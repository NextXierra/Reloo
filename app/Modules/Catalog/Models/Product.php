<?php

namespace App\Modules\Catalog\Models;

use App\Modules\Cart\Models\CartItem;
use App\Modules\Order\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'condition', // 'new', 'like_new', 'good', 'fair'
        'price',
        'original_price',
        'weight_grams',
        'stock',
        'description',
        'is_active',
        'vendor_id',
    ];

    protected $casts = [
        'price' => 'float',
        'original_price' => 'float',
        'weight_grams' => 'integer',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name) . '-' . Str::lower(Str::random(5));
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('order_index');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->ofMany([
            'is_primary' => 'max',
            'order_index' => 'min',
            'id' => 'min',
        ]);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getConditionBadgeAttribute(): array
    {
        return match ($this->condition) {
            'new' => ['label' => 'Baru', 'color' => 'bg-[#EDF3EC] text-[#26542A] border-[#D1E2CF]'],
            'like_new' => ['label' => 'Seperti Baru', 'color' => 'bg-[#E1F3FE] text-[#17547D] border-[#C2E4FC]'],
            'good' => ['label' => 'Bagus', 'color' => 'bg-[#FBF3DB] text-[#785100] border-[#F5E5B5]'],
            'fair' => ['label' => 'Cukup', 'color' => 'bg-[#F4F4F5] text-[#3F3F46] border-[#E4E4E7]'],
            default => ['label' => ucfirst($this->condition), 'color' => 'bg-[#F4F4F5] text-[#3F3F46] border-[#E4E4E7]'],
        };
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedOriginalPriceAttribute(): ?string
    {
        return $this->original_price ? 'Rp ' . number_format($this->original_price, 0, ',', '.') : null;
    }
}
