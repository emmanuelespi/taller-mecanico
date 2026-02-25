<?php

namespace App\Livewire\Dashboard;

use App\Models\Client;
use App\Models\SparePart;
use App\Models\WorkOrder;
use Livewire\Component;

class DashboardIndex extends Component
{
    public function render()
    {
        return view('livewire.dashboard.dashboard-index', [
            'totalActiveOrders' => WorkOrder::whereIn('status', ['pending', 'in_progress'])->count(),
            'totalClients' => Client::count(),
            'lowStockParts' => SparePart::where('stock', '<=', 'minimum_stock')->get(),
            'recentOrders' => WorkOrder::with(['client', 'vehicle', 'mechanic'])
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }
}
