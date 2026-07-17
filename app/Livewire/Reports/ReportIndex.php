<?php

namespace App\Livewire\Reports;

use App\Models\Client;
use App\Models\Service;
use App\Models\SparePart;
use App\Models\User;
use App\Models\WorkOrder;
use App\Traits\Exportable;
use Livewire\Component;

class ReportIndex extends Component
{
    use Exportable;

    public $reportType = 'orders';

    public $dateFrom = '';

    public $dateTo = '';

    public $status = 'all';

    public function exportOrders()
    {
        $query = WorkOrder::with(['client', 'vehicle', 'mechanic']);

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        $orders = $query->get();

        $headers = [
            '# Orden', 'Cliente', 'Vehículo', 'Mecánico',
            'Fecha Ingreso', 'Fecha Entrega', 'Estado',
            'Subtotal', 'IVA', 'Total',
        ];

        $data = $orders->map(function ($order) {
            return [
                $order->order_number,
                $order->client->name.' '.$order->client->last_name,
                $order->vehicle->plate.' - '.$order->vehicle->brand.' '.$order->vehicle->model,
                $order->mechanic->name ?? 'No asignado',
                $order->created_at->format('d/m/Y H:i'),
                $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') : '-',
                $order->status_label,
                $order->subtotal,
                $order->tax,
                $order->total,
            ];
        });

        $filename = 'ordenes_'.now()->format('Y-m-d_His');

        return $this->exportToCSV($data, $headers, $filename);
    }

    public function exportInventory()
    {
        $products = SparePart::where('is_active', true)->get();

        $headers = [
            'SKU', 'Nombre', 'Categoría', 'Unidad',
            'Precio Venta', 'Precio Compra', 'Stock',
            'Stock Mínimo', 'Ubicación', 'Proveedor',
        ];

        $data = $products->map(function ($product) {
            return [
                $product->sku ?? 'N/A',
                $product->name,
                $product->category_label,
                $product->unit,
                $product->unit_price,
                $product->purchase_price,
                $product->stock,
                $product->minimum_stock,
                $product->location ?? '-',
                $product->supplier ?? '-',
            ];
        });

        $filename = 'inventario_'.now()->format('Y-m-d_His');

        return $this->exportToCSV($data, $headers, $filename);
    }

    public function exportClients()
    {
        $clients = Client::withCount('vehicles')->get();

        $headers = ['ID', 'Nombre', 'Email', 'Teléfono', 'Dirección', 'Vehículos', 'Fecha Registro'];

        $data = $clients->map(function ($client) {
            return [
                $client->id,
                $client->name.' '.$client->last_name,
                $client->email ?? '-',
                $client->phone ?? '-',
                trim(implode(' ', array_filter([$client->street, $client->avenue, $client->number]))) ?: '-',
                $client->vehicles_count,
                $client->created_at->format('d/m/Y'),
            ];
        });

        $filename = 'clientes_'.now()->format('Y-m-d_His');

        return $this->exportToCSV($data, $headers, $filename);
    }

    public function exportServices()
    {
        $services = Service::all();

        $headers = ['ID', 'Nombre', 'Descripción', 'Precio', 'Estado'];

        $data = $services->map(function ($service) {
            return [
                $service->id,
                $service->name,
                $service->description ?? '-',
                $service->price,
                $service->active ? 'Activo' : 'Inactivo',
            ];
        });

        $filename = 'servicios_'.now()->format('Y-m-d_His');

        return $this->exportToCSV($data, $headers, $filename);
    }

    public function exportUsers()
    {
        $users = User::all();

        $headers = ['ID', 'Nombre', 'Email', 'Rol', 'Estado', 'Fecha Registro'];

        $data = $users->map(function ($user) {
            return [
                $user->id,
                $user->name,
                $user->email,
                $user->getRoleName(),
                ! $user->trashed() ? 'Activo' : 'Inactivo',
                $user->created_at->format('d/m/Y'),
            ];
        });

        $filename = 'usuarios_'.now()->format('Y-m-d_His');

        return $this->exportToCSV($data, $headers, $filename);
    }

    public function render()
    {
        $statuses = [
            'all' => 'Todos',
            'pending' => 'Pendientes',
            'in_progress' => 'En Progreso',
            'completed' => 'Completados',
            'delivered' => 'Entregados',
            'cancelled' => 'Cancelados',
        ];

        return view('livewire.reports.report-index', [
            'statuses' => $statuses,
        ]);
    }
}
