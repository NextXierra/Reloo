<?php

namespace App\Modules\Shipping\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Shipping\Services\RajaOngkirShippingCostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShippingApiController extends Controller
{
    public function __construct(
        protected RajaOngkirShippingCostService $shippingCostService
    ) {}

    /**
     * Search domestic destinations by subdistrict, city, or province name.
     */
    public function destinations(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('search', ''));

        if (strlen($search) < 3) {
            return response()->json([
                'meta' => [
                    'code' => 422,
                    'status' => 'error',
                    'message' => 'Kata kunci pencarian minimal 3 karakter.',
                ],
                'data' => [],
            ], 422);
        }

        $limit = max(1, min(20, (int) $request->query('limit', 10)));
        $offset = max(0, (int) $request->query('offset', 0));

        $results = $this->shippingCostService->searchDestinations($search, $limit, $offset);

        // Standardize output items
        $data = array_map(function ($item) {
            return [
                'id' => $item['id'] ?? null,
                'label' => $item['label'] ?? '',
                'subdistrict_name' => $item['subdistrict_name'] ?? '',
                'district_name' => $item['district_name'] ?? '',
                'city_name' => $item['city_name'] ?? '',
                'province_name' => $item['province_name'] ?? '',
                'postal_code' => $item['zip_code'] ?? ($item['postal_code'] ?? ''),
            ];
        }, $results);

        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Destinasi ditemukan.',
            ],
            'data' => $data,
        ]);
    }

    /**
     * Calculate shipping costs for a destination.
     */
    public function calculate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'destination_id' => 'required|integer',
            'weight_grams' => 'required|integer|min:1',
            'courier' => 'nullable|string',
            'origin_id' => 'nullable|integer',
        ]);

        $originId = (int) ($validated['origin_id'] ?? config('services.rajaongkir.origin_id', 17473));
        $destinationId = (int) $validated['destination_id'];
        $weightGrams = (int) $validated['weight_grams'];

        if (!empty($validated['courier'])) {
            $rates = $this->shippingCostService->calculateDomesticCost(
                $originId,
                $destinationId,
                $weightGrams,
                $validated['courier']
            );

            return response()->json([
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Kalkulasi ongkir berhasil.',
                ],
                'data' => $rates,
            ]);
        }

        $allCouriers = $this->shippingCostService->calculateAllCouriers(
            $originId,
            $destinationId,
            $weightGrams,
            ['jne', 'sicepat', 'jnt']
        );

        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Kalkulasi ongkir semua kurir berhasil.',
            ],
            'data' => $allCouriers,
        ]);
    }

    /**
     * Track shipment waybill.
     */
    public function track(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'courier' => 'required|string',
            'awb' => 'required|string',
        ]);

        $result = $this->shippingCostService->trackWaybill($validated['courier'], $validated['awb']);

        $statusCode = ($result['success'] ?? false) ? 200 : 404;

        return response()->json([
            'meta' => [
                'code' => $statusCode,
                'status' => ($result['success'] ?? false) ? 'success' : 'error',
                'message' => $result['message'] ?? 'Informasi resi pengiriman.',
            ],
            'data' => $result['data'] ?? null,
        ], $statusCode);
    }
}
