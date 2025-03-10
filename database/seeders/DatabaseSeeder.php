<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\ProductAttribute;
use App\Models\ProductVariation;
use Database\Seeders\TagTableSeeder;
use Database\Seeders\TaxTableSeeder;
use App\Models\ProductAttributeValue;
use Database\Seeders\UserTableSeeder;
use Database\Seeders\BrandTableSeeder;
use Database\Seeders\CouponTableSeeder;
use Database\Seeders\ProductTableSeeder;
use Database\Seeders\CategoryTableSeeder;
use Database\Seeders\CustomerTableSeeder;
use Database\Seeders\SupplierTableSeeder;
use Database\Seeders\SubCategoryTableSeeder;
use Database\Seeders\ProductStockTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(UserTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(SubCategoryTableSeeder::class);
        $this->call(BrandTableSeeder::class);
        $this->call(ProductTableSeeder::class);


        // $this->call(CouponTableSeeder::class);
        // $this->call(CustomerTableSeeder::class);
        // $this->call(SupplierTableSeeder::class);
        // $this->call(TagTableSeeder::class);
        // $this->call(TaxTableSeeder::class);
        // $this->call(ProductStockTableSeeder::class);
    }
}