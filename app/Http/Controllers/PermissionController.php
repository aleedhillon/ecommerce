<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionStoreRequest;
use App\Http\Requests\PermissionUpdateRequest;
use App\Models\Permission;
use App\Traits\CrudTrait;
use App\Utils\CrudConfig;

class PermissionController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->init(new CrudConfig(
            resource: 'permissions',
            modelClass: Permission::class,
            storeRequestClass: PermissionStoreRequest::class,
            updateRequestClass: PermissionUpdateRequest::class,
            componentPath: 'Permissions/Index',
            searchColumns: ['name'],
        ));
    }
}
