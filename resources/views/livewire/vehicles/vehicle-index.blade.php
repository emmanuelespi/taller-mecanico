<div>
    <!-- HEADER -->
    <div class="flex items-center justify-between mb-6">
        <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.35-4.35" />
                </svg>
            </span>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar vehículo..."
                class="bg-card border border-border rounded-lg py-2.5 pl-10 pr-4 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all w-72" />
            <!-- Spinner de carga -->
            <div wire:loading wire:target="search" class="absolute right-3.5 top-1/2 -translate-y-1/2">
                <svg class="w-4 h-4 text-gray-400 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                </svg>
            </div>
        </div>
        <button wire:click="$dispatchTo('vehicles.vehicle-form', 'openModal')"
            class="flex items-center gap-2 bg-accent hover:bg-orange-600 text-white font-semibold text-sm px-4 py-2.5 rounded-lg transition-all shadow-[0_4px_12px_rgba(249,115,22,0.25)] hover:-translate-y-0.5">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M12 5v14M5 12h14" />
            </svg>
            Nuevo Vehículo
        </button>
    </div>

    <!-- TABLE -->
    <div class="overflow-hidden border bg-card border-border rounded-xl">
        <table class="w-full">
            <thead>
                <tr>
                    <th
                        class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500 border-b border-border">
                        #</th>
                    <th
                        class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500 border-b border-border">
                        Placa</th>
                    <th
                        class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500 border-b border-border">
                        Cliente</th>
                    <th
                        class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500 border-b border-border">
                        Marca / Modelo</th>
                    <th
                        class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500 border-b border-border">
                        Año</th>
                    <th
                        class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500 border-b border-border">
                        Color</th>
                    <th
                        class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500 border-b border-border">
                        Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vehicles as $vehicle)
                <tr class="transition-colors hover:bg-hover">
                    <td class="px-5 py-3.5 text-xs text-gray-500 border-b border-border/50">{{ $vehicle->id }}</td>
                    <td class="px-5 py-3.5 border-b border-border/50">
                        <span
                            class="font-rajdhani font-bold text-sm bg-base border border-border px-2 py-0.5 rounded tracking-wider">
                            {{ $vehicle->plate }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-sm font-medium border-b border-border/50">
                        {{ $vehicle->client->name }} {{ $vehicle->client->last_name }}
                    </td>
                    <td class="px-5 py-3.5 text-sm border-b border-border/50">
                        <span class="text-white">{{ $vehicle->brand }}</span>
                        <span class="text-gray-400"> / {{ $vehicle->model }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-sm text-gray-400 border-b border-border/50">{{ $vehicle->year }}</td>
                    <td class="px-5 py-3.5 border-b border-border/50">
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 border rounded-full border-border/50"
                                style="background-color: {{ $vehicle->color }}"></div>
                            <span class="text-sm text-gray-400">{{ $vehicle->color }}</span>
                            @if($vehicle->hasActiveWorkOrder())
                            <span
                                class="ml-2 text-[10px] px-1.5 py-0.5 bg-yellow-500/20 text-yellow-400 rounded-full">En
                                taller</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-5 py-3.5 border-b border-border/50">
                        <div class="flex items-center gap-2">
                            <button
                                wire:click="$dispatchTo('vehicles.vehicle-form', 'openModal', { vehicleId: {{ $vehicle->id }} })"
                                class="flex items-center justify-center w-8 h-8 transition-colors rounded-lg bg-accent/10 text-accent hover:bg-accent/20">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                            </button>
                            <button wire:click="openDeleteModal({{ $vehicle->id }})"
                                class="flex items-center justify-center w-8 h-8 text-red-400 transition-colors rounded-lg bg-red-500/10 hover:bg-red-500/20">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="3 6 5 6 21 6" />
                                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
                                    <path d="M10 11v6M14 11v6" />
                                    <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-sm text-center text-gray-500">
                        No hay vehículos registrados aún.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- PAGINATION -->
        @if($vehicles->hasPages())
        <div class="px-5 py-4 border-t border-border">
            {{ $vehicles->links() }}
        </div>
        @endif
    </div>

    <x-confirm-modal :show="$showConfirmModal" title="¿Eliminar vehículo?"
        message="Esta acción eliminará el vehículo permanentemente." confirm-action="deleteVehicle"
        cancel-action="cancelDelete" />

    <livewire:vehicles.vehicle-form />
</div>