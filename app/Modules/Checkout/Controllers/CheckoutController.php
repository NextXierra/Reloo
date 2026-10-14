<?php

namespace App\Modules\Checkout\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Models\UserAddress;
use App\Modules\Cart\Services\CartService;
use App\Modules\Catalog\Models\Product;
use App\Modules\Checkout\Requests\CheckoutProcessRequest;
use App\Modules\Checkout\Services\ShippingCostCalculator;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Models\OrderItem;
use App\Modules\Order\Models\OrderPayment;
use App\Modules\Order\Models\OrderShipment;
use App\Modules\Payment\Services\QrislyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected ShippingCostCalculator $shippingCalculator,
        protected QrislyService $qrislyService
    ) {}

    public function index(): View|RedirectResponse
    {
        $userId = Auth::id();
        $items = $this->cartService->getCartItems($userId);

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong. Silakan pilih produk terlebih dahulu.');
        }

        $totals = $this->cartService->calculateTotals($items);
        $addresses = UserAddress::where('user_id', $userId)->get();
        $couriers = $this->shippingCalculator->getAvailableCouriers($totals['total_weight']);

        return view('checkout::index', compact('items', 'totals', 'addresses', 'couriers'));
    }

    public function process(CheckoutProcessRequest $request): RedirectResponse
    {
        $userId = Auth::id();
        $cartItems = $this->cartService->getCartItems($userId);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        // 1. Resolve Shipping Address Snapshot
        $addressSnapshot = [];
        $savedAddress = null;
        if ($request->filled('address_id')) {
            $savedAddress = UserAddress::where('user_id', $userId)->where('id', $request->address_id)->firstOrFail();
            $addressSnapshot = [
                'recipient_name' => $savedAddress->recipient_name,
                'phone_number' => $savedAddress->phone_number,
                'address_line' => $savedAddress->address_line,
                'province_name' => $savedAddress->province_name,
                'city_name' => $savedAddress->city_name,
                'district_name' => $savedAddress->district_name,
                'postal_code' => $savedAddress->postal_code,
            ];
        } else {
            $addressSnapshot = [
                'recipient_name' => $request->input('recipient_name'),
                'phone_number' => $request->input('phone_number'),
                'address_line' => $request->input('address_line'),
                'province_name' => $request->input('province_name'),
                'city_name' => $request->input('city_name'),
                'district_name' => $request->input('district_name'),
                'postal_code' => $request->input('postal_code'),
            ];

            if ($request->boolean('save_address')) {
                UserAddress::create(array_merge($addressSnapshot, [
                    'user_id' => $userId,
                    'is_default' => (UserAddress::where('user_id', $userId)->count() === 0),
                ]));
            }
        }

        // 2. Atomic Order Transaction
        $order = DB::transaction(function () use ($userId, $cartItems, $addressSnapshot, $savedAddress, $request) {
            foreach ($cartItems as $cartItem) {
                $product = Product::lockForUpdate()->find($cartItem->product_id);
                if (!$product || !$product->is_active) {
                    throw ValidationException::withMessages([
                        'stock' => "Produk '{$cartItem->product_name}' sudah tidak aktif atau tidak ditemukan.",
                    ]);
                }
                if ($product->stock < $cartItem->quantity) {
                    throw ValidationException::withMessages([
                        'stock' => "Maaf, stok '{$product->name}' tidak mencukupi (Tersedia {$product->stock} unit, diminta {$cartItem->quantity} unit).",
                    ]);
                }
            }

            $totals = $this->cartService->calculateTotals($cartItems);
            $destinationId = $request->filled('destination_id') ? (int) $request->destination_id : null;
            if (!$destinationId && !empty($savedAddress) && !app()->environment('testing')) {
                $searchQuery = $savedAddress->district_name ?: ($savedAddress->city_name ?: $savedAddress->postal_code);
                if ($searchQuery) {
                    $roResults = app(\App\Modules\Shipping\Services\RajaOngkirShippingCostService::class)->searchDestinations($searchQuery, 1);
                    if (!empty($roResults) && !empty($roResults[0]['id'])) {
                        $destinationId = (int) $roResults[0]['id'];
                    }
                }
            }
            $shippingInfo = $this->shippingCalculator->calculate($request->courier_code, $totals['total_weight'], $destinationId);
            $grandTotal = $totals['items_total'] + $shippingInfo['cost'];
            $orderNumber = 'INV/' . date('Ymd') . '/RLO-' . strtoupper(Str::random(5));

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $userId,
                'status' => 'pending_payment',
                'items_total' => $totals['items_total'],
                'shipping_cost' => $shippingInfo['cost'],
                'grand_total' => $grandTotal,
                'customer_note' => $request->input('customer_note'),
                'shipping_address' => $addressSnapshot,
            ]);

            foreach ($cartItems as $cartItem) {
                $product = Product::find($cartItem->product_id);
                $product->decrement('stock', $cartItem->quantity);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $cartItem->quantity,
                    'weight_grams' => $product->weight_grams,
                    'subtotal' => $product->price * $cartItem->quantity,
                ]);
            }

            OrderShipment::create([
                'order_id' => $order->id,
                'courier_code' => $shippingInfo['code'],
                'courier_service' => $shippingInfo['service'],
                'shipping_status' => 'pending',
                'destination_id' => $destinationId,
                'origin_id' => (int) config('services.rajaongkir.origin_id', 17473),
            ]);

            $payment = OrderPayment::create([
                'order_id' => $order->id,
                'payment_gateway' => $request->payment_gateway,
                'payment_type' => $request->payment_gateway === 'qrisly' ? 'qris' : 'bank_transfer',
                'payment_status' => 'pending',
            ]);

            // If RajaOngkir Qrisly, generate QRIS
            if ($request->payment_gateway === 'qrisly') {
                $qrisData = $this->qrislyService->generateQris($grandTotal, $orderNumber);
                $payment->update([
                    'transaction_id' => $qrisData['history_id'],
                    'gateway_payload' => json_encode($qrisData),
                ]);
            }

            $this->cartService->clearCart($userId);

            return $order;
        });

        return redirect()->route('payment.pay', $order->order_number)->with('success', 'Pesanan berhasil dibuat. Silakan selesaikan pembayaran.');
    }
}
