<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ItemReportStatus;

class ItemReport extends Model
{
    public $timestamps = false;

    protected $table = 'item_reports';

    protected $fillable = [
        'item_id',
        'reported_by',
        'reason',
        'notes',
        'status',
    ];

    protected $casts = [
        'status' => ItemReportStatus::class,
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}