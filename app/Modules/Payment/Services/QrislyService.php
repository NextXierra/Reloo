<?php

namespace App\Modules\Payment\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class QrislyService
{
    protected string $apiKey;
    protected int $qrisId;
    protected bool $isSandbox;
    protected string $merchantName;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('QRISLY_API_KEY', '9vBjwpmb248f87abf1ac249e1DPKzgY5');
        $this->qrisId = (int) env('QRISLY_QRIS_ID', 1277);
        $this->isSandbox = env('QRISLY_ENV', 'sandbox') === 'sandbox';
        $this->merchantName = env('QRISLY_MERCHANT_NAME', 'Reloo Marketplace');
        
        $this->baseUrl = $this->isSandbox
            ? 'https://api-sandbox.collaborator.komerce.id/user'
            : 'https://api.collaborator.komerce.id/user';
    }

    /**
     * Generate Dynamic QRIS via RajaOngkir Qrisly API or EMVCo compliant generator fallback
     */
    public function generateQris(float $amount, string $orderNumber): array
    {
        $endpoint = $this->baseUrl . '/api/v1/qrisly/generate-qris';

        $payload = [
            'qris_id' => $this->qrisId,
            'amount' => (int) round($amount),
            'output_type' => 'string',
            'unique_amount' => true,
        ];

        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(6)->post($endpoint, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $qrisString = $data['data']['qris_string'] ?? $data['data']['qris_data'] ?? $data['data']['qr_string'] ?? null;
                
                if (!empty($qrisString)) {
                    $finalAmount = (float) ($data['data']['final_amount'] ?? $data['data']['amount'] ?? $amount);
                    $historyId = (string) ($data['data']['history_id'] ?? ('qrisly-' . $orderNumber));

                    return [
                        'history_id' => $historyId,
                        'amount' => $finalAmount,
                        'qris_string' => $qrisString,
                        'qr_image_url' => $this->getQrImageUrl($qrisString),
                        'is_dummy' => false,
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Fallback to EMVCo dynamic generator
        }

        // Standard EMVCo QRIS dynamic generation for sandbox testing
        return $this->generateDummyQris($amount, $orderNumber, $this->merchantName);
    }

    /**
     * Check payment status from RajaOngkir Qrisly API
     */
    public function checkStatus(string $historyId): array
    {
        $endpoint = $this->baseUrl . '/api/v1/qrisly/payment-status/' . urlencode($historyId);

        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(5)->get($endpoint);

            if ($response->successful()) {
                return $response->json() ?? [];
            }
        } catch (\Throwable $e) {
            // Connection fallback
        }

        return [];
    }

    /**
     * Generate dynamic QRIS string compliant with EMVCo Indonesian QRIS Standard
     */
    public function generateDummyQris(float $amount, string $orderNumber, string $merchantName = 'Reloo Marketplace'): array
    {
        // Compute small unique amount offset to prevent duplicate collision
        $numericId = (int) preg_replace('/\D/', '', $orderNumber);
        $uniqueAmount = (int) round($amount) + ($numericId % 999);
        
        $cleanMerchant = strtoupper(substr(preg_replace('/[^A-Za-z0-9 ]/', '', $merchantName), 0, 25));
        if (empty($cleanMerchant)) {
            $cleanMerchant = 'RELOO MARKETPLACE';
        }

        // Tag 26: Merchant Account Information
        $subTag00 = self::tlv('00', 'ID.CO.QRIS.WWW');
        $subTag01 = self::tlv('01', 'ID1020030040050'); // NMID
        $subTag02 = self::tlv('02', '000000000001');
        $subTag03 = self::tlv('03', 'UMI');
        $tag26Value = $subTag00 . $subTag01 . $subTag02 . $subTag03;

        // Tag 62: Additional Reference Data
        $tag62Value = self::tlv('01', 'ORD-' . Str::slug($orderNumber));

        $raw = '';
        $raw .= self::tlv('00', '01'); // Format Indicator
        $raw .= self::tlv('01', '12'); // Point of Initiation: Dynamic QR
        $raw .= self::tlv('26', $tag26Value);
        $raw .= self::tlv('51', self::tlv('02', 'ID1020030040050'));
        $raw .= self::tlv('52', '5311'); // MCC
        $raw .= self::tlv('53', '360'); // Currency: IDR (360)
        $raw .= self::tlv('54', (string) $uniqueAmount); // Amount
        $raw .= self::tlv('58', 'ID'); // Country Code
        $raw .= self::tlv('59', $cleanMerchant); // Merchant Name
        $raw .= self::tlv('60', 'JAKARTA'); // City
        $raw .= self::tlv('61', '12810'); // Postal Code
        $raw .= self::tlv('62', $tag62Value);
        $raw .= '6304'; // Checksum Tag

        $crc = self::crc16($raw);
        $finalQrisString = $raw . $crc;

        $historyId = 'qrisly-' . Str::slug($orderNumber) . '-' . time();

        return [
            'history_id' => $historyId,
            'amount' => $uniqueAmount,
            'qris_string' => $finalQrisString,
            'qr_image_url' => $this->getQrImageUrl($finalQrisString),
            'is_dummy' => true,
        ];
    }

    /**
     * Get high-resolution QR image URL for scanning
     */
    public function getQrImageUrl(string $qrisString): string
    {
        return 'https://quickchart.io/qr?text=' . urlencode($qrisString) . '&size=320&ecLevel=M&margin=2';
    }

    /**
     * Compute Tag-Length-Value (TLV) segment
     */
    protected static function tlv(string $tag, string $value): string
    {
        return sprintf('%02d%02d%s', (int) $tag, strlen($value), $value);
    }

    /**
     * Calculate CRC16-CCITT Checksum (Polynomial 0x1021, Initial 0xFFFF)
     */
    protected static function crc16(string $str): string
    {
        $crc = 0xFFFF;
        $len = strlen($str);

        for ($c = 0; $c < $len; $c++) {
            $crc ^= (ord($str[$c]) << 8);
            for ($i = 0; $i < 8; $i++) {
                if ($crc & 0x8000) {
                    $crc = (($crc << 1) ^ 0x1021) & 0xFFFF;
                } else {
                    $crc = ($crc << 1) & 0xFFFF;
                }
            }
        }

        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }
}
