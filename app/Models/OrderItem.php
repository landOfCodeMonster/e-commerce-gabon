<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'source_url',
        'source_site',
        'scraped_title',
        'scraped_price',
        'scraped_currency',
        'scraped_image',
        'quantity',
        'color',
        'size',
        'converted_price',
    ];

    protected function casts(): array
    {
        return [
            'scraped_price' => 'decimal:2',
            'converted_price' => 'decimal:2',
            'quantity' => 'integer',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getLineTotalAttribute(): float
    {
        $price = $this->converted_price ?? $this->scraped_price ?? 0;
        return $price * $this->quantity;
    }
}
