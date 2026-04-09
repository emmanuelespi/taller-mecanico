<?php

namespace App\Livewire\Dashboard;

use App\Models\Client;
use App\Models\SparePart;
use App\Models\User;
use App\Models\WorkOrder;
use Livewire\Component;

class DashboardIndex extends Component
{
    public function render()
    {
        $totalActiveOrders = WorkOrder::whereIn('status', ['pending', 'in_progress'])->count();
        $totalClients = Client::count();
        $monthlyRevenue = WorkOrder::where('status', 'delivered')
            ->whereMonth('delivered_at', now()->month)
            ->whereYear('delivered_at', now()->year)
            ->sum('total');

        $lowStockParts = SparePart::whereColumn('stock', '<=', 'minimum_stock')
            ->where('is_active', true)
            ->orderBy('stock', 'asc')
            ->get();

        $recentOrders = WorkOrder::with(['client', 'vehicle', 'mechanic'])
            ->latest()
            ->take(5)
            ->get();

        $ordersByStatus = [
            'pending' => WorkOrder::where('status', 'pending')->count(),
            'in_progress' => WorkOrder::where('status', 'in_progress')->count(),
            'completed' => WorkOrder::where('status', 'completed')->count(),
            'delivered' => WorkOrder::where('status', 'delivered')->count(),
            'cancelled' => WorkOrder::where('status', 'cancelled')->count(),
        ];

        $monthlyOrders = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyOrders[] = WorkOrder::whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->sum('total');
        }

        $monthlyRevenueData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyRevenueData[] = WorkOrder::where('status', 'delivered')
                ->whereMonth('delivered_at', $i)
                ->whereYear('delivered_at', now()->year)
                ->sum('total');
        }

        $topMechanics = User::where('role', 'mecanico')
            ->withCount(['assignedWorkOrders as completed_count' => function ($query) {
                $query->where('status', 'delivered');
            }])
            ->orderBy('completed_count', 'desc')
            ->limit(5)
            ->get();

        $recentLowStock = $lowStockParts->take(5);

        return view('livewire.dashboard.dashboard-index', [
            'totalActiveOrders' => $totalActiveOrders,
            'totalClients' => $totalClients,
            'monthlyRevenue' => $monthlyRevenue,
            'lowStockParts' => $lowStockParts,
            'recentOrders' => $recentOrders,
            'ordersByStatus' => $ordersByStatus,
            'monthlyOrders' => $monthlyOrders,
            'monthlyRevenueData' => $monthlyRevenueData,
            'topMechanics' => $topMechanics,
            'recentLowStock' => $recentLowStock,
        ]);
    }
}
