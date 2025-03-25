<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentMethodStoreRequest;
// use App\Exports\PaymentMethodExport;
use App\Http\Requests\PaymentMethodUpdateRequest;
use App\Models\PaymentMethod;
use App\Traits\CrudTrait;
use App\Utils\CrudConfig;

class PaymentMethodController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->init(new CrudConfig(
            resource: 'payment-methods',
            modelClass: PaymentMethod::class,
            storeRequestClass: PaymentMethodStoreRequest::class,
            updateRequestClass: PaymentMethodUpdateRequest::class,
            componentPath: 'PaymentMethods/Index',
            searchColumns: ['name'],
            // exportClass: PaymentMethodExport::class,
            withRelations: [],
        ));
    }
}
