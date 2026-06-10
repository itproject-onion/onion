<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectedOutfit extends Model
{
    protected $fillable = [
        'user_id',
        'item_id',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
