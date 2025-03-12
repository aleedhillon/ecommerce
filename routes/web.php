<?php

use App\Http\Controllers\WelcomePageController;

Route::get('/', WelcomePageController::class)->name('welcome');

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\TagController;
use App\Utils\CrudRouter;
use Illuminate\Support\Facades\Route;

// AUTH & VERIFIED

Route::middleware(['auth', 'verified'])->group(function () {
    CrudRouter::setFor('categories', CategoryController::class);
    CrudRouter::setFor('tags', TagController::class);
    CrudRouter::setFor('brands', BrandController::class);
    CrudRouter::setFor('sub-categories', SubCategoryController::class);

});
