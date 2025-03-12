<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubCategoryStoreRequest;
use App\Http\Requests\SubCategoryUpdateRequest;
use App\Models\SubCategory;
use App\Traits\CrudTrait;

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
