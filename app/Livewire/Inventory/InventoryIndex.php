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
