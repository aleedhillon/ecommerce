<?php

use App\Http\Controllers\WelcomePageController;

Route::get('/', WelcomePageController::class)->name('welcome');


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Utils\CrudRouter;

# AUTH & VERIFIED

Route::middleware(['auth', 'verified'])->group(function () {
    // Categories
    Route::resource('categories', CategoryController::class);
    Route::get('/categories/export/excel', [CategoryController::class, 'export'])->name('categories.export');
    Route::post('/categories/destroy/bulk', [CategoryController::class, 'bulkDestroy'])->name('categories.bulk-destroy');

    // Sub-Categories
    Route::resource('sub-categories', SubCategoryController::class);
    Route::get('/sub-categories/export/excel', [SubCategoryController::class, 'export'])->name('sub_categories.export');

    CrudRouter::setFor('tags', TagController::class);
    CrudRouter::setFor('brands', BrandController::class);
    
});
