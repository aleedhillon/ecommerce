<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Traits\CrudTrait;
use App\Utils\CrudConfig;
use App\Models\Permission;
use Illuminate\Support\Str;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use Illuminate\Support\Arr;

class RoleController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->init(new CrudConfig(
            resource: 'roles',
            modelClass: Role::class,
            storeRequestClass: RoleStoreRequest::class,
            updateRequestClass: RoleUpdateRequest::class,
            componentPath: 'Roles/Index',
            searchColumns: ['name'],
            addProps: $this->addProps(),
            withRelations: ['permissions:id,name']
        ));
    }

    protected function addProps(): array
    {
        return [
            'permissions' => Permission::select('id', 'name')->get(),
        ];
    }

    public function store(RoleStoreRequest $request)
    {
        $data = $request->validated();
        $role = Role::create(['name' => $data['name']]);
        if (isset($role)) {
            $permissions = Permission::whereKey($data['ids'])->get();
            $role->syncPermissions($permissions);
        }
    }

    public function update(RoleUpdateRequest $request, Role $role)
    {
        $data = $request->validated();
        $role->update(['name' => $data['name']]);
        if (isset($role)) {
            $permissions = Permission::whereKey($data['ids'])->get();
            $role->syncPermissions($permissions);
        }
    }

    public function destroy(Role $role)
    {
        if (isset($role)) {
            $superAdminId = 1;
            $isNotSuperAdmin = (Role::count() > 1) && $role->id != $superAdminId;
            if ($isNotSuperAdmin) {
                $role->delete();
                return back();
            }
        }
        return \redirect()->back()->withErrors('Super Admin Can\'t be Deleted');
    }
}
