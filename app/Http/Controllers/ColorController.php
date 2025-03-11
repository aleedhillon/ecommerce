<?php

namespace App\Http\Controllers;

use App\Traits\CrudTrait;
use App\Models\Color;
use App\Http\Controllers\Controller;
use App\Http\Requests\ColorStoreRequest;
use App\Http\Requests\ColorUpdateRequest;

class ColorController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->modelClass = Color::class;
        $this->componentPath = 'Colors/Index';
        $this->storeRequestClass = ColorStoreRequest::class;
        $this->updateRequestClass = ColorUpdateRequest::class;
        $this->resource = 'colors';
    }
}
