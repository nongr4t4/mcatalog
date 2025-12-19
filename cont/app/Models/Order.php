<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'total_amount',
        'shipping_address',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    // Связи
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Аксесори
    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_amount, 2, '.', ' ') . ' ₴';
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'Очікує',
            'processing' => 'У обробці',
            'completed' => 'Завершено',
            'cancelled' => 'Скасовано',
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }

    // Події моделі
    protected static function boot()
    {
        parent::boot();

        // Генерація номера замовлення при створенні
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(uniqid());
            }
        });
    }

    // Методы для работы со статусами
    public function markAsProcessing()
    {
        $this->update(['status' => 'processing']);
    }

    public function markAsCompleted()
    {
        $this->update(['status' => 'completed']);
    }

    public function markAsCancelled()
    {
        $this->update(['status' => 'cancelled']);
    }
}
