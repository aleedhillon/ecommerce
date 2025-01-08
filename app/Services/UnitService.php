<?php
namespace App\Services;

use App\Models\Unit;
use App\Services\ServiceTrait;
use App\Interfaces\UnitServiceInterface;

class UnitService implements UnitServiceInterface
{
    use ServiceTrait;
    public $model = Unit::class;
}
