<?php

namespace App\Http\Controllers;

use App\Exports\TodoExport;
use App\Http\Requests\TodoStoreRequest;
use App\Http\Requests\TodoUpdateRequest;
use App\Models\Todo;
use App\Traits\CrudTrait;
use App\Utils\CrudConfig;

class TodoController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->init(new CrudConfig(
            resource: 'todos',
            modelClass: Todo::class,
            storeRequestClass: TodoStoreRequest::class,
            updateRequestClass: TodoUpdateRequest::class,
            componentPath: 'Todos/Index',
            searchColumns: ['title'],
            exportClass: TodoExport::class,
            withRelations: [],
        ));
    }
}
