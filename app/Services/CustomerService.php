<?php
namespace App\Services;

use App\Models\Customer;
use App\Services\ServiceTrait;
use App\Interfaces\CustomerServiceInterface;

class CustomerService implements CustomerServiceInterface
{
    use ServiceTrait;
    public $model = Customer::class;
}
