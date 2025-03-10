<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'sub_category_id',
        'tax_id',
        'brand_id',
        'created_by',
        'name',
        'slug',
        'sku',
        'barcode',
        'code',
        'base_price',
        'base_discount_price',
        'stock_quantity',
        'stock_status',
        'type',
        'weight',
        'dimensions',
        'materials',
        'description',
        'additional_info',
        'is_active',
        'thumbnail',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'base_price' => 'decimal:2',
        'base_discount_price' => 'decimal:2',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(ProductAttribute::class, 'product_variation_attributes');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_status', 'in_stock');
    }

    // Helper methods
    public function isVariable(): bool
    {
        return $this->type === 'variable';
    }

    public function getCurrentPrice(): float
    {
        return $this->base_discount_price ?? $this->base_price;
    }

    public function hasDiscount(): bool
    {
        return !is_null($this->base_discount_price);
    }

    public function getDiscountPercentage(): ?float
    {
        if (!$this->hasDiscount()) {
            return null;
        }

        return round((($this->base_price - $this->base_discount_price) / $this->base_price) * 100);
    }
}