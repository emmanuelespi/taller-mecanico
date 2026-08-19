<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SparePart extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'category',
        'unit',
        'unit_price',
        'purchase_price',
        'stock',
        'minimum_stock',
        'location',
        'supplier',
        'is_active',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'stock' => 'integer',
        'minimum_stock' => 'integer',
        'is_active' => 'boolean',
    ];

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class, 'spare_part_id');
    }

    public function workOrders(): BelongsToMany
    {
        return $this->belongsToMany(WorkOrder::class, 'work_order_spare_parts')
            ->withPivot('quantity', 'unit_price')
            ->withTimestamps();
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'minimum_stock');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('sku', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
    }

    public function getStatusBadgeAttribute(): string
    {
        return $this->is_active
            ? '<span class="px-2 py-1 text-xs text-green-400 rounded-full bg-green-500/10">Activo</span>'
            : '<span class="px-2 py-1 text-xs text-red-400 rounded-full bg-red-500/10">Inactivo</span>';
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) return 'Agotado';
        if ($this->stock <= $this->minimum_stock) return 'Stock bajo';
        return 'En stock';
    }

    // Métodos de negocio
    public function isLowStock(): bool
    {
        return $this->stock <= $this->minimum_stock;
    }

    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }

    public function hasStock(int $quantity): bool
    {
        return $this->stock >= $quantity;
    }

    // Accessors
    public function getCategoryLabelAttribute(): string
    {
        $categoryEnum = \App\Enums\ProductsCategory::tryFrom($this->category);
        return $categoryEnum ? $categoryEnum->label() : ($this->category ?? 'N/A');
    }

    public function getStockClassAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'text-red-500 font-bold';
        }
        if ($this->stock <= $this->minimum_stock) {
            return 'text-yellow-500 font-semibold';
        }
        return 'text-gray-200';
    }
}
