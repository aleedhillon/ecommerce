<?php

namespace App\Http\Controllers;

use App\Exports\TagExport;
use App\Http\Requests\TagStoreRequest;
use App\Http\Requests\TagUpdateRequest;
use App\Models\Tag;
use App\Traits\CrudTrait;

class TagController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->init([
            'resource' => 'tags',
            'modelClass' => Tag::class,
            'storeRequestClass' => TagStoreRequest::class,
            'updateRequestClass' => TagUpdateRequest::class,
            'searchColumns' => ['name'],
            'exportClass' => TagExport::class,
            'componentPath' => 'Tags/Index',
        ]);
    }
}
