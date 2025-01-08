<?php
namespace App\Services;

use App\Models\Coupon;
use App\Services\ServiceTrait;
use App\Interfaces\CouponServiceInterface;

class CouponService implements CouponServiceInterface
{
    use ServiceTrait;
    public $model = Coupon::class;
}
