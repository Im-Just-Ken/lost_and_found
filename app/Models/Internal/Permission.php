<?php

namespace App\Models\Internal;

use Spatie\Permission\Models\Permission as SpatiePermission;

use App\Enums\PermissionStatus;
use App\Models\Internal\Group;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'guard_name',
        'status',
        'group_id',
    ];

     protected $casts = [
        'status' => PermissionStatus::class, 
    ];

    public function Group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}