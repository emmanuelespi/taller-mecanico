<div>
    <!-- STATS -->
    <div class="grid grid-cols-4 gap-4 mb-7">
        <div
            class="bg-card border border-border rounded-xl p-5 relative overflow-hidden hover:border-accent transition-all hover:-translate-y-0.5 group">
            <div class="absolute top-0 left-0 right-0 h-0.5 bg-accent"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-accent/10 text-accent">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            {{-- <div class="mb-1 text-4xl font-bold leading-none font-rajdhani">{{ $totalActiveOrders }}</div> --}}
            <div class="text-xs font-medium text-gray-400">Órdenes Activas</div>
        </div>

        <div
            class="bg-card border border-border rounded-xl p-5 relative overflow-hidden hover:border-blue-500 transition-all hover:-translate-y-0.5">
            <div class="absolute top-0 left-0 right-0 h-0.5 bg-blue-500"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center justify-center w-10 h-10 text-blue-400 rounded-xl bg-blue-500/10">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                    </svg>
                </div>
            </div>
            <div class="mb-1 text-4xl font-bold leading-none font-rajdhani">{{ $totalClients }}</div>
            <div class="text-xs font-medium text-gray-400">Clientes Registrados</div>
        </div>

        <div
            class="bg-card border border-border rounded-xl p-5 relative overflow-hidden hover:border-green-500 transition-all hover:-translate-y-0.5">
            <div class="absolute top-0 left-0 right-0 h-0.5 bg-green-500"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center justify-center w-10 h-10 text-green-400 rounded-xl bg-green-500/10">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path
                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                </div>
            </div>
            <div class="mb-1 text-4xl font-bold leading-none font-rajdhani">Q 0.00</div>
            <div class="text-xs font-medium text-gray-400">Ingresos del Mes</div>
        </div>

        <div
            class="bg-card border border-border rounded-xl p-5 relative overflow-hidden hover:border-red-500 transition-all hover:-translate-y-0.5">
            <div class="absolute top-0 left-0 right-0 h-0.5 bg-red-500"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center justify-center w-10 h-10 text-red-400 rounded-xl bg-red-500/10">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path
                            d="M20.54 5.23l-1.39-1.68C18.88 3.21 18.47 3 18 3H6c-.47 0-.88.21-1.16.55L3.46 5.23C3.17 5.57 3 6.02 3 6.5V19c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6.5c0-.48-.17-.93-.46-1.27z" />
                        <path d="M12 17V9M9 12l3-3 3 3" />
                    </svg>
                </div>
            </div>
            <div class="mb-1 text-4xl font-bold leading-none font-rajdhani">{{ $lowStockParts->count() }}</div>
            <div class="text-xs font-medium text-gray-400">Repuestos Bajo Stock</div>
        </div>

    </div>

    <!-- BOTTOM GRID -->
    <div class="grid grid-cols-[1fr_340px] gap-4">

        <!-- ÓRDENES RECIENTES -->
        <div class="overflow-hidden border bg-card border-border rounded-xl">
            <div class="flex items-center justify-between px-5 py-4 border-b border-border">
                <span class="text-lg font-bold font-rajdhani">Órdenes Recientes</span>
                <a href="{{ route('work-orders.index') }}" wire:navigate
                    class="text-xs font-semibold text-accent hover:underline">Ver todas →</a>
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th
                            class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500 border-b border-border">
                            #</th>
                        <th
                            class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500 border-b border-border">
                            Cliente</th>
                        <th
                            class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500 border-b border-border">
                            Vehículo</th>
                        <th
                            class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500 border-b border-border">
                            Mecánico</th>
                        <th
                            class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500 border-b border-border">
                            Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr class="transition-colors cursor-pointer hover:bg-hover">
                        <td class="px-5 py-3.5 text-xs text-gray-500 border-b border-border/50">#{{ str_pad($order->id,
                            4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-5 py-3.5 text-sm border-b border-border/50">{{ $order->client->name }} {{
                            $order->client->last_name }}</td>
                        <td class="px-5 py-3.5 border-b border-border/50">
                            <span
                                class="font-rajdhani font-bold text-sm bg-base border border-border px-2 py-0.5 rounded tracking-wider">{{
                                $order->vehicle->plate }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-sm border-b border-border/50">{{ $order->mechanic->name }}</td>
                        <td class="px-5 py-3.5 border-b border-border/50">
                            @php
                            $badges = [
                            'pending' => 'bg-orange-500/10 text-orange-400',
                            'in_progress' => 'bg-blue-500/10 text-blue-400',
                            'finished' => 'bg-green-500/10 text-green-400',
                            'delivered' => 'bg-gray-500/10 text-gray-400',
                            ];
                            $labels = [
                            'pending' => 'Pendiente',
                            'in_progress' => 'En Proceso',
                            'finished' => 'Terminado',
                            'delivered' => 'Entregado',
                            ];
                            @endphp
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $badges[$order->status] ?? '' }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                {{ $labels[$order->status] ?? $order->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-sm text-center text-gray-500">No hay órdenes registradas
                            aún.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- STOCK ALERTS -->
        <div class="overflow-hidden border bg-card border-border rounded-xl">
            <div class="flex items-center justify-between px-5 py-4 border-b border-border">
                <span class="text-lg font-bold font-rajdhani">Alertas de Stock</span>
                <a href="{{ route('inventory.index') }}" wire:navigate
                    class="text-xs font-semibold text-accent hover:underline">Ver inventario →</a>
            </div>
            <div>
                @forelse($lowStockParts as $part)
                <div
                    class="flex items-center gap-3 px-5 py-3.5 border-b border-border/50 hover:bg-hover transition-colors cursor-pointer">
                    <div
                        class="w-2 h-2 rounded-full flex-shrink-0 {{ $part->stock <= 0 ? 'bg-red-500 shadow-[0_0_6px_#EF4444]' : 'bg-accent shadow-[0_0_6px_#F97316]' }}">
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium truncate">{{ $part->name }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">Mínimo: {{ $part->minimum_stock }} unidades</div>
                    </div>
                    <div
                        class="font-rajdhani font-bold text-xl {{ $part->stock <= 0 ? 'text-red-400' : 'text-accent' }}">
                        {{ $part->stock }}
                    </div>
                </div>
                @empty
                <div class="px-5 py-8 text-sm text-center text-gray-500">No hay alertas de stock.</div>
                @endforelse
            </div>
        </div>

    </div>
</div>
