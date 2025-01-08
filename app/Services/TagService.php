<?php

namespace App\Services;

use App\Models\Tag;
use App\Services\ServiceTrait;
use App\Interfaces\TagServiceInterface;

class TagService implements TagServiceInterface
{
    use ServiceTrait;
    public $model = Tag::class;
}