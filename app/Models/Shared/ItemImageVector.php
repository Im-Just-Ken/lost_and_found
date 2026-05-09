<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Model;


class ItemImageVector extends Model
{
    public $timestamps = false;

    protected $table = 'item_image_vectors';

    protected $fillable = [
        'item_id',
        'item_image_id',
        'vector_id',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function image()
    {
        return $this->belongsTo(ItemImage::class, 'item_image_id');
    }
}