<?php
namespace App\Services;

use App\Services\ServiceTrait;
use App\Interfaces\BrandServiceInterface;
use App\Models\Brand;

class BrandService implements BrandServiceInterface
{
    use ServiceTrait;
    public $model = Brand::class;
}
