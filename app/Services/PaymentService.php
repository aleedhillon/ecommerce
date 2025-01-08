<?php
namespace App\Services;

use App\Models\Payments;
use App\Services\ServiceTrait;
use App\Interfaces\PaymentServiceInterface;

class PaymentService implements PaymentServiceInterface
{
    use ServiceTrait;
    public $model = Payments::class;
}
