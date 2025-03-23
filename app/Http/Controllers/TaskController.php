<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
// use App\Exports\TaskExport;
use App\Utils\CrudConfig;
use App\Traits\CrudTrait;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;

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
