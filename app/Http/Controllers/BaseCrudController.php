<?php

namespace App\Http\Controllers;

use App\Traits\CrudTrait;
use App\Http\Controllers\Controller;

class BaseCrudController extends Controller
{
    use CrudTrait;

    public function __construct(array $init)
    {
        $this->init($init);
    }
}
