<?php

namespace App\Modules\Shipping\Services;

use App\Modules\Order\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class KomerceDeliveryOrderService
{
    protected string $apiKey;
    protected string $baseUrl;
    protected string $environment;
    protected int $timeout;
    protected bool $verifySsl;
    protected array $shipper;

    public function __construct()
    {
        $this->apiKey = (string) config('services.komerce_delivery.api_key', 'MDbaQJym248f87abf1ac249e3qdXbx5I');
        $this->baseUrl = rtrim((string) config('services.komerce_delivery.base_url', 'https://api-sandbox.collaborator.komerce.id'), '/');
        $this->environment = (string) config('services.komerce_delivery.env', 'sandbox');
        $this->timeout = (int) config('services.komerce_delivery.timeout', 12);
        $this->verifySsl = (bool) config('services.komerce_delivery.verify_ssl', true);

        $this->shipper = [
            'name' => (string) config('services.komerce_delivery.shipper_name', 'Reloo Store'),
            'phone' => (string) config('services.komerce_delivery.shipper_phone', '081234567890'),
            'email' => (string) config('services.komerce_delivery.shipper_email', 'admin@reloo.id'),
            'address' => (string) config('services.komerce_delivery.shipper_address', 'Jl. Reloo Central Hub No. 1, Jakarta'),
            'destination_id' => (int) config('services.komerce_delivery.shipper_destination_id', 17473),
            'origin_pin_point' => (string) config('services.komerce_delivery.origin_pin_point', '-6.175392, 106.827153'),
        ];
    }

    /**
     * Search destination via Komerce tariff search API.
     */
    public function searchDestination(string $keyword): array
    {
        $keyword = trim($keyword);
        if (strlen($keyword) < 3) {
            return ['success' => false, 'message' => 'Keyword minimal 3 karakter.', 'data' => []];
        }

        try {
            $response = $this->client()->get("{$this->baseUrl}/tariff/api/v1/destination/search", [
                'keyword' => $keyword,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data') ?? [],
                    'message' => $response->json('meta.message') ?? 'Berhasil mencari destinasi.',
                ];
            }

            return $this->errorResponse($response, 'Gagal mencari destinasi Komerce.');
        } catch (Throwable $e) {
            return $this->exceptionResponse($e, 'searchDestination');
        }
    }

    /**
     * Calculate tariff using Komerce Delivery tariff API.
     */
    public function calculateTariff(array $params): array
    {
        try {
            $response = $this->client()->get("{$this->baseUrl}/tariff/api/v1/calculate", array_merge([
                'shipper_destination_id' => $this->shipper['destination_id'],
                'origin_pin_point' => $this->shipper['origin_pin_point'],
            ], $params));

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data') ?? [],
                    'message' => $response->json('meta.message') ?? 'Berhasil menghitung tarif Komerce.',
                ];
            }

            return $this->errorResponse($response, 'Gagal menghitung tarif Komerce.');
        } catch (Throwable $e) {
            return $this->exceptionResponse($e, 'calculateTariff');
        }
    }

    /**
     * Store delivery order in Komerce system.
     */
    public function storeOrder(Order $order, array $overrides = []): array
    {
        $order->loadMissing(['items', 'shipment', 'user']);

        $shipment = $order->shipment;
        $address = is_array($order->shipping_address) ? $order->shipping_address : [];
        $recipientName = $address['recipient_name'] ?? ($order->user->name ?? 'Pelanggan');
        $phoneNumber = $address['phone_number'] ?? '081234567890';
        $fullAddress = trim(implode(', ', array_filter([
            $address['address_line'] ?? '',
            $address['district_name'] ?? '',
            $address['city_name'] ?? '',
            $address['province_name'] ?? '',
            $address['postal_code'] ?? '',
        ])));

        $receiverDestinationId = $shipment?->destination_id ?? (int) ($overrides['receiver_destination_id'] ?? $this->shipper['destination_id']);

        $orderDetails = [];
        foreach ($order->items as $item) {
            $orderDetails[] = [
                'product_name' => $item->product_name,
                'product_variant_name' => 'Default',
                'product_price' => (float) $item->price,
                'product_weight' => (int) ($item->weight_grams ?: 100),
                'product_width' => 10,
                'product_height' => 10,
                'product_length' => 10,
                'qty' => (int) $item->quantity,
                'subtotal' => (float) $item->subtotal,
            ];
        }

        $courierCode = strtolower($shipment?->courier_code ?: 'jne');
        $courierService = strtoupper($shipment?->courier_service ?: 'REG');

        $payload = array_merge([
            'order_date' => date('Y-m-d'),
            'shipper_name' => $this->shipper['name'],
            'shipper_phone' => $this->shipper['phone'],
            'shipper_destination_id' => $this->shipper['destination_id'],
            'shipper_address' => $this->shipper['address'],
            'receiver_name' => $recipientName,
            'receiver_phone' => $phoneNumber,
            'receiver_destination_id' => $receiverDestinationId,
            'receiver_address' => $fullAddress ?: 'Alamat pengiriman pesanan ' . $order->order_number,
            'shipping' => $courierCode,
            'shipping_type' => $courierService,
            'payment_method' => 'NON COD',
            'order_details' => $orderDetails,
            'reference_id' => $order->order_number,
        ], $overrides);

        try {
            $response = $this->client()->post("{$this->baseUrl}/order/api/v1/orders/store", $payload);

            if ($response->successful()) {
                $data = $response->json('data') ?? [];
                $orderNo = $data['order_no'] ?? ($data['order_id'] ?? null);

                return [
                    'success' => true,
                    'order_no' => $orderNo,
                    'order_id' => $data['order_id'] ?? null,
                    'data' => $data,
                    'message' => $response->json('meta.message') ?? 'Pesanan pengiriman berhasil dibuat di Komerce.',
                ];
            }

            return $this->errorResponse($response, 'Gagal membuat pesanan pengiriman di Komerce.');
        } catch (Throwable $e) {
            return $this->exceptionResponse($e, 'storeOrder');
        }
    }

    /**
     * Request courier pickup for one or more order numbers.
     */
    public function requestPickup(
        array $orderNumbers,
        string $pickupDate,
        string $pickupTime,
        string $vehicle = 'Motor'
    ): array {
        $cleanNumbers = array_values(array_filter($orderNumbers));

        if (empty($cleanNumbers)) {
            return ['success' => false, 'message' => 'Nomor order tidak boleh kosong.', 'data' => []];
        }

        $payload = [
            'orders' => $cleanNumbers,
            'pickup_date' => $pickupDate,
            'pickup_time' => $pickupTime,
            'vehicle' => $vehicle,
        ];

        try {
            $response = $this->client()->post("{$this->baseUrl}/order/api/v1/pickup/request", $payload);

            if ($response->successful()) {
                $data = $response->json('data') ?? [];
                return [
                    'success' => true,
                    'data' => $data,
                    'message' => $response->json('meta.message') ?? 'Pickup pengiriman berhasil dijadwalkan.',
                ];
            }

            return $this->errorResponse($response, 'Gagal menjadwalkan pickup di Komerce.');
        } catch (Throwable $e) {
            return $this->exceptionResponse($e, 'requestPickup');
        }
    }

    /**
     * Generate or download thermal shipping label PDF.
     */
    public function printLabel(string $orderNo, string $pageFormat = 'page_5'): array
    {
        $orderNo = trim($orderNo);
        if (empty($orderNo)) {
            return ['success' => false, 'message' => 'Nomor order tidak boleh kosong.', 'data' => null];
        }

        try {
            $response = $this->client()->post("{$this->baseUrl}/order/api/v1/orders/print-label", [
                'order_no' => $orderNo,
                'page' => $pageFormat,
            ]);

            if ($response->successful()) {
                $data = $response->json('data') ?? [];
                return [
                    'success' => true,
                    'data' => $data,
                    'path' => $data['path'] ?? null,
                    'base_64' => $data['base_64'] ?? null,
                    'message' => $response->json('meta.message') ?? 'Label pengiriman berhasil digenerate.',
                ];
            }

            return $this->errorResponse($response, 'Gagal mencetak label pengiriman di Komerce.');
        } catch (Throwable $e) {
            return $this->exceptionResponse($e, 'printLabel');
        }
    }

    /**
     * Cancel delivery order in Komerce.
     */
    public function cancelOrder(string $orderNo): array
    {
        $orderNo = trim($orderNo);
        if (empty($orderNo)) {
            return ['success' => false, 'message' => 'Nomor order tidak boleh kosong.', 'data' => null];
        }

        try {
            $response = $this->client()->put("{$this->baseUrl}/order/api/v1/orders/cancel", [
                'order_no' => $orderNo,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data') ?? [],
                    'message' => $response->json('meta.message') ?? 'Pesanan pengiriman berhasil dibatalkan.',
                ];
            }

            return $this->errorResponse($response, 'Gagal membatalkan pesanan di Komerce.');
        } catch (Throwable $e) {
            return $this->exceptionResponse($e, 'cancelOrder');
        }
    }

    /**
     * Get order detail from Komerce.
     */
    public function getOrderDetail(string $orderNo): array
    {
        $orderNo = trim($orderNo);

        try {
            $response = $this->client()->get("{$this->baseUrl}/order/api/v1/orders/detail", [
                'order_no' => $orderNo,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data') ?? [],
                    'message' => $response->json('meta.message') ?? 'Detail pesanan ditemukan.',
                ];
            }

            return $this->errorResponse($response, 'Gagal mengambil detail pesanan dari Komerce.');
        } catch (Throwable $e) {
            return $this->exceptionResponse($e, 'getOrderDetail');
        }
    }

    /**
     * Get Airway Bill (AWB) tracking history from Komerce.
     */
    public function getAirwayBillHistory(string $courier, string $awb): array
    {
        try {
            $response = $this->client()->get("{$this->baseUrl}/order/api/v1/orders/history-airway-bill", [
                'shipping' => strtolower($courier),
                'airway_bill' => trim($awb),
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data') ?? [],
                    'message' => $response->json('meta.message') ?? 'History AWB berhasil diambil.',
                ];
            }

            return $this->errorResponse($response, 'Gagal mengambil riwayat resi Komerce.');
        } catch (Throwable $e) {
            return $this->exceptionResponse($e, 'getAirwayBillHistory');
        }
    }

    /**
     * Normalized HTTP client for Komerce Collaborator Delivery API.
     */
    protected function client()
    {
        return Http::withHeaders([
            'x-api-key' => $this->apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'User-Agent' => 'Reloo-Marketplace/1.0',
        ])
        ->timeout($this->timeout)
        ->withOptions([
            'verify' => $this->verifySsl,
        ]);
    }

    protected function errorResponse($response, string $fallbackMessage): array
    {
        $json = $response->json();
        $message = $json['meta']['message'] ?? ($json['message'] ?? $fallbackMessage);

        Log::warning("KomerceDelivery API Error [{$response->status()}]: {$message}", [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return [
            'success' => false,
            'code' => $response->status(),
            'message' => $message,
            'data' => $json['data'] ?? null,
        ];
    }

    protected function exceptionResponse(Throwable $e, string $action): array
    {
        Log::error("KomerceDelivery Exception in {$action}: " . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
        ]);

        return [
            'success' => false,
            'code' => 500,
            'message' => "Terjadi kesalahan koneksi ke layanan Komerce ({$action}): " . $e->getMessage(),
            'data' => null,
        ];
    }
}
