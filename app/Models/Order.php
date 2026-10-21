<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no',
        'customer_id',
        'order_date',
        'exp_delv_date',
        'address',
        'thana_id',
        'status',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'date',
            'exp_delv_date' => 'date',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function thana(): BelongsTo
    {
        return $this->belongsTo(Thana::class, 'thana_id');
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'updated_by');
    }

    public function getTotalAmountAttribute(): float
    {
        return (float) $this->orderDetails->sum(function ($item) {
            return $item->quantity * $item->selling_price;
        });
    }

    public function getTotalItemsAttribute(): int
    {
        return (int) $this->orderDetails->sum('quantity');
    }
}
