<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use App\Utils\CrudConfig;
use App\Traits\ApiCrudTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\BrandStoreRequest;
use App\Http\Requests\BrandUpdateRequest;

class BrandController extends Controller
{
    use ApiCrudTrait;

    public function __construct()
    {
        $this->init(new CrudConfig(
            resource: 'brands',
            modelClass: Brand::class,
            storeRequestClass: BrandStoreRequest::class,
            updateRequestClass: BrandUpdateRequest::class,
            searchColumns: ['name'],
        ));
    }
}
