<?php

namespace App\Services;

use App\Interfaces\WarrantyServiceInterface;
use App\Models\WarrantyGuarantee;
use App\Services\ServiceTrait;

class WarrantyService implements WarrantyServiceInterface
{
    use ServiceTrait;
    public $model = WarrantyGuarantee::class;
}
