<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Traits\HasCrud;
use App\Utils\CrudConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;

class UserController extends Controller
{
    use HasCrud;

    public function __construct()
    {
        $this->init(new CrudConfig(
            resource: 'users',
            modelClass: User::class,
            storeRequestClass: UserStoreRequest::class,
            updateRequestClass: UserUpdateRequest::class,
            componentPath: 'Admin/Users/Index',
            searchColumns: ['name', 'email'],

        ));
    }
}
