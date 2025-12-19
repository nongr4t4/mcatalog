<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    // Связи
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Аксессоры
    public function getSubtotalAttribute()
    {
        return $this->product->price * $this->quantity;
    }

    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->subtotal, 2, '.', ' ') . ' ₴';
    }

    // Валідація кількості
    public function setQuantityAttribute($value)
    {
        $maxQuantity = $this->product->stock ?? 0;
        $this->attributes['quantity'] = min($value, $maxQuantity);
    }
}
