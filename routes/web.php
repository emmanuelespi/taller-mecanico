<?php

use App\Livewire\Clients\ClientIndex;
use App\Livewire\Dashboard\DashboardIndex;
use App\Livewire\Inventory\InventoryIndex;
use App\Livewire\Services\ServiceIndex;
use App\Livewire\SpareParts\SparePartIndex;
use App\Livewire\Users\UserIndex;
use App\Livewire\Vehicles\VehicleIndex;
use App\Livewire\WorkOrders\WorkOrderDetails;
use App\Livewire\WorkOrders\WorkOrderIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Todos los roles
    Route::get('dashboard', DashboardIndex::class)->name('dashboard');
    Route::get('work-orders', WorkOrderIndex::class)->name('work-orders.index');

    Route::get('work-orders/{order}', WorkOrderDetails::class)->name('work-orders.show');

    // Admin y Recepcionista
    Route::middleware(['role:admin,receptionist'])->group(function () {
        Route::get('clients', ClientIndex::class)->name('clients.index');
        Route::get('vehicles', VehicleIndex::class)->name('vehicles.index');
        Route::get('inventory', InventoryIndex::class)->name('inventory.index');
    });

    // Solo Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('services', ServiceIndex::class)->name('services.index');
        Route::get('spare-parts', SparePartIndex::class)->name('spare-parts.index');
        Route::get('users', UserIndex::class)->name('users.index');
    });
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
