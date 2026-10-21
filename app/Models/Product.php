<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link',
        'image',
        'category',
        'size',
        'price',
        'selling_price',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'selling_price' => 'decimal:2',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'updated_by');
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }
}
