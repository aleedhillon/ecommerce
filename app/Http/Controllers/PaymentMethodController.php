<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
// use App\Exports\PaymentMethodExport;
use App\Utils\CrudConfig;
use App\Traits\CrudTrait;
use App\Http\Requests\PaymentMethodStoreRequest;
use App\Http\Requests\PaymentMethodUpdateRequest;

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
