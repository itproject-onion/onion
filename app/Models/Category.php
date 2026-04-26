<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'is_impacted_by_rain',
        'min_temperature',
        'max_temperature',
        'min_uv_index',
        'max_uv_index',
        'cloud_cover_threshold',
    ];

    // Relationships
    public function users()
    {
        // can be used as $category->users()->first()->offsets->min_temperature_offset
        return $this->belongsToMany(User::class)
            ->as('offsets')
            ->withPivot('min_temperature_offset', 'max_temperature_offset');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
