<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    protected $fillable = [
        'city', 'address',
    ];

    public function productQuantities(): HasMany
    {
        return $this->hasMany(ProductQuantity::class);
    }
}
