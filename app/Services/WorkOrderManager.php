<?php

namespace App\Services;

use App\Models\InventoryMovement;
use App\Models\SparePart;
use App\Models\User;
use App\Models\WorkOrder;
use App\Notifications\LowStockNotification;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class WorkOrderManager
{
    /**
     * Agrega un repuesto a la orden, descuenta el inventario y recalcula el total.
     */
    public function addSpareParts(WorkOrder $order, int $partId, int $quantity, float $unitPrice)
    {
        return DB::transaction(function () use ($order, $partId, $quantity, $unitPrice) {
            //Obtener la refacción con bloqueo para lectura/escritura
            $sparePart = SparePart::lockForUpdate()->findOrFail($partId);

            if ($sparePart->stock < $quantity) {
                throw new Exception("Stock insuficiente para '{$sparePart->name}'. Disponible: {$sparePart->stock}, solicitado: {$quantity}.");
            }

            $sparePart->decrement('stock', $quantity);

            InventoryMovement::create([
                'spare_part_id' => $sparePart->id,
                'user_id'       => auth()->id() ?: $order->user_id,
                'type'          => 'out',
                'quantity'      => $quantity,
                'reason'        => "Asignado a la orden {$order->order_number}",
                'reference'     => $order->order_number,
            ]);

            if ($sparePart->stock <= $sparePart->minimum_stock && $sparePart->is_active) {
                $admins = User::whereIn('role', ['admin', 'recepcionista'])->where('is_active', true)->get();
                Notification::send($admins, new LowStockNotification(($sparePart)));
                # code...
            }

            $existingPart = $order->spareParts()->where('spare_part_id', $partId)->first();

            if ($existingPart) {
                $newQuantity = $existingPart->pivot->quantity + $quantity;
                $order->spareParts()->updateExistingPivot($partId, [
                    'quantity' => $newQuantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $newQuantity * $unitPrice,
                ]);
            } else {
                $order->spareParts()->attach($partId, [
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $quantity * $unitPrice,
                ]);
            }

            $this->recalculateTotal($order);

            return $order->fresh(['spareParts', 'services']);
        });
    }

    public function getAll(
        string $search = '',
        string $status = 'all',
        string $dateForm = '',
        string $dateFrom = '',
        string $dateTo = '',
        int $perPage = 15
    ): LengthAwarePaginator {
        $query = WorkOrder::with(['client', 'vehicle', 'mechanic']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('problem_description', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($clientQuery) use ($search) {
                        $clientQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {
                        $vehicleQuery->where('license_plate', 'like', "%{$search}%")
                            ->orWhere('brand', 'like', "%{$search}%")
                            ->orWhere('model', 'like', "%{$search}%");
                    });
            });
        }

        if (!empty($status) && $status !== 'all') {
            $query->where('status', $status);
        }

        if (!empty($dateFrom)) {
            $query->whereDate('entry_date', '>=', $dateFrom);
            # code...
        }

        if (!empty($dateTo)) {
            $query->whereDate('entry_date', '<=', $dateTo);
            # code...
        }

        return $query->latest()->paginate($perPage);
    }

    public function recalculateTotal(WorkOrder $order): void
    {
        // Carga las relaciones frescas para evitar datos en caché
        $order->load(['spareParts', 'services']);

        // Suma simple iterando repuestos
        $totalParts = $order->spareParts->sum(function ($part) {
            return ($part->pivot->subtotal)
                ?? (($part->pivot->quantity ?? 1) * ($part->pivot->unit_price ?? $part->pivot->price ?? 0));
        });

        // Suma simple iterando servicios
        $totalServices = $order->services->sum(function ($service) {
            return ($service->pivot->subtotal)
                ?? (($service->pivot->quantity ?? 1) * ($service->pivot->unit_price ?? $service->pivot->price ?? 0));
        });

        $order->update([
            'total' => $totalParts + $totalServices,
        ]);
    }

    public function create(array $data): workOrder
    {
        $order = DB::transaction(function () use ($data) {
            $data['order_number'] = $this->generateOrderNumber();
            $data['entry_date'] = now();

            return WorkOrder::create($data);
        });

        // Notificar nueva orden
        $users = \App\Models\User::whereIn('role', ['admin', 'recepcionista', 'mecanico'])
            ->where('is_active', true)
            ->get();
        \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\NewWorkOrderNotification($order));

        return $order;
    }

    public function update(WorkOrder $order, array $data): WorkOrder
    {
        return DB::transaction(function () use ($order, $data) {
            $order->update($data);

            return $order;
        });
    }

    public function addService(WorkOrder $order, int $serviceId, float $unitPrice, int $quantity = 1): void
    {
        DB::transaction(function () use ($order, $serviceId, $unitPrice, $quantity) {
            $order->services()->attach($serviceId, [
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
            ]);

            $this->recalculateTotal($order);
        });
    }

    public function removeService(WorkOrder $order, int $serviceId): void
    {
        DB::transaction(function () use ($order, $serviceId) {
            $order->services()->detach($serviceId);
            $this->recalculateTotal($order);
        });
    }

    public function removeSparePart(WorkOrder $order, int $partId): void
    {
        DB::transaction(function () use ($order, $partId) {
            $pivot = $order->spareParts()->where('spare_part_id', $partId)->first();

            if ($pivot) {
                $quantityToReturn = $pivot->pivot->quantity;

                $sparePart = SparePart::lockForUpdate()->find($partId);
                if ($sparePart) {
                    $sparePart->increment('stock', $quantityToReturn);

                    InventoryMovement::create([
                        'spare_part_id' => $sparePart->id,
                        'user_id' => auth()->id() ?: $order->user_id,
                        'type' => 'in',
                        'quantity' => $quantityToReturn,
                        'reason' => "Removido de la orden {$order->order_number}",
                        'reference' => $order->order_number,
                    ]);
                }

                $order->spareParts()->detach($partId);
                $this->recalculateTotal($order);
            }
        });
    }

    public function changeStatus(WorkOrder $order, string $newStatus): WorkOrder
    {
        $oldStatus = $order->status;

        $order = DB::transaction(function () use ($order, $newStatus, $oldStatus) {
            if ($newStatus === 'completed' && $oldStatus !== 'completed') {
                foreach ($order->spareParts as $part) {
                    if (! $part->hasStock($part->pivot->quantity)) {
                        throw new \Exception("Stock insuficiente para {$part->name}");
                    }
                    // Restar stock al completar la orden
                    $part->stock -= $part->pivot->quantity;
                    $part->save();

                    // Registrar movimiento de inventario por venta/consumo
                    \App\Models\InventoryMovement::create([
                        'spare_part_id' => $part->id,
                        'user_id'       => auth()->id() ?: $order->user_id,
                        'type'          => 'out',
                        'quantity'      => $part->pivot->quantity,
                        'reason'        => "Consumo en orden {$order->order_number}",
                        'reference'     => $order->order_number,
                    ]);

                    // Notificar stock bajo de este repuesto si aplica
                    if ($part->stock <= $part->minimum_stock && $part->is_active) {
                        $admins = \App\Models\User::whereIn('role', ['admin', 'recepcionista'])->where('is_active', true)->get();
                        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\LowStockNotification($part));
                    }
                }
                $order->completed_at = now();
            }

            if ($newStatus === 'delivered') {
                $order->delivered_at = now();
            }

            // Restaurar stock si una orden completada se cancela
            if ($newStatus === 'cancelled' && $oldStatus === 'completed') {
                foreach ($order->spareParts as $part) {
                    $part->stock += $part->pivot->quantity;
                    $part->save();

                    // Registrar movimiento de inventario por devolución/retorno
                    \App\Models\InventoryMovement::create([
                        'spare_part_id' => $part->id,
                        'user_id'       => auth()->id() ?: $order->user_id,
                        'type'          => 'in',
                        'quantity'      => $part->pivot->quantity,
                        'reason'        => "Devolución por cancelación de orden {$order->order_number}",
                        'reference'     => $order->order_number,
                    ]);
                }
            }

            $order->status = $newStatus;
            $order->save();
            $order->refresh();

            // Registrar transición en la bitácora histórica
            \App\Models\WorkOrderHistory::create([
                'work_order_id' => $order->id,
                'user_id' => auth()->id(),
                'from_status' => $oldStatus,
                'to_status' => \App\Enums\WorkOrderStatus::tryFrom($newStatus) ?: $newStatus,
                'notes' => 'Estado actualizado a: ' . (\App\Enums\WorkOrderStatus::tryFrom($newStatus)?->label() ?? $newStatus),
            ]);

            return $order;
        });

        // Notificar orden completada
        if ($newStatus === 'completed' && $oldStatus !== 'completed') {
            $users = \App\Models\User::whereIn('role', ['admin', 'recepcionista'])->where('is_active', true)->get();
            \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\OrderCompletedNotification($order));
        }

        return $order;
    }

    public function delete(WorkOrder $order): void
    {
        if (! $order->isPending()) {
            throw new \Exception('Solo se pueden eliminar órdenes pendientes.');
        }

        $order->delete();
    }

    private function generateOrderNumber(): string
    {
        $year = date('Y');
        $month = date('m');

        $lastOrder = WorkOrder::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastOrder && preg_match('/OT-' . $year . $month . '-(\d+)/', $lastOrder->order_number, $matches)) {
            $newNumber = str_pad((int) $matches[1] + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "OT-{$year}{$month}-{$newNumber}";
    }

    public function getStatistics(): array
    {
        return [
            'total' => WorkOrder::count(),
            'pending' => WorkOrder::where('status', 'pending')->count(),
            'in_progress' => WorkOrder::where('status', 'in_progress')->count(),
            'completed' => WorkOrder::where('status', 'completed')->count(),
            'delivered' => WorkOrder::where('status', 'delivered')->count(),
            'cancelled' => WorkOrder::where('status', 'cancelled')->count(),
            'revenue' => WorkOrder::where('status', 'delivered')->sum('total'),
        ];
    }
}
