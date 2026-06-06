<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ItemHistoryActionType;
use App\Models\User;

class ItemHistory extends Model
{
    public $timestamps = true;

    protected $table = 'item_histories';

    protected $fillable = [
        'item_id',
        'user_id',
        'action_type',
        'notes',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'action_type' => ItemHistoryActionType::class,
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}