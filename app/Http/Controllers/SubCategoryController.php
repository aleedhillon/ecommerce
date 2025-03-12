<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use App\Exports\SubCategoriesExport;
use App\Http\Controllers\BaseCrudController;
use App\Http\Requests\SubCategoryStoreRequest;
use App\Http\Requests\SubCategoryUpdateRequest;

class SubCategoryController extends BaseCrudController
{
    public function __construct()
    {
        parent::__construct([
            'resource' => 'sub-categories',
            'modelClass' => SubCategory::class,
            'storeRequestClass' => SubCategoryStoreRequest::class,
            'updateRequestClass' => SubCategoryUpdateRequest::class,
            'searchColumns' => ['name'],
            'exportClass' => SubCategoriesExport::class,
            'componentPath' => 'SubCategories/Index',
            'withRelations' => ['category'],
            'addProps' => $this->addProps(),
        ]);
    }

    protected function addProps(): array
    {
        return [
            'categories' => Category::select('id', 'name')->get(),
        ];
    }
}
