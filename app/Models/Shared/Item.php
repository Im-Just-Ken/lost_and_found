<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\ItemStatus;
use App\Models\Shared\ItemImage;
use App\Models\Shared\ItemImageVector;
use App\Models\Shared\ItemHistory;
use App\Models\Shared\ItemReport;
use App\Models\Shared\ItemMatch;

class Item extends Model
{
    use SoftDeletes;

    protected $table = 'items';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'description',
        'contact_number',
        'status',
        'date_lost',
        'location_text',
        'resolved_at',
    ];

    protected $casts = [
        'date_lost' => 'date',
        'resolved_at' => 'datetime',
        'status' => ItemStatus::class,
    ];


    public function images()
    {
        return $this->hasMany(ItemImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ItemImage::class)->where('is_primary', true);
    }

    public function vectors()
    {
        return $this->hasMany(ItemImageVector::class);
    }

    public function histories()
    {
        return $this->hasMany(ItemHistory::class);
    }

    public function reports()
    {
        return $this->hasMany(ItemReport::class);
    }

    public function lostMatches()
    {
        return $this->hasMany(ItemMatch::class, 'lost_item_id');
    }

    public function foundMatches()
    {
        return $this->hasMany(ItemMatch::class, 'found_item_id');
    }
}