<div>
    <!-- STATS CARDS -->
    <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Órdenes Activas -->
        <div
            class="bg-gray-900 border border-gray-800 rounded-xl p-5 relative overflow-hidden hover:border-orange-500 transition-all hover:-translate-y-0.5">
            <div class="absolute top-0 left-0 right-0 h-0.5 bg-orange-500"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-orange-500/10">
                    <svg class="w-5 h-5 text-orange-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-white">{{ $totalActiveOrders }}</div>
            <div class="mt-1 text-xs font-medium text-gray-400">Órdenes Activas</div>
        </div>

        <!-- Clientes Registrados -->
        <div
            class="bg-gray-900 border border-gray-800 rounded-xl p-5 relative overflow-hidden hover:border-blue-500 transition-all hover:-translate-y-0.5">
            <div class="absolute top-0 left-0 right-0 h-0.5 bg-blue-500"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-blue-500/10">
                    <svg class="w-5 h-5 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 00-3-3.87" />
                        <path d="M16 3.13a4 4 0 010 7.75" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-white">{{ $totalClients }}</div>
            <div class="mt-1 text-xs font-medium text-gray-400">Clientes Registrados</div>
        </div>

        <!-- Ingresos del Mes -->
        <div
            class="bg-gray-900 border border-gray-800 rounded-xl p-5 relative overflow-hidden hover:border-green-500 transition-all hover:-translate-y-0.5">
            <div class="absolute top-0 left-0 right-0 h-0.5 bg-green-500"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-green-500/10">
                    <svg class="w-5 h-5 text-green-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path
                            d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-green-400">${{ number_format($monthlyRevenue, 2) }}</div>
            <div class="mt-1 text-xs font-medium text-gray-400">Ingresos del Mes</div>
        </div>

        <!-- Repuestos Bajo Stock -->
        <div
            class="bg-gray-900 border border-gray-800 rounded-xl p-5 relative overflow-hidden hover:border-red-500 transition-all hover:-translate-y-0.5">
            <div class="absolute top-0 left-0 right-0 h-0.5 bg-red-500"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-red-500/10">
                    <svg class="w-5 h-5 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path
                            d="M20.54 5.23l-1.39-1.68C18.88 3.21 18.47 3 18 3H6c-.47 0-.88.21-1.16.55L3.46 5.23C3.17 5.57 3 6.02 3 6.5V19c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6.5c0-.48-.17-.93-.46-1.27z" />
                        <path d="M12 17V9M9 12l3-3 3 3" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-red-400">{{ $lowStockParts->count() }}</div>
            <div class="mt-1 text-xs font-medium text-gray-400">Repuestos Bajo Stock</div>
        </div>
    </div>

    <!-- DISTRIBUCIÓN DE ÓRDENES -->
    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
        <div class="overflow-hidden bg-gray-900 border border-gray-800 rounded-xl">
            <div class="px-5 py-4 border-b border-gray-800">
                <h3 class="text-lg font-semibold text-white">Estado de Órdenes</h3>
            </div>
            <div class="p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-400">Pendientes</span>
                    <span class="text-lg font-semibold text-yellow-400">{{ $ordersByStatus['pending'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-400">En Progreso</span>
                    <span class="text-lg font-semibold text-blue-400">{{ $ordersByStatus['in_progress'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-400">Completadas</span>
                    <span class="text-lg font-semibold text-green-400">{{ $ordersByStatus['completed'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-400">Entregadas</span>
                    <span class="text-lg font-semibold text-purple-400">{{ $ordersByStatus['delivered'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-400">Canceladas</span>
                    <span class="text-lg font-semibold text-red-400">{{ $ordersByStatus['cancelled'] }}</span>
                </div>
            </div>
        </div>

        <!-- Top Mecánicos -->
        <div class="overflow-hidden bg-gray-900 border border-gray-800 rounded-xl">
            <div class="px-5 py-4 border-b border-gray-800">
                <h3 class="text-lg font-semibold text-white">Top Mecánicos</h3>
            </div>
            <div class="p-5 space-y-3">
                @forelse($topMechanics as $mechanic)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-white">{{ $mechanic->name }}</span>
                    <span class="text-sm font-semibold text-orange-400">{{ $mechanic->completed_count }} órdenes</span>
                </div>
                @empty
                <p class="text-sm text-gray-500">No hay datos disponibles</p>
                @endforelse
            </div>
        </div>

        <!-- Órdenes por Mes (Resumen) -->
        <div class="overflow-hidden bg-gray-900 border border-gray-800 rounded-xl">
            <div class="px-5 py-4 border-b border-gray-800">
                <h3 class="text-lg font-semibold text-white">Resumen del Año</h3>
            </div>
            <div class="p-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-400">Total Órdenes</span>
                    <span class="text-lg font-semibold text-white">{{ array_sum($monthlyOrders) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-400">Total Ingresos</span>
                    <span class="text-lg font-semibold text-green-400">${{ number_format(array_sum($monthlyRevenueData),
                        2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ÓRDENES RECIENTES Y ALERTAS -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        <!-- ÓRDENES RECIENTES -->
        <div class="overflow-hidden bg-gray-900 border border-gray-800 rounded-xl">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-800">
                <h3 class="text-lg font-semibold text-white">Órdenes Recientes</h3>
                <a href="{{ route('work-orders.index') }}" wire:navigate
                    class="text-xs font-semibold text-orange-400 hover:text-orange-300">
                    Ver todas →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-800/50">
                        <tr>
                            <th
                                class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">
                                # Orden</th>
                            <th
                                class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">
                                Cliente</th>
                            <th
                                class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">
                                Vehículo</th>
                            <th
                                class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">
                                Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse($recentOrders as $order)
                        <tr class="transition-colors cursor-pointer hover:bg-gray-800/50"
                            onclick="window.location.href='{{ route('work-orders.show', $order) }}'">
                            <td class="px-5 py-3.5 text-sm font-mono font-semibold text-orange-400">
                                {{ $order->order_number }}
                            </td>
                            <td class="px-5 py-3.5 text-sm text-white">
                                {{ $order->client->name }} {{ $order->client->last_name }}
                            </td>
                            <td class="px-5 py-3.5 text-sm text-gray-400">
                                {{ $order->vehicle->plate }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2 py-1 text-xs font-medium rounded-full {{ $order->status_badge }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-{{ $order->status_color }}-400"></span>
                                    {{ $order->status_label }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-sm text-center text-gray-500">
                                No hay órdenes registradas aún.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ALERTAS DE STOCK -->
        <div class="overflow-hidden bg-gray-900 border border-gray-800 rounded-xl">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-800">
                <h3 class="text-lg font-semibold text-white">Alertas de Stock</h3>
                <a href="{{ route('inventory.index') }}" wire:navigate
                    class="text-xs font-semibold text-orange-400 hover:text-orange-300">
                    Ver inventario →
                </a>
            </div>
            <div class="divide-y divide-gray-800">
                @forelse($recentLowStock as $part)
                <div class="flex items-center justify-between px-5 py-3.5 hover:bg-gray-800/50 transition-colors">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-white">{{ $part->name }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">Mínimo: {{ $part->minimum_stock }} unidades</div>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-bold {{ $part->stock <= 0 ? 'text-red-400' : 'text-yellow-400' }}">
                            {{ $part->stock }}
                        </div>
                        <div class="text-xs text-gray-500">unidades</div>
                    </div>
                </div>
                @empty
                <div class="px-5 py-8 text-sm text-center text-gray-500">
                    No hay alertas de stock.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
