<?php

namespace App\Modules\Payment\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Models\Product;
use App\Modules\Order\Models\Order;
use App\Modules\Payment\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MidtransWebhookController extends Controller
{
    public function __construct(
        protected MidtransService $midtransService
    ) {}

    public function handle(Request $request): JsonResponse
    {
        $orderId = (string) $request->input('order_id');
        $statusCode = (string) $request->input('status_code');
        $grossAmount = (string) $request->input('gross_amount');
        $signatureKey = (string) $request->input('signature_key');

        // Signature check if non-demo key is in use
        if (!empty(env('MIDTRANS_SERVER_KEY')) && !str_contains(env('MIDTRANS_SERVER_KEY'), 'DemoKey')) {
            if (!$this->midtransService->verifySignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid signature key',
                ], 403);
            }
        }

        $order = Order::with(['payment', 'items'])->where('order_number', $orderId)->first();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found',
            ], 404);
        }

        $transactionStatus = $request->input('transaction_status');
        $fraudStatus = $request->input('fraud_status');
        $transactionId = $request->input('transaction_id');
        $paymentType = $request->input('payment_type');

        // State Machine Update
        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept') {
                $order->update(['status' => 'paid']);
                $order->payment?->update([
                    'payment_status' => 'paid',
                    'transaction_id' => $transactionId,
                    'payment_type' => $paymentType,
                    'paid_at' => now(),
                    'gateway_payload' => json_encode($request->all()),
                ]);
            }
        } elseif ($transactionStatus === 'settlement') {
            $order->update(['status' => 'paid']);
            $order->payment?->update([
                'payment_status' => 'paid',
                'transaction_id' => $transactionId,
                'payment_type' => $paymentType,
                'paid_at' => now(),
                'gateway_payload' => json_encode($request->all()),
            ]);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            // Cancel order & Restock products
            if ($order->status !== 'cancelled') {
                $order->update(['status' => 'cancelled']);
                $order->payment?->update([
                    'payment_status' => 'failed',
                    'gateway_payload' => json_encode($request->all()),
                ]);

                // Restore stock
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                    }
                }
            }
        } elseif ($transactionStatus === 'pending') {
            $order->payment?->update([
                'payment_status' => 'pending',
                'gateway_payload' => json_encode($request->all()),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Webhook notification processed successfully',
        ]);
    }
}
