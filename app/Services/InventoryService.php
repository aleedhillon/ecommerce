<?php

namespace App\Services;

use App\Models\Inventory;
use App\Services\ServiceTrait;
use App\Interfaces\InventoryServiceInterface;

class InventoryService implements InventoryServiceInterface
{
    use ServiceTrait;
    public $model = Inventory::class;
}
