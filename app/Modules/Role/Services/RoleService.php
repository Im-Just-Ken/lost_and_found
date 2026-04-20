<?php

namespace App\Modules\Role\Services;

use App\Models\Internal\Role;
use App\Modules\Role\DTO\RoleData;
use App\Enums\RoleStatus;

class RoleService
{
public function create(RoleData $data): Role
{
    return Role::create([
        'name' => $data->name,
        'permission_group_id' => $data->permission_group_id,
        'guard_name' => 'web',
        'status' => RoleStatus::ACTIVE,
    ]);
}

public function update(Role $role, RoleData $data): Role
{
    $role->update([
        'name' => $data->name,
        'permission_group_id' => $data->permission_group_id,
        'status' => $data->status,
    ]);

    return $role;
}
    public function delete(Role $role): bool
    {
        return $role->delete();
    }
}