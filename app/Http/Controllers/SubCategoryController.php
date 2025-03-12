<?php

namespace App\Http\Controllers;

use App\Exports\SubCategoriesExport;
use App\Http\Requests\SubCategoryStoreRequest;
use App\Http\Requests\SubCategoryUpdateRequest;
use App\Models\Category;
use App\Models\SubCategory;
use App\Traits\CrudTrait;
use App\Utils\CrudConfig;

class SubCategoryController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->init(new CrudConfig(
            resource: 'sub-categories',
            modelClass: SubCategory::class,
            storeRequestClass: SubCategoryStoreRequest::class,
            updateRequestClass: SubCategoryUpdateRequest::class,
            searchColumns: ['name'],
            exportClass: SubCategoriesExport::class,
            componentPath: 'SubCategories/Index',
            withRelations: ['category'],
            addProps: $this->addProps(),
        ));
    }

    protected function addProps(): array
    {
        return [
            'categories' => Category::select('id', 'name')->get(),
        ];
    }
}
