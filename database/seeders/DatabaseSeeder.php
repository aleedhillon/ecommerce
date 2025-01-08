<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(CouponTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(BrandTableSeeder::class);
        $this->call(CustomerTableSeeder::class);
        $this->call(SubCategoryTableSeeder::class);
        $this->call(SupplierTableSeeder::class);
        $this->call(TagTableSeeder::class);
        $this->call(TaxTableSeeder::class);
        $this->call(ProductTableSeeder::class);
        $this->call(ProductStockTableSeeder::class);
    }
}
