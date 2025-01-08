<?php
namespace App\Services;

use App\Models\Warehouse;
use App\Services\ServiceTrait;
use App\Interfaces\WarehouseServiceInterface;

class WarehouseService implements WarehouseServiceInterface
{
    use ServiceTrait;
    public $model = Warehouse::class;
}
