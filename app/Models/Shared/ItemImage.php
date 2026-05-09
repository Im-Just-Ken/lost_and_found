<?php

namespace App\Models\Shared;
use Illuminate\Database\Eloquent\Model;



class ItemImage extends Model
{
    public $timestamps = false;

    protected $table = 'item_images';

    protected $fillable = [
        'item_id',
        'path',
        'is_primary',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function vector()
    {
        return $this->hasOne(ItemImageVector::class);
    }
}