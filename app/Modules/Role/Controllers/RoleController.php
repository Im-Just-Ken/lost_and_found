<?php

namespace App\Modules\Role\Controllers;

use App\Http\Resources\RoleResource;
use App\Http\Controllers\Controller;
use App\Models\Internal\Role;
use App\Models\Internal\PermissionGroup;
use App\Modules\Role\Services\RoleService;
use App\Modules\Role\Requests\StoreRoleRequest;
use App\Modules\Role\Requests\UpdateRoleRequest;
use App\Modules\Role\DTO\RoleData;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $service
    ) {}

 public function index()
{
    return inertia('Roles/Index', [
        'roles' => RoleResource::collection(
            Role::with('roleGroup')->latest()->get()
        )->resolve(),

        'groups' => PermissionGroup::orderBy('name')->get(),
    ]);
}


    public function store(StoreRoleRequest $request)
    {
        $dto = RoleData::fromArray($request->validated());

        $this->service->create($dto);

        return back();
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $dto = RoleData::fromArray($request->validated());

        $this->service->update($role, $dto);

        return back();
    }

    public function destroy(Role $role)
    {
        $this->service->delete($role);

        return back();
    }
}