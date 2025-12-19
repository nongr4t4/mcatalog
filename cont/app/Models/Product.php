<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'is_archived',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_archived' => 'boolean',
    ];

    // Связи
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product')
            ->withTimestamps();
    }

    public function photos()
    {
        return $this->hasMany(ProductPhoto::class)->orderBy('order');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    // Аксессоры
    public function getMainPhotoAttribute()
    {
        return $this->photos->where('is_main', true)->first() 
            ?? $this->photos->first();
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2, '.', ' ') . ' ₴';
    }

    public function getInStockAttribute()
    {
        return $this->stock > 0;
    }

    public function getAverageRatingAttribute(): float
    {
        $avg = $this->reviews_avg_stars
            ?? ($this->relationLoaded('reviews') ? $this->reviews->avg('stars') : $this->reviews()->avg('stars'));

        return $avg ? round((float) $avg, 1) : 0.0;
    }

    public function getRatingsCountAttribute(): int
    {
        if ($this->reviews_count !== null) {
            return (int) $this->reviews_count;
        }

        if ($this->relationLoaded('reviews')) {
            return $this->reviews->count();
        }

        return $this->reviews()->count();
    }

    // Скоупы
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }
}
