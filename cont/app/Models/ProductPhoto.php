<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'path',
        'is_main',
        'order',
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    // Связи
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Аксессор для URL изображения
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }

    // Мутатор для установки пути
    public function setPathAttribute($value)
    {
        $this->attributes['path'] = str_replace('public/', '', $value);
    }

    // События модели
    protected static function boot()
    {
        parent::boot();

        // При сохранении новой главной фотографии снимаем флаг с других
        static::saving(function ($photo) {
            if ($photo->is_main) {
                ProductPhoto::where('product_id', $photo->product_id)
                    ->where('id', '!=', $photo->id)
                    ->update(['is_main' => false]);
            }
        });
    }
}
