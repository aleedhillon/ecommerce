<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Support\Str;
use App\Http\Response\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\Response;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Interfaces\ProductServiceInterface;

class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->productService->all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request)
    {
        // $sku = Str::slug($product->product_name)."-".Str::random(5);

        $product = $this->productService->store($request->validated());

        if ($product->discount_price > $product->price) {
            return ("Discounted Price too long");
        }

        $regular_price = $product->price;

        if ($product->discount_price == NULL) {
            $regular_price = $product->price;
        } else {
            $discount_price = $product->discount_price;
            $regular_price - $discount_price;
        }
        return ApiResponse::created($product, 'Product create successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $this->productService->find($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product = $this->productService->update($request->validated(), $product);
        return ApiResponse::created($product, 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product = $this->productService->delete($product);
        return ApiResponse::created($product, 'Product deleted successfully!');
    }
}
