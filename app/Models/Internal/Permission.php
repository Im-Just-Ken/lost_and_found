<?php

namespace App\Models\Internal;

use Spatie\Permission\Models\Permission as SpatiePermission;

use App\Enums\PermissionStatus;
use App\Models\Internal\PermissionGroup;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'guard_name',
        'status',
        'permission_group_id',
    ];

     protected $casts = [
        'status' => PermissionStatus::class, 
    ];

    public function permissionGroup()
    {
        return $this->belongsTo(PermissionGroup::class);
    }
}