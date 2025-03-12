<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use App\Traits\ApiCrudTrait;

class CategoryController extends Controller
{
    use ApiCrudTrait;

    public function __construct()
    {
        $this->resource = 'categories';
        $this->modelClass = Category::class;
        $this->storeRequestClass = CategoryStoreRequest::class;
        $this->updateRequestClass = CategoryUpdateRequest::class;

        $this->searchColumns = ['name'];
    }
}
