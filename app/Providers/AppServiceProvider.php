<?php

namespace App\Providers;

use Blueprint\Blueprint;
use App\Services\TagService;
use App\Services\TaxService;
use App\Services\CartService;
use App\Services\UnitService;
use App\Services\BrandService;
use App\Services\OrderService;
use App\Services\CouponService;
use App\Services\PaymentService;
use App\Services\ProductService;
use App\Services\CategoryService;
use App\Services\CustomerService;
use App\Services\DiscountService;
use App\Services\ShoppingService;
use App\Services\SupplierService;
use App\Services\WarrantyService;
use App\Services\InventoryService;
use App\Services\WarehouseService;
use App\Services\SubCategoryService;
use Illuminate\Support\Facades\Vite;
use App\Services\ProductStockService;
use App\Generators\InertiaVueGenerator;
use App\Interfaces\TagServiceInterface;
use App\Interfaces\TaxServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\CartServiceInterface;
use App\Interfaces\UnitServiceInterface;
use App\Interfaces\BrandServiceInterface;
use App\Interfaces\OrderServiceInterface;
use App\Interfaces\CouponServiceInterface;
use App\Interfaces\PaymentServiceInterface;
use App\Interfaces\ProductServiceInterface;
use App\Interfaces\CategoryServiceInterface;
use App\Interfaces\CustomerServiceInterface;
use App\Interfaces\DiscountServiceInterface;
use App\Interfaces\ShoppingServiceInterface;
use App\Interfaces\SupplierServiceInterface;
use App\Interfaces\WarrantyServiceInterface;
use App\Interfaces\InventoryServiceInterface;
use App\Interfaces\WarehouseServiceInterface;
use App\Interfaces\SubCategoryServiceInterface;
use App\Interfaces\ProductStockServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
        $this->app->bind(TagServiceInterface::class, TagService::class);
        $this->app->bind(CouponServiceInterface::class, CouponService::class);
        $this->app->bind(TaxServiceInterface::class, TaxService::class);
        $this->app->bind(BrandServiceInterface::class, BrandService::class);
        $this->app->bind(SubCategoryServiceInterface::class, SubCategoryService::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(CustomerServiceInterface::class, CustomerService::class);
        $this->app->bind(SupplierServiceInterface::class, SupplierService::class);
        $this->app->bind(ProductStockServiceInterface::class, ProductStockService::class);
        $this->app->bind(WarehouseServiceInterface::class, WarehouseService::class);
        $this->app->bind(UnitServiceInterface::class, UnitService::class);
        $this->app->bind(InventoryServiceInterface::class, InventoryService::class);
        $this->app->bind(WarrantyServiceInterface::class, WarrantyService::class);
        $this->app->bind(ShoppingServiceInterface::class, ShoppingService::class);
        $this->app->bind(CartServiceInterface::class, CartService::class);
        $this->app->bind(DiscountServiceInterface::class, DiscountService::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
        $this->app->bind(PaymentServiceInterface::class, PaymentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // $this->app->extend(Blueprint::class, function (Blueprint $blueprint, $app) {
        //     $blueprint->registerGenerator(new InertiaVueGenerator($app['files']));
        //     return $blueprint;
        // });

        Vite::prefetch(concurrency: 3);
    }
}
