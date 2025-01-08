<?php
namespace App\Services;

use App\Models\Product;
use App\Services\ServiceTrait;
use App\Interfaces\ProductServiceInterface;

class ProductService implements ProductServiceInterface
{
    use ServiceTrait;
    public $model = Product::class;
}
