<?php

namespace App\Http\Controllers;

use App\Exports\CategoriesExport;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use App\Traits\CrudTrait;

class CategoryController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->init([
            'resource' => 'categories',
            'modelClass' => Category::class,
            'storeRequestClass' => CategoryStoreRequest::class,
            'updateRequestClass' => CategoryUpdateRequest::class,
            'searchColumns' => ['name'],
            'exportClass' => CategoriesExport::class,
            'componentPath' => 'Categories/Index',
        ]);
    }
}
