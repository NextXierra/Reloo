<?php

namespace App\Modules\Payment\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class QrislyService
{
    protected string $apiKey;
    protected int $qrisId;
    protected bool $isSandbox;
    protected bool $verifySsl;
    protected string $merchantName;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = (string) (config('services.qrisly.api_key') ?? env('QRISLY_API_KEY', '9vBjwpmb248f87abf1ac249e1DPKzgY5'));
        $this->qrisId = (int) (config('services.qrisly.qris_id') ?? env('QRISLY_QRIS_ID', 1277));
        $this->isSandbox = (config('services.qrisly.env') ?? env('QRISLY_ENV', 'sandbox')) === 'sandbox';
        $this->verifySsl = filter_var(config('services.qrisly.verify_ssl') ?? env('QRISLY_VERIFY_SSL', false), FILTER_VALIDATE_BOOLEAN);
        $this->merchantName = (string) (config('services.qrisly.merchant_name') ?? env('QRISLY_MERCHANT_NAME', 'Reloo Marketplace'));

        $this->baseUrl = $this->isSandbox
            ? 'https://api-sandbox.collaborator.komerce.id/user'
            : 'https://api.collaborator.komerce.id/user';
    }

    /**
     * Build HTTP client configured with required headers and SSL verification settings.
     */
    protected function client(): PendingRequest
    {
        $client = Http::withHeaders([
            'X-API-Key' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->timeout(12);

        if (!$this->verifySsl) {
            $client = $client->withoutVerifying();
        }

        return $client;
    }

    /**
     * Generate Dynamic QRIS via RajaOngkir Qrisly Sandbox/Production API.
     * Absolute server reference (history_id) is generated and returned by Qrisly server.
     *
     * @throws \RuntimeException
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
            $response = $this->client()->post($endpoint, $payload);
        } catch (\Throwable $e) {
            throw new \RuntimeException(
                "Koneksi ke server Qrisly gagal: " . $e->getMessage(),
                0,
                $e
            );
        }

        if (!$response->successful()) {
            $errorMessage = $response->json('meta.message')
                ?? $response->json('message')
                ?? $response->body();
            throw new \RuntimeException(
                "Gagal generate QRIS dari Qrisly API [HTTP {$response->status()}]: {$errorMessage}"
            );
        }

        $resData = $response->json();
        $data = $resData['data'] ?? [];

        $historyId = $data['history_id'] ?? null;
        $qrisString = $data['qris_string'] ?? $data['qris_data'] ?? $data['qr_string'] ?? null;
        $finalAmount = (float) ($data['final_amount'] ?? $data['amount'] ?? $amount);
        $expiryTime = $data['expiry_time'] ?? null;

        if (!$historyId || empty($qrisString)) {
            throw new \RuntimeException(
                "Respon Qrisly tidak valid: 'history_id' atau 'qris_string' tidak ditemukan dalam payload respon."
            );
        }

        return [
            'history_id' => $historyId,
            'amount' => $finalAmount,
            'qris_string' => $qrisString,
            'qr_image_url' => $this->getQrImageUrl($qrisString),
            'expiry_time' => $expiryTime,
            'is_dummy' => false,
            'raw' => $data,
        ];
    }

    /**
     * Check payment status from RajaOngkir Qrisly API.
     */
    public function checkStatus(string|int $historyId): array
    {
        $endpoint = $this->baseUrl . '/api/v1/qrisly/payment-status/' . urlencode((string) $historyId);

        try {
            $response = $this->client()->get($endpoint);

            if ($response->successful()) {
                return $response->json() ?? [];
            }
        } catch (\Throwable $e) {
            // Connection/timeout error during polling query handled gracefully
        }

        return [];
    }

    /**
     * Get high-resolution QR image URL for scanning.
     */
    public function getQrImageUrl(string $qrisString): string
    {
        return 'https://quickchart.io/qr?text=' . urlencode($qrisString) . '&size=320&ecLevel=M&margin=2';
    }
}
