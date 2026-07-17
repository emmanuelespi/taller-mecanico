<?php

namespace App\Livewire\Inventory;

use App\Enums\ProductsCategory;
use App\Models\SparePart;
use App\Services\InventoryManager;
use Livewire\Component;
use Livewire\WithPagination;

class InventoryIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $categoryFilter = 'all';

    public $onlyLowStock = false;

    public $showConfirmModal = false;

    public $deleteId = null;

    // Propiedades del modal de Restock / Ajuste
    public $showRestockModal = false;
    public $selectedProductId = null;
    public $selectedProductName = '';
    public $selectedProductStock = 0;
    public $restockQuantity = 1;
    public $restockType = 'in';
    public $restockReason = 'Compra de mercadería (Restock)';
    public $restockReference = '';

    protected $queryString = ['search', 'categoryFilter', 'onlyLowStock'];

    protected $listeners = [
        'productSaved' => 'refreshProducts',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function refreshProducts()
    {
        $this->resetPage();
    }

    // Métodos del modal de Restock / Ajuste
    public function openRestockModal($productId)
    {
        $product = SparePart::findOrFail($productId);
        $this->selectedProductId = $product->id;
        $this->selectedProductName = $product->name;
        $this->selectedProductStock = $product->stock;
        $this->restockQuantity = 1;
        $this->restockType = 'in';
        $this->restockReason = 'Compra de mercadería (Restock)';
        $this->restockReference = '';
        $this->showRestockModal = true;
    }

    public function closeRestock()
    {
        $this->showRestockModal = false;
        $this->reset(['selectedProductId', 'selectedProductName', 'selectedProductStock', 'restockQuantity', 'restockType', 'restockReason', 'restockReference']);
    }

    public function saveRestock()
    {
        $this->validate([
            'restockQuantity' => 'required|integer|min:1',
            'restockType' => 'required|in:in,out',
            'restockReason' => 'required|string|max:255',
            'restockReference' => 'nullable|string|max:100',
        ], [
            'restockQuantity.required' => 'La cantidad es obligatoria.',
            'restockQuantity.integer' => 'La cantidad debe ser un número entero.',
            'restockQuantity.min' => 'La cantidad debe ser al menos 1.',
            'restockReason.required' => 'La razón del ajuste es obligatoria.',
        ]);

        try {
            $product = SparePart::findOrFail($this->selectedProductId);
            $manager = new InventoryManager;

            $manager->updateStock(
                $product,
                (int) $this->restockQuantity,
                $this->restockType,
                $this->restockReason,
                $this->restockReference
            );

            $this->showRestockModal = false;
            $this->dispatch('notify', message: 'Inventario actualizado correctamente.');
            $this->resetPage();
            $this->closeRestock();
        } catch (\Exception $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        }
    }

    public function openDeleteModal($id)
    {
        $this->deleteId = $id;
        $this->showConfirmModal = true;
    }

    public function cancelDelete()
    {
        $this->showConfirmModal = false;
        $this->deleteId = null;
    }

    public function deleteProduct()
    {
        try {
            $product = SparePart::findOrFail($this->deleteId);
            $manager = new InventoryManager;
            $manager->delete($product);

            $this->showConfirmModal = false;
            $this->deleteId = null;

            $this->dispatch('notify', message: 'Producto eliminado correctamente.');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        }
    }

    public function toggleActive($id)
    {
        try {
            $product = SparePart::findOrFail($id);
            $manager = new InventoryManager;

            $updateProduct = $manager->toggleActive($product);

            $status = $updateProduct->is_active ? 'activado' : 'desactivado';

            $this->dispatch('notify', message: "Producto {$status} correctamente");

            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        }

    }

    public function render()
    {
        $manager = new InventoryManager;
        $products = $manager->getAll($this->search, $this->categoryFilter, true, $this->onlyLowStock);
        $categories = ProductsCategory::options();

        return view('livewire.inventory.inventory-index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
