<?php

namespace App\Modules\Checkout\Services;

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
        'flat' => [
            'name' => 'Kurir Reloo (Flat Rate)',
            'service' => 'Standard Delivery',
            'rate_per_kg' => 10000,
            'flat' => true,
            'etd' => '2-4 Hari',
        ],
    ];

    /**
     * Calculate cost for a specific courier
     */
    public function calculate(string $courierCode, int $weightGrams): array
    {
        $code = strtolower($courierCode);
        $courier = $this->rates[$code] ?? $this->rates['jne'];

        $chargeableWeightKg = max(1, (int) ceil($weightGrams / 1000));

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
        ];
    }

    /**
     * Get all available courier options
     */
    public function getAvailableCouriers(int $weightGrams): array
    {
        $options = [];
        foreach (array_keys($this->rates) as $code) {
            $options[$code] = $this->calculate($code, $weightGrams);
        }
        return $options;
    }
}
