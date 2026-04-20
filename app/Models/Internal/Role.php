<?php

namespace App\Models\Internal;

use Spatie\Permission\Models\Role as SpatieRole;
use App\Enums\RoleStatus;
use App\Models\Internal\PermissionGroup;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'status',
        'permission_group_id',
    ];

    protected $casts = [
        'status' => RoleStatus::class,
    ];

    public function roleGroup()
    {
        return $this->belongsTo(PermissionGroup::class);
    }
}