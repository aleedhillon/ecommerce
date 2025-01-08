<?php

namespace App\Services;

use App\Models\ProductStock;
use App\Services\ServiceTrait;
use App\Interfaces\ProductStockServiceInterface;

class ProductStockService implements ProductStockServiceInterface
{
    use ServiceTrait;
    public $model = ProductStock::class;
}
