<?php
namespace App\Services;

use App\Models\Discount;
use App\Services\ServiceTrait;
use App\Interfaces\DiscountServiceInterface;

class DiscountService implements DiscountServiceInterface
{
    use ServiceTrait;
    public $model = Discount::class;
}
