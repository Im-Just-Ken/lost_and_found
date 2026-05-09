<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ItemMatchStatus;

class ItemMatch extends Model
{
    protected $table = 'item_matches';

    protected $fillable = [
        'lost_item_id',
        'found_item_id',
        'confidence_score',
        'status',
    ];

            protected $casts = [
        'status' => ItemMatchStatus::class,
    ];


    public function lostItem()
    {
        return $this->belongsTo(Item::class, 'lost_item_id');
    }

    public function foundItem()
    {
        return $this->belongsTo(Item::class, 'found_item_id');
    }
}