<?php

use App\Utils\CrudRouter;


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\WelcomePageController;
use App\Http\Controllers\PaymentMethodController;

Route::get('/', WelcomePageController::class)->name('welcome');

// AUTH & VERIFIED

Route::middleware(['auth', 'verified'])->group(function () {
    CrudRouter::setFor('products', ProductController::class);
    CrudRouter::setFor('categories', CategoryController::class);
    CrudRouter::setFor('tags', TagController::class);
    CrudRouter::setFor('brands', BrandController::class);
    CrudRouter::setFor('sub-categories', SubCategoryController::class);
    CrudRouter::setFor('payment-methods', PaymentMethodController::class);
    CrudRouter::setFor('todos', TodoController::class);
});
