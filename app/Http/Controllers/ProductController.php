<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Traits\CrudTrait;
use App\Utils\CrudConfig;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;

class ProductController extends Controller
{
    use CrudTrait;
    public function __construct()
    {
        $this->init(new CrudConfig(
            resource: 'products',
            modelClass: Product::class,
            storeRequestClass: ProductStoreRequest::class,
            updateRequestClass: ProductUpdateRequest::class,
            componentPath: 'Products/Index',
            searchColumns: ['name', 'description'],
            exportClass: ProductExport::class,
            withRelations: ['category', 'brand', 'subCategory', 'tags'],
        ));
    }

    protected function modifyQuery($query)
    {
        return $query;
    }
}
