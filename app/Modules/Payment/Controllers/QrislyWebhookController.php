<?php

namespace App\Modules\Payment\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Models\Product;
use App\Modules\Order\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QrislyWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();
        $event = $payload['event'] ?? ($payload['type'] ?? '');
        $data = $payload['data'] ?? [];
        $status = $data['payment_status'] ?? ($data['status'] ?? ($payload['payment_status'] ?? ($payload['status'] ?? '')));
        $historyId = $data['qris_history_id'] ?? ($data['history_id'] ?? ($payload['qris_history_id'] ?? ($payload['history_id'] ?? null)));
        if ($historyId) {
            $order = Order::with(['payment', 'items'])
                ->whereHas('payment', function ($q) use ($historyId) {
                    $q->where('transaction_id', $historyId);
                })
                ->first();

            if ($order) {
                if ($event === 'payment.success' || $status === 'paid') {
                    $order->update(['status' => 'paid']);
                    $order->payment?->update([
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                        'gateway_payload' => json_encode($payload),
                    ]);
                } elseif ($event === 'payment.expired' || $status === 'expired') {
                    if ($order->status !== 'cancelled') {
                        $order->update(['status' => 'cancelled']);
                        $order->payment?->update([
                            'payment_status' => 'expired',
                            'gateway_payload' => json_encode($payload),
                        ]);

                        // Restock inventory
                        foreach ($order->items as $item) {
                            if ($item->product_id) {
                                Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                            }
                        }
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Qrisly webhook processed successfully',
        ]);
    }
}
