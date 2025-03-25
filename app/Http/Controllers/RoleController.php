<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Role;
use App\Traits\CrudTrait;
use App\Utils\CrudConfig;

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
        ));
    }
}
