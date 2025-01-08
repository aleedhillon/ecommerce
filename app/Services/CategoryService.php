<?php

namespace App\Services;

use App\Models\Category;
use App\Services\ServiceTrait;
use App\Interfaces\CategoryServiceInterface;

class CategoryService implements CategoryServiceInterface
{
    use ServiceTrait;
    public $model = Category::class;
}
