<?php

use App\Http\Controllers\WelcomePageController;

Route::get('/', WelcomePageController::class)->name('welcome');

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\TagController;
use App\Utils\CrudRouter;
use Illuminate\Support\Facades\Route;

// AUTH & VERIFIED

Route::middleware(['auth', 'verified'])->group(function () {
    CrudRouter::setFor('products', ProductController::class);
    CrudRouter::setFor('categories', CategoryController::class);
    CrudRouter::setFor('tags', TagController::class);
    CrudRouter::setFor('brands', BrandController::class);
    CrudRouter::setFor('sub-categories', SubCategoryController::class);
});

CrudRouter::setFor('todos', App\Http\Controllers\TodoController::class);