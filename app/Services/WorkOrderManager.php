<?php

namespace App\Services;

use App\Models\SparePart;
use App\Models\WorkOrder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class WorkOrderManager
{
    public function getAll(string $search = '', string $status = 'all', string $dateFrom = '', string $dateTo = ''): LengthAwarePaginator
    {
        $query = WorkOrder::with('client', 'vehicle', 'mechanic');

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('vehicle', function ($q) use ($search) {
                        $q->where('plate', 'like', "%{$search}%");
                    });
            });
        }

        if ($dateFrom) {
            $query->whereDate('entry_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('entry_date', '<=', $dateTo);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);

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

            $order->calculateTotals();
        });
    }

    public function addSpareParts(WorkOrder $order, int $partId, int $quantity, float $unitPrice): void
    {
        DB::transaction(function () use ($order, $partId, $quantity, $unitPrice) {
            $part = SparePart::findOrFail($partId);

            if (! $part->hasStock($quantity)) {
                throw new \Exception("Stock insuficiente para {$part->name}. Disponible: {$part->stock}");
            }

            $order->spareParts()->attach($partId, [
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
            ]);

            $order->calculateTotals();
        });
    }

    public function removeService(WorkOrder $order, int $serviceId): void
    {
        DB::transaction(function () use ($order, $serviceId) {
            $order->services()->detach($serviceId);
            $order->calculateTotals();
        });
    }

    public function removeSparePart(WorkOrder $order, int $partId): void
    {
        DB::transaction(function () use ($order, $partId) {
            $order->spareParts()->detach($partId);
            $order->calculateTotals();
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

        if ($lastOrder && preg_match('/OT-'.$year.$month.'-(\d+)/', $lastOrder->order_number, $matches)) {
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
