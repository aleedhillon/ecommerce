<?php

namespace App\Http\Controllers;

use App\Exports\TagExport;
use App\Http\Requests\TagStoreRequest;
use App\Http\Requests\TagUpdateRequest;
use App\Models\Tag;
use App\Traits\CrudTrait;
use App\Utils\CrudConfig;

class TagController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->init(new CrudConfig(
            resource: 'tags',
            modelClass: Tag::class,
            storeRequestClass: TagStoreRequest::class,
            updateRequestClass: TagUpdateRequest::class,
            componentPath: 'Tags/Index',
            searchColumns: ['name'],
            exportClass: TagExport::class,
            withRelations: [],

        ));
    }
}
