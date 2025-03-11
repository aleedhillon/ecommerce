<?php

namespace App\Http\Controllers;

use App\Traits\CrudTrait;
use App\Models\Brand;
use App\Http\Controllers\Controller;
use App\Exports\BrandExport;
use App\Http\Requests\BrandStoreRequest;
use App\Http\Requests\BrandUpdateRequest;

class BrandController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->modelClass = Brand::class;
        $this->componentPath = 'Brands/Index';
        $this->storeRequestClass = BrandStoreRequest::class;
        $this->updateRequestClass = BrandUpdateRequest::class;
        $this->resource = 'brands';
        $this->exportClass = BrandExport::class;
    }
}
