<?php

namespace App\Http\Controllers;

use App\Traits\CrudTrait;
use App\Models\SubCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubCategoryStoreRequest;
use App\Http\Requests\SubCategoryUpdateRequest;

class SubCategoryController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->modelClass = SubCategory::class;
        $this->componentPath = 'SubCategories/Index';
        $this->storeRequestClass = SubCategoryStoreRequest::class;
        $this->updateRequestClass = SubCategoryUpdateRequest::class;
        $this->resource = 'SubCategory';
    }
}
