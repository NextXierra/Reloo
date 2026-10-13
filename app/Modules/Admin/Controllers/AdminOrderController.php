<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Models\Product;
use App\Modules\Order\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminOrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'payment', 'shipment', 'items'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where('order_number', 'like', "%{$keyword}%");
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin::orders.index', compact('orders'));
    }

    public function show(int $id): View
    {
        $order = Order::with(['user', 'payment', 'shipment', 'items.product'])->findOrFail($id);
        return view('admin::orders.show', compact('order'));
    }

    public function verifyPayment(int $id): RedirectResponse
    {
        $order = Order::with('payment')->findOrFail($id);

        if ($order->payment) {
            $order->payment->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);
        }

        $order->update(['status' => 'processing']);

        return back()->with('success', "Pembayaran untuk pesanan {$order->order_number} berhasil diverifikasi! Status beralih ke 'Diproses Toko'.");
    }

    public function updateShipment(Request $request, int $id): RedirectResponse
    {
        $order = Order::with('shipment')->findOrFail($id);

        $validated = $request->validate([
            'tracking_number' => 'required|string|max:100',
            'courier_code' => 'nullable|string',
            'courier_service' => 'nullable|string',
        ]);

        if ($order->shipment) {
            $order->shipment->update([
                'tracking_number' => $validated['tracking_number'],
                'shipping_status' => 'in_transit',
                'shipped_at' => now(),
                'courier_code' => $validated['courier_code'] ?? $order->shipment->courier_code,
                'courier_service' => $validated['courier_service'] ?? $order->shipment->courier_service,
            ]);
        }

        $order->update(['status' => 'shipped']);

        return back()->with('success', "Nomor resi '{$validated['tracking_number']}' berhasil disimpan! Status pesanan kini beralih ke 'Dalam Pengiriman (Shipped)'.");
    }

    public function cancelOrder(int $id): RedirectResponse
    {
        $order = Order::with(['items', 'payment'])->findOrFail($id);

        if ($order->status !== 'cancelled') {
            $order->update(['status' => 'cancelled']);
            if ($order->payment) {
                $order->payment->update(['payment_status' => 'failed']);
            }

            // Restore product stock
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                }
            }
        }

        return back()->with('success', "Pesanan {$order->order_number} berhasil dibatalkan dan stok produk telah dikembalikan.");
    }
}
