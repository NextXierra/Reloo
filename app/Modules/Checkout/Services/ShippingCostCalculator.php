<?php

namespace App\Modules\Checkout\Services;

use App\Modules\Shipping\Services\RajaOngkirShippingCostService;

class ShippingCostCalculator
{
    /**
     * Supported Couriers and base rate per kg
     */
    protected array $rates = [
        'jne' => [
            'name' => 'JNE Express',
            'service' => 'REG (Reguler)',
            'rate_per_kg' => 15000,
            'etd' => '1-3 Hari',
        ],
        'sicepat' => [
            'name' => 'SiCepat Ekspres',
            'service' => 'REG (Regular)',
            'rate_per_kg' => 14000,
            'etd' => '1-2 Hari',
        ],
        'jnt' => [
            'name' => 'J&T Express',
            'service' => 'EZ (Standard)',
            'rate_per_kg' => 16000,
            'etd' => '1-3 Hari',
        ],
    ];

    public function __construct(
        protected ?RajaOngkirShippingCostService $rajaOngkirService = null
    ) {
        $this->rajaOngkirService = $rajaOngkirService ?? app(RajaOngkirShippingCostService::class);
    }

    /**
     * Calculate cost for a specific courier, optionally resolving live RajaOngkir rate.
     */
    public function calculate(string $courierCode, int $weightGrams, ?int $destinationId = null): array
    {
        $code = strtolower($courierCode);
        $courier = $this->rates[$code] ?? $this->rates['jne'];
        $chargeableWeightKg = max(1, (int) ceil($weightGrams / 1000));

        // If destination ID is provided and courier is not internal flat rate, query RajaOngkir live rates
        if ($destinationId && $code !== 'flat' && $this->rajaOngkirService) {
            $originId = (int) config('services.rajaongkir.origin_id', 17473);
            $liveRates = $this->rajaOngkirService->calculateDomesticCost($originId, $destinationId, $weightGrams, $code);

            if (!empty($liveRates)) {
                // Find regular service or pick first available option
                $selected = null;
                foreach ($liveRates as $rate) {
                    $svc = strtoupper($rate['service'] ?? '');
                    if (in_array($svc, ['REG', 'EZ', 'CTC', 'SIUNT'])) {
                        $selected = $rate;
                        break;
                    }
                }
                $selected = $selected ?? $liveRates[0];

                return [
                    'code' => $code,
                    'name' => $selected['name'] ?? $courier['name'],
                    'service' => $selected['service'] ?? $courier['service'],
                    'etd' => $selected['etd'] ?? $courier['etd'],
                    'weight_kg' => $chargeableWeightKg,
                    'cost' => (float) $selected['cost'],
                    'formatted_cost' => $selected['formatted_cost'] ?? ('Rp ' . number_format($selected['cost'], 0, ',', '.')),
                    'is_live' => true,
                ];
            }
        }

        // Fallback or static base rate calculation
        if (!empty($courier['flat'])) {
            $cost = (float) $courier['rate_per_kg'];
        } else {
            $cost = (float) ($courier['rate_per_kg'] * $chargeableWeightKg);
        }

        return [
            'code' => $code,
            'name' => $courier['name'],
            'service' => $courier['service'],
            'etd' => $courier['etd'],
            'weight_kg' => $chargeableWeightKg,
            'cost' => $cost,
            'formatted_cost' => 'Rp ' . number_format($cost, 0, ',', '.'),
            'is_live' => false,
        ];
    }

    /**
     * Get all available courier options
     */
    public function getAvailableCouriers(int $weightGrams, ?int $destinationId = null): array
    {
        $options = [];

        if ($destinationId && $this->rajaOngkirService) {
            $originId = (int) config('services.rajaongkir.origin_id', 17473);
            $allRates = $this->rajaOngkirService->calculateAllCouriers($originId, $destinationId, $weightGrams, ['jne', 'sicepat', 'jnt']);

            foreach ($allRates as $code => $ratesList) {
                if (!empty($ratesList)) {
                    $selected = null;
                    foreach ($ratesList as $rate) {
                        $svc = strtoupper($rate['service'] ?? '');
                        if (in_array($svc, ['REG', 'EZ', 'CTC', 'SIUNT'])) {
                            $selected = $rate;
                            break;
                        }
                    }
                    $selected = $selected ?? $ratesList[0];

                    $options[$code] = [
                        'code' => $code,
                        'name' => $selected['name'],
                        'service' => $selected['service'],
                        'etd' => $selected['etd'],
                        'weight_kg' => max(1, (int) ceil($weightGrams / 1000)),
                        'cost' => (float) $selected['cost'],
                        'formatted_cost' => $selected['formatted_cost'],
                        'is_live' => $selected['is_live'] ?? true,
                    ];
                }
            }


            return $options;
        }

        foreach (array_keys($this->rates) as $code) {
            $options[$code] = $this->calculate($code, $weightGrams);
        }

        return $options;
    }
}
