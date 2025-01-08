<?php
namespace App\Services;

use App\Models\SubCategory;
use App\Services\ServiceTrait;
use App\Interfaces\SubCategoryServiceInterface;

class SubCategoryService implements SubCategoryServiceInterface
{
    use ServiceTrait;
    public $model = SubCategory::class;
}
