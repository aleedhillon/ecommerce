<?php
namespace App\Services;

use App\Models\Supplier;
use App\Services\ServiceTrait;
use App\Interfaces\SupplierServiceInterface;

class SupplierService implements SupplierServiceInterface
{
    use ServiceTrait;
    public $model = Supplier::class;
}
