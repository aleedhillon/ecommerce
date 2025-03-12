<?php

namespace App\Http\Controllers;

use App\Exports\BrandExport;
use App\Http\Requests\BrandStoreRequest;
use App\Http\Requests\BrandUpdateRequest;
use App\Models\Brand;
use App\Traits\CrudTrait;
use App\Utils\CrudConfig;

class BrandController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->init(new CrudConfig(
            resource: 'brands',
            modelClass: Brand::class,
            storeRequestClass: BrandStoreRequest::class,
            updateRequestClass: BrandUpdateRequest::class,
            componentPath: 'Brands/Index',
            searchColumns: ['name'],
            exportClass: BrandExport::class,
            withRelations: [],
        ));
    }
}
