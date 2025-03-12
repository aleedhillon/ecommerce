<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Exports\CategoriesExport;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Controllers\BaseCrudController;
use App\Http\Requests\CategoryUpdateRequest;

class CategoryController extends BaseCrudController
{
    public function __construct()
    {
        parent::__construct([
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
