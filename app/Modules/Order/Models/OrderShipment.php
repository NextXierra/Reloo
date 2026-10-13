<?php

namespace App\Modules\Order\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderShipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'courier_code',
        'courier_service',
        'tracking_number',
        'shipping_status', // pending, in_transit, delivered
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getCourierNameAttribute(): string
    {
        return match (strtolower($this->courier_code)) {
            'jne' => 'JNE Express',
            'sicepat' => 'SiCepat Ekspres',
            'jnt' => 'J&T Express',
            'flat' => 'Kurir Reloo (Flat Rate)',
            default => strtoupper($this->courier_code),
        };
    }
}
