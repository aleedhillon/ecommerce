<?php
namespace App\Services;

use App\Models\Order;
use App\Services\ServiceTrait;
use App\Interfaces\OrderServiceInterface;

class OrderService implements OrderServiceInterface
{
    use ServiceTrait;
    public $model = Order::class;
}
