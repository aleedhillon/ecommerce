<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\SubCategoryController;

Route::middleware(['auth', 'verified'])->group(function () {
    // Categories
    Route::resource('/categories', CategoryController::class);
    Route::post('/categories/bulk-destroy', [CategoryController::class, 'bulkDestroy'])->name('categories.bulk-destroy');
    // Sub-Categories
    Route::resource('/sub-categories', SubCategoryController::class);
    // Brands
    Route::resource('/brands', BrandController::class);
    Route::post('/brands/bulk-destroy', [BrandController::class, 'bulkDestroy'])->name('brands.bulk-destroy');
    // Tags
    Route::resource('/tags', TagController::class);
    Route::post('/tags/bulk-destroy', [TagController::class, 'bulkDestroy'])->name('tags.bulk-destroy');
    // Colors
    Route::resource('colors', ColorController::class);
    Route::post('/colors/bulk-destroy', [ColorController::class, 'bulkDestroy'])->name('colors.bulk-destroy');
});
