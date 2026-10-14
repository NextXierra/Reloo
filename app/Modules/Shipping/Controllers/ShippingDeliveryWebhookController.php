<?php

namespace App\Modules\Shipping\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Order\Models\OrderShipment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShippingDeliveryWebhookController extends Controller
{
    /**
     * Handle incoming delivery status updates from Komerce.
     */
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        Log::info('Komerce shipping webhook received', $payload);

        // Normalize payload (root level or nested under 'data')
        $orderNo = $payload['order_no'] ?? ($payload['data']['order_no'] ?? null);
        $cnote = $payload['cnote'] ?? ($payload['data']['cnote'] ?? ($payload['airway_bill'] ?? null));
        $rawStatus = $payload['status'] ?? ($payload['data']['status'] ?? null);

        if (!$orderNo && !$cnote) {
            return response()->json([
                'meta' => [
                    'code' => 400,
                    'status' => 'failed',
                    'message' => 'Missing order_no or cnote identifier.',
                ],
            ], 400);
        }

        // Find shipment by delivery_order_no or tracking_number (cnote)
        $shipmentQuery = OrderShipment::with('order');

        if ($orderNo) {
            $shipmentQuery->where('delivery_order_no', $orderNo);
        }

        if ($cnote) {
            if ($orderNo) {
                $shipmentQuery->orWhere('tracking_number', $cnote);
            } else {
                $shipmentQuery->where('tracking_number', $cnote);
            }
        }

        $shipment = $shipmentQuery->first();

        if (!$shipment) {
            Log::warning('Komerce shipping webhook: shipment not found', [
                'order_no' => $orderNo,
                'cnote' => $cnote,
            ]);

            return response()->json([
                'meta' => [
                    'code' => 404,
                    'status' => 'not_found',
                    'message' => 'Shipment not found for provided order_no or cnote.',
                ],
            ], 404);
        }

        // Update tracking number if cnote is provided
        if ($cnote && empty($shipment->tracking_number)) {
            $shipment->tracking_number = $cnote;
        }

        // Map status
        $normalizedStatus = strtolower(trim((string) $rawStatus));
        $order = $shipment->order;

        if (in_array($normalizedStatus, ['dijemput', 'diproses', 'pickup', 'processing', 'picked_up'])) {
            $shipment->shipping_status = 'in_transit';
            if (!$shipment->shipped_at) {
                $shipment->shipped_at = now();
            }
            if ($order && in_array($order->status, ['processing', 'paid'])) {
                $order->update(['status' => 'shipped']);
            }
        } elseif (in_array($normalizedStatus, ['dikirim', 'in_transit', 'shipping', 'on_delivery', 'with_courier'])) {
            $shipment->shipping_status = 'in_transit';
            if (!$shipment->shipped_at) {
                $shipment->shipped_at = now();
            }
            if ($order && $order->status !== 'completed' && $order->status !== 'cancelled') {
                $order->update(['status' => 'shipped']);
            }
        } elseif (in_array($normalizedStatus, ['selesai', 'delivered', 'sukses', 'success'])) {
            $shipment->shipping_status = 'delivered';
            $shipment->delivered_at = now();
            if ($order && $order->status !== 'cancelled') {
                $order->update(['status' => 'completed']);
            }
        } elseif (in_array($normalizedStatus, ['dibatalkan', 'cancelled', 'canceled', 'retur', 'failed'])) {
            $shipment->shipping_status = 'cancelled';
        }

        // Store webhook payload in shipment delivery_payload
        $currentPayload = is_array($shipment->delivery_payload) ? $shipment->delivery_payload : [];
        $currentPayload['last_webhook'] = [
            'received_at' => now()->toIso8601String(),
            'payload' => $payload,
        ];
        $shipment->delivery_payload = $currentPayload;

        $shipment->save();

        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Webhook processed successfully',
            ],
            'data' => [
                'order_no' => $shipment->delivery_order_no,
                'tracking_number' => $shipment->tracking_number,
                'shipping_status' => $shipment->shipping_status,
                'order_status' => $order?->status,
            ],
        ], 200);
    }
}
