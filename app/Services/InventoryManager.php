<?php

namespace App\Services;

use App\Models\InventoryMovement;
use App\Models\SparePart;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class InventoryManager
{
    public function getAll(string $search = '', string $category = '', bool $onlyActive = true, bool $onlyLowStock = false): LengthAwarePaginator
    {
        $query = SparePart::query();

        if ($onlyActive) {
            $query->where('is_active', true);
        }

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        if ($onlyLowStock) {
            $query->whereColumn('stock', '<=', 'minimum_stock');
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('name')->paginate(10);
    }

    public function create(array $data): SparePart
    {
        return DB::transaction(function () use ($data) {
            $product = SparePart::create($data);
            if ($product->stock > 0) {
                InventoryMovement::create([
                    'spare_part_id' => $product->id,
                    'user_id' => auth()->id(),
                    'type' => 'in',
                    'quantity' => $product->stock,
                    'reason' => 'Inventario inicial',
                ]);
            }

            $this->checkAndNotifyLowStock($product);

            return $product;
        });
    }

    public function update(SparePart $product, array $data): SparePart
    {
        $oldStock = $product->stock;
        $newStock = $data['stock'] ?? $oldStock;

        $product->update($data);

        if ($newStock != $oldStock) {
            $difference = $newStock - $oldStock;
            InventoryMovement::create([
                'spare_part_id' => $product->id,
                'user_id' => auth()->id(),
                'type' => $difference > 0 ? 'in' : 'out',
                'quantity' => abs($difference),
                'reason' => 'Ajuste de inventario',
            ]);
        }

        $this->checkAndNotifyLowStock($product);

        return $product;
    }

    public function delete(SparePart $product): void
    {
        if ($product->inventoryMovements()->exists()) {
            throw new \Exception('No se puede eliminar un producto con historial de movimientos.');
        }

        $product->delete();
    }

    public function toggleActive(SparePart $product): SparePart
    {
        $product->is_active = ! $product->is_active;
        $product->save();

        return $product;
    }

    public function updateStock(SparePart $product, int $quantity, string $type, string $reason, ?string $reference = null): SparePart
    {
        $product = DB::transaction(function () use ($product, $quantity, $type, $reason, $reference) {
            if ($type === 'out' && ! $product->hasStock($quantity)) {
                throw new \Exception("Stock insuficiente para {$product->name} . Disponible: {$product->stock}");
            }

            $product->stock += ($type === 'in' ? $quantity : -$quantity);
            $product->save();

            InventoryMovement::create([
                'spare_part_id' => $product->id,
                'user_id' => auth()->id(),
                'type' => $type,
                'quantity' => $quantity,
                'reason' => $reason,
                'reference' => $reference,
            ]);

            return $product;
        });

        $this->checkAndNotifyLowStock($product);

        return $product;
    }

    protected function checkAndNotifyLowStock(SparePart $product): void
    {
        if ($product->stock <= $product->minimum_stock && $product->is_active) {
            $users = \App\Models\User::whereIn('role', ['admin', 'recepcionista'])->where('is_active', true)->get();
            \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\LowStockNotification($product));
        }
    }

    public function getLowStockProducts(): Collection
    {
        return SparePart::whereColumn('stock', '<=', 'minimum_stock')
            ->where('is_active', true)
            ->orderBy('stock')
            ->get();
    }
}
