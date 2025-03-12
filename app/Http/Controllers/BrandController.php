<?php

namespace App\Http\Controllers;

use App\Exports\BrandExport;
use App\Http\Requests\BrandStoreRequest;
use App\Http\Requests\BrandUpdateRequest;
use App\Models\Brand;
use App\Traits\CrudTrait;

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
