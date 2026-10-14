<?php

namespace App\Modules\Shipping\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Order\Models\Order;
use App\Modules\Shipping\Services\KomerceDeliveryOrderService;
use App\Modules\Shipping\Services\RajaOngkirShippingCostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminShippingController extends Controller
{
    public function __construct(
        protected KomerceDeliveryOrderService $deliveryService,
        protected RajaOngkirShippingCostService $rajaOngkirService
    ) {}

    /**
     * Store delivery order in Komerce for fulfillment.
     */
    public function storeOrder(int $id): RedirectResponse
    {
        $order = Order::with(['shipment', 'items', 'user'])->findOrFail($id);

        if (!$order->shipment) {
            return back()->with('error', 'Pesanan ini tidak memiliki informasi pengiriman.');
        }

        if ($order->shipment->delivery_order_no) {
            return back()->with('info', "Pesanan pengiriman sudah dibuat sebelumnya dengan nomor: {$order->shipment->delivery_order_no}");
        }

        $result = $this->deliveryService->storeOrder($order);

        if ($result['success'] ?? false) {
            $orderNo = $result['order_no'] ?? null;
            $payload = $result['data'] ?? [];

            $order->shipment->update([
                'delivery_order_no' => $orderNo,
                'delivery_payload' => $payload,
            ]);

            return back()->with('success', "Order Komerce Delivery berhasil dibuat dengan nomor referensi: {$orderNo}");
        }

        return back()->with('error', 'Gagal membuat order Komerce: ' . ($result['message'] ?? 'Koneksi API gagal.'));
    }

    /**
     * Request courier pickup for the delivery order.
     */
    public function requestPickup(Request $request, int $id): RedirectResponse
    {
        $order = Order::with('shipment')->findOrFail($id);
        $shipment = $order->shipment;

        if (!$shipment || !$shipment->delivery_order_no) {
            return back()->with('error', 'Silakan buat Delivery Order Komerce terlebih dahulu sebelum menjadwalkan pickup.');
        }

        $validated = $request->validate([
            'pickup_date' => 'required|date_format:Y-m-d',
            'pickup_time' => 'required|string',
            'pickup_vehicle' => 'required|string|in:Motor,Mobil,Truk',
        ]);

        $result = $this->deliveryService->requestPickup(
            [$shipment->delivery_order_no],
            $validated['pickup_date'],
            $validated['pickup_time'],
            $validated['pickup_vehicle']
        );

        if ($result['success'] ?? false) {
            $updateData = [
                'pickup_status' => 'scheduled',
                'pickup_time' => $validated['pickup_date'] . ' ' . $validated['pickup_time'] . ':00',
                'pickup_vehicle' => $validated['pickup_vehicle'],
            ];

            // If an AWB or tracking number was assigned immediately
            $pickupData = $result['data'] ?? [];
            if (is_array($pickupData)) {
                $firstItem = isset($pickupData[0]) ? $pickupData[0] : $pickupData;
                if (!empty($firstItem['awb'])) {
                    $updateData['tracking_number'] = $firstItem['awb'];
                    $updateData['shipping_status'] = 'in_transit';
                    $updateData['shipped_at'] = now();
                    $order->update(['status' => 'shipped']);
                }
            }

            $shipment->update($updateData);

            return back()->with('success', 'Jadwal pickup kurir berhasil dikonfirmasi oleh Komerce!');
        }

        return back()->with('error', 'Gagal menjadwalkan pickup: ' . ($result['message'] ?? 'Terjadi kesalahan sistem.'));
    }

    /**
     * Print thermal shipping label (page_5 format).
     */
    public function printLabel(Request $request, int $id): Response|RedirectResponse
    {
        $order = Order::with('shipment')->findOrFail($id);
        $shipment = $order->shipment;

        if (!$shipment || !$shipment->delivery_order_no) {
            return back()->with('error', 'Belum ada nomor order Komerce untuk mencetak label.');
        }

        $format = $request->query('format', 'page_5'); // default thermal 10x10cm
        $result = $this->deliveryService->printLabel($shipment->delivery_order_no, $format);

        if ($result['success'] ?? false) {
            // If base64 encoded PDF is returned
            if (!empty($result['base_64'])) {
                $pdfData = base64_decode($result['base_64']);
                return response($pdfData, 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="label-' . $shipment->delivery_order_no . '.pdf"',
                ]);
            }

            // If a URL/path is returned
            if (!empty($result['path'])) {
                return redirect()->away($result['path']);
            }
        }

        return back()->with('error', 'Gagal mengunduh label pengiriman: ' . ($result['message'] ?? 'File tidak tersedia.'));
    }

    /**
     * Cancel delivery order in Komerce.
     */
    public function cancel(int $id): RedirectResponse
    {
        $order = Order::with('shipment')->findOrFail($id);
        $shipment = $order->shipment;

        if (!$shipment || !$shipment->delivery_order_no) {
            return back()->with('error', 'Tidak ada Delivery Order aktif untuk dibatalkan.');
        }

        $result = $this->deliveryService->cancelOrder($shipment->delivery_order_no);

        if ($result['success'] ?? false) {
            $shipment->update([
                'pickup_status' => 'cancelled',
                'shipping_status' => 'cancelled',
            ]);

            return back()->with('success', "Pesanan pengiriman {$shipment->delivery_order_no} berhasil dibatalkan.");
        }

        return back()->with('error', 'Gagal membatalkan pesanan pengiriman: ' . ($result['message'] ?? 'Kesalahan dari Komerce.'));
    }

    /**
     * Get live airway bill tracking timeline JSON.
     */
    public function track(int $id): JsonResponse
    {
        $order = Order::with('shipment')->findOrFail($id);
        $shipment = $order->shipment;

        if (!$shipment || !$shipment->tracking_number) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nomor resi pengiriman belum tersedia.',
                'timeline' => [],
            ], 404);
        }

        $courier = $shipment->courier_code ?: 'jne';
        $awb = $shipment->tracking_number;

        // Try Komerce AWB history first
        $historyResult = $this->deliveryService->getAirwayBillHistory($courier, $awb);
        if ($historyResult['success'] && !empty($historyResult['data'])) {
            return response()->json([
                'status' => 'success',
                'source' => 'komerce',
                'data' => $historyResult['data'],
            ]);
        }

        // Fallback to RajaOngkir tracking
        $roResult = $this->rajaOngkirService->trackWaybill($courier, $awb);

        return response()->json([
            'status' => $roResult['status'] ?? 'success',
            'source' => 'rajaongkir',
            'data' => $roResult['data'] ?? [],
            'message' => $roResult['message'] ?? '',
        ]);
    }
}
