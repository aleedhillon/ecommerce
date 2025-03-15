<?php

namespace App\Http\Controllers;

use App\Exports\CategoryExport;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use App\Traits\CrudTrait;
use App\Utils\CrudConfig;

class CategoryController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->init(new CrudConfig(
            resource: 'categories',
            modelClass: Category::class,
            storeRequestClass: CategoryStoreRequest::class,
            updateRequestClass: CategoryUpdateRequest::class,
            searchColumns: ['name'],
            exportClass: CategoryExport::class,
            componentPath: 'Categories/Index',
            withRelations: [],
        ));
    }
}
