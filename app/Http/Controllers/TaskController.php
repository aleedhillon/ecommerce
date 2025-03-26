<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreRequest;
// use App\Exports\TaskExport;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Task;
use App\Traits\CrudTrait;
use App\Utils\CrudConfig;

class TaskController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->init(new CrudConfig(
            resource: 'tasks',
            modelClass: Task::class,
            storeRequestClass: TaskStoreRequest::class,
            updateRequestClass: TaskUpdateRequest::class,
            componentPath: 'Tasks/Index',
            searchColumns: [],
            // exportClass: TaskExport::class,
            withRelations: [],
        ));
    }
}
