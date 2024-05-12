<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name', 'group_id',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }



    public function subcategories(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
