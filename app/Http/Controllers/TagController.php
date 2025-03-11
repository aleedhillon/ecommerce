<?php

namespace App\Http\Controllers;

use App\Traits\CrudTrait;

use App\Models\Tag;
use App\Http\Controllers\Controller;
use App\Exports\TagExport;
use App\Http\Requests\TagStoreRequest;
use App\Http\Requests\TagUpdateRequest;

class TagController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->modelClass = Tag::class;
        $this->componentPath = 'Tags/Index';
        $this->storeRequestClass = TagStoreRequest::class;
        $this->updateRequestClass = TagUpdateRequest::class;
        $this->resource = 'tags';
        $this->exportClass = TagExport::class;
    }
}
