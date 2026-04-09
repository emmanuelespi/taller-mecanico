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
                class="bg-gray-800 border border-gray-700 rounded-lg py-2.5 pl-10 pr-4 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all w-72" />
            <!-- Spinner de carga -->
            <div wire:loading wire:target="search" class="absolute right-3.5 top-1/2 -translate-y-1/2">
                <svg class="w-4 h-4 text-gray-400 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                </svg>
            </div>
        </div>
        <button wire:click="$dispatchTo('vehicles.vehicle-form', 'openModal')"
            class="flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white font-semibold text-sm px-4 py-2.5 rounded-lg transition-all shadow-lg hover:shadow-orange-600/25 hover:-translate-y-0.5">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M12 5v14M5 12h14" />
            </svg>
            Nuevo Vehículo
        </button>
    </div>

    <!-- TABLE -->
    <div class="overflow-hidden bg-gray-900 border border-gray-800 rounded-xl">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-800 bg-gray-900/50">
                    <th class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">ID</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">Placa</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">Cliente</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">Marca / Modelo</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">Año</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">Color</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vehicles as $vehicle)
                <tr class="transition-colors border-b border-gray-800 hover:bg-gray-800/50">
                    <td class="px-5 py-3.5 text-xs text-gray-500">{{ $vehicle->id }}</td>
                    <td class="px-5 py-3.5">
                        <span class="font-rajdhani font-bold text-sm bg-gray-800 border border-gray-700 px-2 py-0.5 rounded tracking-wider text-white">
                            {{ $vehicle->plate }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-sm font-medium text-white">
                        {{ $vehicle->client->name }} {{ $vehicle->client->last_name }}
                    </td>
                    <td class="px-5 py-3.5 text-sm">
                        <span class="text-white">{{ $vehicle->brand }}</span>
                        <span class="text-gray-400"> / {{ $vehicle->model }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-sm text-gray-400">{{ $vehicle->year }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 border border-gray-700 rounded-full" style="background-color: {{ $vehicle->color }}"></div>
                            <span class="text-sm text-gray-400">{{ $vehicle->color }}</span>
                            @if($vehicle->hasActiveWorkOrder())
                            <span class="ml-2 text-[10px] px-1.5 py-0.5 bg-yellow-500/20 text-yellow-400 rounded-full">En taller</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2">
                            <button wire:click="$dispatchTo('vehicles.vehicle-form', 'openModal', { vehicleId: {{ $vehicle->id }} })"
                                class="flex items-center justify-center w-8 h-8 text-blue-400 transition-colors rounded-lg bg-blue-500/10 hover:bg-blue-500/20">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                            </button>
                            <button wire:click="openDeleteModal({{ $vehicle->id }})"
                                class="flex items-center justify-center w-8 h-8 text-red-400 transition-colors rounded-lg bg-red-500/10 hover:bg-red-500/20">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                        <div class="flex flex-col items-center gap-3">
                            <svg class="w-12 h-12 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M20 7L4 7M16 3L8 3M12 11v6M9 14h6" />
                                <path d="M5 7L5 19a2 2 0 002 2h10a2 2 0 002-2V7" />
                            </svg>
                            <span>No hay vehículos registrados aún.</span>
                            <button wire:click="$dispatchTo('vehicles.vehicle-form', 'openModal')" class="mt-2 text-sm text-orange-400 hover:text-orange-300">
                                Crear el primer vehículo
                            </button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- PAGINATION -->
        @if($vehicles->hasPages())
        <div class="px-5 py-4 border-t border-gray-800">
            {{ $vehicles->links() }}
        </div>
        @endif
    </div>

    <x-confirm-modal :show="$showConfirmModal" title="¿Eliminar vehículo?"
        message="Esta acción eliminará el vehículo permanentemente." confirm-action="deleteVehicle"
        cancel-action="cancelDelete" />

    <livewire:vehicles.vehicle-form />
</div>
