<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'making_price',
        'asking_price',
        'selling_price',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'making_price' => 'decimal:2',
            'asking_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getSubtotalAttribute(): float
    {
        return (float) ($this->quantity * $this->selling_price);
    }
}
