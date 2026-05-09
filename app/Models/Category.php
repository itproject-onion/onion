<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'is_impacted_by_rain',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
