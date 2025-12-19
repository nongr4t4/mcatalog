<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_price',
        'quantity',
        'subtotal',
    ];

    protected $casts = [
        'product_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Связи
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Аксесори
    public function getFormattedProductPriceAttribute()
    {
        return number_format($this->product_price, 2, '.', ' ') . ' ₴';
    }

    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->subtotal, 2, '.', ' ') . ' ₴';
    }

    // Події моделі
    protected static function boot()
    {
        parent::boot();

        // Автоматичне обчислення subtotal при збереженні
        static::saving(function ($item) {
            if (empty($item->subtotal)) {
                $item->subtotal = $item->product_price * $item->quantity;
            }
        });
    }
}
