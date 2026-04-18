<?php

namespace App\Modules\Permission\Services;

use Spatie\Permission\Models\Permission;

class PermissionService
{
    public function create(array $data): Permission
    {
        return Permission::create([
            'name' => $data['name'],
            'guard_name' => 'web',
        ]);
    }

    public function update(Permission $permission, array $data): Permission
    {
        $permission->update([
            'name' => $data['name'],
        ]);

        return $permission;
    }

    public function delete(Permission $permission): bool
    {
        return $permission->delete();
    }
}