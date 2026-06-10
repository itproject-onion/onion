<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'filepath',
        'is_waterproof',
        'min_temperature',
        'max_temperature',
        'min_uv_index',
        'max_uv_index',
        'cloud_cover_threshold',

        'category_id',
        'user_id',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function selectedOutfits()
    {
        return $this->hasMany(SelectedOutfit::class);
    }
}
