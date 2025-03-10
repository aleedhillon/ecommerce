<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductVariation;
use App\Models\Product;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        if (User::count() > 0) {
            User::factory()->create([
                'name' => 'Mr. Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
            ]);

            User::factory(3)->create();
        }

        // $this->call(CouponTableSeeder::class);
        // $this->call(CategoryTableSeeder::class);
        // $this->call(BrandTableSeeder::class);
        // $this->call(CustomerTableSeeder::class);
        // $this->call(SubCategoryTableSeeder::class);
        // $this->call(SupplierTableSeeder::class);
        // $this->call(TagTableSeeder::class);
        // $this->call(TaxTableSeeder::class);
        // $this->call(ProductTableSeeder::class);
        // $this->call(ProductStockTableSeeder::class);



        // RnD Area
        // ==============================================================

        // Create a Color attribute
        // $colorAttribute = ProductAttribute::create([
        //     'name' => 'color',
        //     'display_name' => 'Color',
        //     'type' => 'color'
        // ]);

        // // Create a Size attribute
        // $sizeAttribute = ProductAttribute::create([
        //     'name' => 'size',
        //     'display_name' => 'Size',
        //     'type' => 'select'
        // ]);

        // // Create Color values
        // $colors = [
        //     ['value' => 'red', 'display_value' => 'Ruby Red', 'color_code' => '#FF0000'],
        //     ['value' => 'blue', 'display_value' => 'Ocean Blue', 'color_code' => '#0000FF'],
        //     ['value' => 'black', 'display_value' => 'Jet Black', 'color_code' => '#000000'],
        // ];

        // $colorValues = collect();
        // foreach ($colors as $color) {
        //     $colorValues->push(ProductAttributeValue::create([
        //         'attribute_id' => $colorAttribute->id,
        //         ...$color
        //     ]));
        // }

        // // Create Size values
        // $sizes = [
        //     ['value' => 'S', 'display_value' => 'Small'],
        //     ['value' => 'M', 'display_value' => 'Medium'],
        //     ['value' => 'L', 'display_value' => 'Large'],
        //     ['value' => 'XL', 'display_value' => 'Extra Large'],
        // ];

        // $sizeValues = collect();
        // foreach ($sizes as $size) {
        //     $sizeValues->push(ProductAttributeValue::create([
        //         'attribute_id' => $sizeAttribute->id,
        //         ...$size
        //     ]));
        // }

        // // Create a sample variable product
        // $product = Product::create([
        //     'name' => 'Classic T-Shirt',
        //     'slug' => Str::slug('Classic T-Shirt'),
        //     'type' => 'variable',
        //     'base_price' => 19.99,
        //     'description' => 'A comfortable classic t-shirt available in multiple colors and sizes.',
        //     'meta_title' => 'Classic T-Shirt | Your Store',
        //     'meta_description' => 'Shop our classic t-shirt available in multiple colors and sizes.',
        //     'is_active' => true,
        // ]);

        // Create variations for each color and size combination

        $product = Product::first();
        $colorValues = ProductAttributeValue::where('attribute_id', 1)->get();
        $sizeValues = ProductAttributeValue::where('attribute_id', 2)->get();
        foreach ($colorValues as $color) {
            foreach ($sizeValues as $size) {
                $sku = sprintf('TSH-%s-%s', strtoupper($color->value), $size->value);

                $variation = ProductVariation::create([
                    'product_id' => $product->id,
                    'sku' => $sku,
                    'price' => 19.99,
                    'stock_quantity' => 100,
                    'stock_status' => 'in_stock',
                    'is_active' => true,
                ]);

                // Attach color and size attributes to the variation
                $variation->attributeValues()->attach([
                    $color->id,
                    $size->id
                ]);
            }
        }
    }
}