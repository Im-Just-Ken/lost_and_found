<?php

namespace App\Models\Internal;

use Spatie\Permission\Models\Role as SpatieRole;
use App\Enums\RoleStatus;
use App\Models\Internal\Group;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'status',
        'group_id',
    ];

    protected $casts = [
        'status' => RoleStatus::class,
    ];

    public function roleGroup()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}