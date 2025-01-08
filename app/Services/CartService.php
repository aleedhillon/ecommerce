<?php
namespace App\Services;

use App\Models\Cart;
use App\Services\ServiceTrait;
use App\Interfaces\CartServiceInterface;

class CartService implements CartServiceInterface
{
    use ServiceTrait;
    public $model = Cart::class;
}
