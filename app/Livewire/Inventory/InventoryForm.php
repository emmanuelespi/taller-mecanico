<?php

namespace App\Livewire\Inventory;

use App\Enums\ProductsCategory;
use App\Models\SparePart;
use App\Services\InventoryManager;
use Livewire\Component;

class InventoryForm extends Component
{
    public $open = false;

    public $productId = null;

    public $name = '';

    public $sku = '';

    public $description = '';

    public $category = '';

    public $unit = 'pieza';

    public $unit_price = '';

    public $purchase_price = '';

    public $stock = '';

    public $minimum_stock = '';

    public $location = '';

    public $supplier = '';

    public $is_active = true;

    protected $listeners = [
        'openProductModal' => 'openModal',
    ];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:50|unique:spare_parts,sku,'.($this->productId ?? 'NULL'),
            'description' => 'nullable|string',
            'category' => 'required|string',
            'unit' => 'required|string|max:20',
            'unit_price' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'location' => 'nullable|string|max:100',
            'supplier' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'name.required' => 'El nombre del producto es obligatorio.',
        'sku.unique' => 'Este código SKU ya está en uso.',
        'category.required' => 'Debe seleccionar una categoría.',
        'unit_price.required' => 'El precio de venta es obligatorio.',
        'stock.required' => 'El stock inicial es obligatorio.',
        'minimum_stock.required' => 'El stock mínimo es obligatorio.',
    ];

    public function openModal($productId = null)
    {
        $this->resetValidation();
        $this->reset();

        $this->productId = $productId;

        if ($productId) {
            $product = SparePart::findOrFail($productId);
            $this->name = $product->name;
            $this->sku = $product->sku;
            $this->description = $product->description;
            $this->category = $product->category;
            $this->unit = $product->unit;
            $this->unit_price = (string) $product->unit_price;
            $this->purchase_price = (string) $product->purchase_price;
            $this->stock = (string) $product->stock;
            $this->minimum_stock = (string) $product->minimum_stock;
            $this->location = $product->location;
            $this->supplier = $product->supplier;
            $this->is_active = $product->is_active;
        }

        $this->open = true;
    }

    public function save()
    {
        $this->validate();
        $data = [
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description,
            'category' => $this->category,
            'unit' => $this->unit,
            'unit_price' => (float) $this->unit_price,
            'purchase_price' => (float) ($this->purchase_price ?: 0),
            'stock' => (int) $this->stock,
            'minimum_stock' => (int) $this->minimum_stock,
            'location' => $this->location,
            'supplier' => $this->supplier,
            'is_active' => $this->is_active,
        ];

        $manager = new InventoryManager;

        if ($this->productId) {
            $product = SparePart::findOrFail($this->productId);
            $manager->update($product, $data);
            $message = 'Producto actualizado correctamente.';
        } else {
            $manager->create($data);
            $message = 'Producto creado correctamente.';
        }

        $this->open = false;
        $this->reset();
        $this->dispatch('productSaved')->to(InventoryIndex::class);
        $this->dispatch('notify', message: $message);

    }

    public function close()
    {
        $this->open = false;
        $this->reset();
    }

    public function render()
    {
        $categories = ProductsCategory::options();

        return view('livewire.inventory.inventory-form', [
            'categories' => $categories,
        ]);
    }
}
