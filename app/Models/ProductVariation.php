<?php

namespace App\Models;

use App\Models\ProductAttributeValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductVariation extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'discount_price',
        'stock_quantity',
        'stock_status',
        'image',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductAttributeValue::class,
            'product_variation_attributes',
            'variation_id',
            'attribute_value_id'
        );
    }
}