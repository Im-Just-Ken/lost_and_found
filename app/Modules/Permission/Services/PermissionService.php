<?php

namespace App\Modules\Permission\Services;

use App\Models\Internal\Permission;
use App\Modules\Permission\DTO\PermissionData;
use App\Enums\PermissionStatus;

class PermissionService
{
public function create(PermissionData $data): Permission
{
    return Permission::create([
        'name' => $data->name,
        'group_id' => $data->group_id,
        'guard_name' => 'web',
        'status' => PermissionStatus::ACTIVE,
    ]);
}

public function update(Permission $permission, PermissionData $data): Permission
{
    $permission->update([
        'name' => $data->name,
        'group_id' => $data->group_id,
        'status' => $data->status,
    ]);

    return $permission;
}
    public function delete(Permission $permission): bool
    {
        return $permission->delete();
    }
}