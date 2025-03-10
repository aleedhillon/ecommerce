<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductAttributeValue extends Model
{
    protected $fillable = [
        'attribute_id',
        'value',
        'display_value',
        'color_code'
    ];

    // Relationships
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class, 'attribute_id');
    }

    public function variations(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductVariation::class,
            'product_variation_attributes',
            'attribute_value_id',
            'variation_id'
        );
    }

    // Helper methods
    public function getDisplayValue(): string
    {
        return $this->display_value ?? $this->value;
    }

    public function isColorAttribute(): bool
    {
        return $this->attribute->type === 'color';
    }
}