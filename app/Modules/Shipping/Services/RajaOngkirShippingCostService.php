<?php

namespace App\Modules\Shipping\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class RajaOngkirShippingCostService
{
    protected string $apiKey;
    protected string $baseUrl;
    protected int $defaultOriginId;
    protected int $timeout;
    protected bool $verifySsl;

    public function __construct()
    {
        $this->apiKey = (string) config('services.rajaongkir.api_key', 'MDbaQJym248f87abf1ac249e3qdXbx5I');
        $this->baseUrl = rtrim((string) config('services.rajaongkir.base_url', 'https://rajaongkir.komerce.id/api/v1'), '/');
        $this->defaultOriginId = (int) config('services.rajaongkir.origin_id', 17473);
        $this->timeout = (int) config('services.rajaongkir.timeout', 10);
        $this->verifySsl = (bool) config('services.rajaongkir.verify_ssl', true);
    }

    /**
     * Search domestic destinations by keyword (subdistrict, city, province).
     */
    public function searchDestinations(string $query, int $limit = 10, int $offset = 0): array
    {
        $cleanQuery = trim($query);
        if (strlen($cleanQuery) < 3) {
            return [];
        }

        $cacheKey = 'ro_dest_' . md5(strtolower($cleanQuery) . "_{$limit}_{$offset}");

        if (Cache::has($cacheKey)) {
            $cached = Cache::get($cacheKey);
            if (!empty($cached)) {
                return $cached;
            }
        }

        try {
            $response = $this->client()
                ->get("{$this->baseUrl}/destination/domestic-destination", [
                    'search' => $cleanQuery,
                    'limit' => $limit,
                    'offset' => $offset,
                ]);

            if ($response->successful()) {
                $data = $response->json('data') ?? [];
                if (!empty($data) && is_array($data)) {
                    Cache::put($cacheKey, $data, 86400);
                    return $data;
                }
            }

            Log::warning('RajaOngkir destination search non-200 response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (Throwable $e) {
            Log::error('RajaOngkir destination search failed: ' . $e->getMessage(), [
                'query' => $cleanQuery,
            ]);
        }

        return [];
    }

    /**
     * Calculate shipping cost for a specific courier between origin and destination.
     *
     * @return array Array of rate options
     */
    public function calculateDomesticCost(int $originId, int $destinationId, int $weightGrams, string $courier): array
    {
        $courierCode = strtolower(trim($courier));
        $weight = max(1, $weightGrams);

        try {
            $response = $this->client()
                ->asForm()
                ->post("{$this->baseUrl}/calculate/domestic-cost", [
                    'origin' => $originId ?: $this->defaultOriginId,
                    'destination' => $destinationId,
                    'weight' => $weight,
                    'courier' => $courierCode,
                ]);

            if ($response->successful()) {
                $data = $response->json('data') ?? [];
                if (!empty($data) && is_array($data)) {
                    $rates = [];
                    foreach ($data as $item) {
                        $cost = (float) ($item['cost'] ?? 0);
                        $rates[] = [
                            'code' => $courierCode,
                            'name' => $item['name'] ?? strtoupper($courierCode),
                            'service' => $item['service'] ?? 'REG',
                            'description' => $item['description'] ?? '',
                            'cost' => $cost,
                            'formatted_cost' => 'Rp ' . number_format($cost, 0, ',', '.'),
                            'etd' => $this->formatEtd($item['etd'] ?? ''),
                            'weight_kg' => max(1, (int) ceil($weight / 1000)),
                            'is_live' => true,
                        ];
                    }
                    return $rates;
                }
            }

            Log::warning('RajaOngkir calculateDomesticCost unsuccessful', [
                'status' => $response->status(),
                'body' => $response->body(),
                'origin' => $originId,
                'destination' => $destinationId,
                'courier' => $courierCode,
            ]);
        } catch (Throwable $e) {
            Log::error('RajaOngkir calculateDomesticCost exception: ' . $e->getMessage(), [
                'origin' => $originId,
                'destination' => $destinationId,
                'courier' => $courierCode,
            ]);
        }

        return $this->getFallbackRate($courierCode, $weight);
    }

    /**
     * Calculate rates across multiple couriers.
     */
    public function calculateAllCouriers(
        int $originId,
        int $destinationId,
        int $weightGrams,
        array $couriers = ['jne', 'sicepat', 'jnt']
    ): array {
        $results = [];

        foreach ($couriers as $courier) {
            $rates = $this->calculateDomesticCost($originId, $destinationId, $weightGrams, $courier);
            if (!empty($rates)) {
                $results[$courier] = $rates;
            }
        }


        return $results;
    }

    /**
     * Track Waybill (AWB) status via RajaOngkir.
     */
    public function trackWaybill(string $courier, string $awb): array
    {
        $courierCode = strtolower(trim($courier));
        $awbNumber = trim($awb);

        try {
            $response = $this->client()
                ->asForm()
                ->post("{$this->baseUrl}/track/waybill", [
                    'courier' => $courierCode,
                    'awb' => $awbNumber,
                ]);

            if ($response->successful()) {
                $json = $response->json();
                return [
                    'success' => true,
                    'status' => $json['meta']['status'] ?? 'success',
                    'data' => $json['data'] ?? [],
                ];
            }

            return [
                'success' => false,
                'status' => 'not_found',
                'message' => $response->json('meta.message') ?? 'Nomor resi tidak ditemukan atau belum terupdate di sistem kurir.',
                'data' => null,
            ];
        } catch (Throwable $e) {
            Log::error('RajaOngkir trackWaybill error: ' . $e->getMessage(), [
                'courier' => $courierCode,
                'awb' => $awbNumber,
            ]);

            return [
                'success' => false,
                'status' => 'error',
                'message' => 'Gagal melacak resi: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Get calibrated fallback rates when API is offline or destination ID is unset.
     */
    public function getFallbackRate(string $courierCode, int $weightGrams): array
    {
        $code = strtolower($courierCode);
        $weightKg = max(1, (int) ceil($weightGrams / 1000));

        $matrix = [
            'jne' => [
                'name' => 'JNE Express',
                'service' => 'REG (Reguler)',
                'description' => 'Layanan Reguler JNE',
                'rate_per_kg' => 15000,
                'etd' => '1-3 Hari',
            ],
            'sicepat' => [
                'name' => 'SiCepat Ekspres',
                'service' => 'REG (Regular)',
                'description' => 'Layanan Regular SiCepat',
                'rate_per_kg' => 14000,
                'etd' => '1-2 Hari',
            ],
            'jnt' => [
                'name' => 'J&T Express',
                'service' => 'EZ (Standard)',
                'description' => 'Layanan Reguler J&T',
                'rate_per_kg' => 16000,
                'etd' => '1-3 Hari',
            ],
            'flat' => [
                'name' => 'Kurir Reloo (Flat Rate)',
                'service' => 'Standard Delivery',
                'description' => 'Pengiriman Kurir Internal Reloo',
                'rate_per_kg' => 10000,
                'flat' => true,
                'etd' => '2-4 Hari',
            ],
        ];

        $courier = $matrix[$code] ?? $matrix['jne'];
        $cost = !empty($courier['flat'])
            ? (float) $courier['rate_per_kg']
            : (float) ($courier['rate_per_kg'] * $weightKg);

        return [
            [
                'code' => $code,
                'name' => $courier['name'],
                'service' => $courier['service'],
                'description' => $courier['description'],
                'cost' => $cost,
                'formatted_cost' => 'Rp ' . number_format($cost, 0, ',', '.'),
                'etd' => $courier['etd'],
                'weight_kg' => $weightKg,
                'is_live' => false,
            ]
        ];
    }

    /**
     * Normalized HTTP client configured with required headers and security options.
     */
    protected function client()
    {
        return Http::withHeaders([
            'key' => $this->apiKey,
            'Accept' => 'application/json',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        ])
        ->timeout($this->timeout)
        ->withOptions([
            'verify' => $this->verifySsl,
        ]);
    }

    /**
     * Format estimated delivery time string.
     */
    protected function formatEtd(string $etd): string
    {
        $etd = trim($etd);
        if (empty($etd)) {
            return '1-3 Hari';
        }

        if (str_contains(strtolower($etd), 'day') || str_contains(strtolower($etd), 'hari')) {
            return str_ireplace('day', 'Hari', $etd);
        }

        return $etd . ' Hari';
    }
}
