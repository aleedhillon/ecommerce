<?php
namespace App\Services;

use App\Models\Shopping;
use App\Services\ServiceTrait;
use App\Interfaces\ShoppingServiceInterface;

class ShoppingService implements ShoppingServiceInterface
{
    use ServiceTrait;
    public $model = Shopping::class;
}
