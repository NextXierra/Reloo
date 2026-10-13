<?php

namespace App\Modules\Order\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'status', // pending_payment, paid, processing, shipped, completed, cancelled
        'items_total',
        'shipping_cost',
        'grand_total',
        'customer_note',
        'shipping_address',
        'vendor_id',
    ];

    protected $casts = [
        'items_total' => 'float',
        'shipping_cost' => 'float',
        'grand_total' => 'float',
        'shipping_address' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shipment(): HasOne
    {
        return $this->hasOne(OrderShipment::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(OrderPayment::class);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'pending_payment' => ['label' => 'Menunggu Pembayaran', 'color' => 'bg-amber-100 text-amber-800 border-amber-300'],
            'paid' => ['label' => 'Sudah Dibayar', 'color' => 'bg-blue-100 text-blue-800 border-blue-300'],
            'processing' => ['label' => 'Diproses Toko', 'color' => 'bg-indigo-100 text-indigo-800 border-indigo-300'],
            'shipped' => ['label' => 'Dalam Pengiriman', 'color' => 'bg-purple-100 text-purple-800 border-purple-300'],
            'completed' => ['label' => 'Selesai', 'color' => 'bg-emerald-100 text-emerald-800 border-emerald-300'],
            'cancelled' => ['label' => 'Dibatalkan', 'color' => 'bg-rose-100 text-rose-800 border-rose-300'],
            default => ['label' => ucfirst($this->status), 'color' => 'bg-gray-100 text-gray-800 border-gray-300'],
        };
    }

    public function getFormattedGrandTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->grand_total, 0, ',', '.');
    }
}
