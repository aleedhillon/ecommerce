<?php

namespace App\Services;

use App\Models\Tax;
use App\Services\ServiceTrait;
use App\Interfaces\TaxServiceInterface;

class TaxService implements TaxServiceInterface
{
    use ServiceTrait;
    public $model = Tax::class;
}
