<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Exports\TagExport;
use App\Http\Requests\TagStoreRequest;
use App\Http\Requests\TagUpdateRequest;
use App\Http\Controllers\BaseCrudController;

class TagController extends BaseCrudController
{
    public function __construct()
    {
        parent::__construct([
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
