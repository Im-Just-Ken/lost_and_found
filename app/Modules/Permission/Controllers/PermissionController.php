<?php

namespace App\Modules\Permission\Controllers;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Modules\Permission\Services\PermissionService;
use App\Modules\Permission\Requests\StorePermissionRequest;
use App\Modules\Permission\Requests\UpdatePermissionRequest;

class PermissionController extends Controller
{
    public function __construct(
        protected PermissionService $service
    ) {}

    public function index()
    {
        return inertia('Permissions/Index', [
            'permissions' => Permission::all(),
        ]);
    }

    public function store(StorePermissionRequest $request)
    {
        $this->service->create($request->validated());

        return back();
    }

    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $this->service->update($permission, $request->validated());

        return back();
    }

    public function destroy(Permission $permission)
    {
        $this->service->delete($permission);

        return back();
    }
}