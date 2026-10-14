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
        'delivery_order_no',
        'destination_id',
        'origin_id',
        'pickup_status',
        'pickup_time',
        'pickup_vehicle',
        'label_path',
        'live_tracking_url',
        'shipping_cashback',
        'delivery_payload',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'pickup_time' => 'datetime',
        'delivery_payload' => 'array',
        'shipping_cashback' => 'decimal:2',
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
