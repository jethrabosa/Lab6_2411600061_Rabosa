<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'category',
        'description',
        'quantity',
        'reorder_level',
        'unit_cost',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function latestTransaction(): HasOne
    {
        return $this->hasOne(InventoryTransaction::class)->latestOfMany();
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->reorder_level;
    }

    public function isOutOfStock(): bool
    {
        return $this->quantity <= 0;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->quantity <= 0) {
            return 'Out of Stock';
        }

        if ($this->quantity <= $this->reorder_level) {
            return 'Low Stock';
        }

        return 'In Stock';
    }

    public function getStockStatusColorAttribute(): string
    {
        if ($this->quantity <= 0) {
            return 'red';
        }

        if ($this->quantity <= $this->reorder_level) {
            return 'amber';
        }

        return 'emerald';
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity', '<=', 'reorder_level');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '<=', 0);
    }

    public function scopeInCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
