<?php

namespace App\Modules\Order\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_gateway', // midtrans, manual_transfer
        'payment_type',
        'transaction_id',
        'payment_status', // pending, waiting_verification, paid, expired, failed
        'payment_proof',
        'snap_token',
        'paid_at',
        'gateway_payload',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getPaymentStatusBadgeAttribute(): array
    {
        return match ($this->payment_status) {
            'pending' => ['label' => 'Belum Dibayar', 'color' => 'bg-amber-100 text-amber-800 border-amber-300'],
            'waiting_verification' => ['label' => 'Menunggu Verifikasi Admin', 'color' => 'bg-blue-100 text-blue-800 border-blue-300'],
            'paid' => ['label' => 'Lunas', 'color' => 'bg-emerald-100 text-emerald-800 border-emerald-300'],
            'expired' => ['label' => 'Kedaluwarsa', 'color' => 'bg-rose-100 text-rose-800 border-rose-300'],
            'failed' => ['label' => 'Gagal', 'color' => 'bg-rose-100 text-rose-800 border-rose-300'],
            default => ['label' => ucfirst($this->payment_status), 'color' => 'bg-gray-100 text-gray-800 border-gray-300'],
        };
    }

    public function getPaymentProofUrlAttribute(): ?string
    {
        if (!$this->payment_proof) {
            return null;
        }

        if (str_starts_with($this->payment_proof, 'http://') || str_starts_with($this->payment_proof, 'https://')) {
            return $this->payment_proof;
        }

        return asset('storage/' . ltrim($this->payment_proof, '/'));
    }
}
