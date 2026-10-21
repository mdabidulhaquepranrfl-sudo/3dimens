<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Thana extends Model
{
    use HasFactory;

    protected $table = 'thana';

    protected $fillable = [
        'name',
        'division_id',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'thana_id');
    }
}
