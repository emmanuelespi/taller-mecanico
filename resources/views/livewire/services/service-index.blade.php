<div>
    <!-- HEADER -->
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div class="relative flex-1 max-w-md">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.35-4.35" />
                </svg>
            </span>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar servicio..."
                class="w-full bg-card border border-border rounded-lg py-2.5 pl-10 pr-4 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all" />
        </div>

        <div class="flex items-center gap-3">
            <label class="flex items-center gap-2 text-sm text-gray-400">
                <input type="checkbox" wire:model="showOnlyActive" class="rounded border-border bg-card text-accent">
                <span>Mostrar solo activos</span>
            </label>

            <button wire:click="$dispatchTo('services.service-form', 'openModal')"
                class="flex items-center gap-2 bg-accent hover:bg-orange-600 text-white font-semibold text-sm px-4 py-2.5 rounded-lg transition-all shadow-[0_4px_12px_rgba(249,115,22,0.25)] hover:-translate-y-0.5">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Nuevo Servicio
            </button>
        </div>
    </div>

    <!-- TABLE -->
    <div class="overflow-hidden border bg-card border-border rounded-xl">
        <table class="w-full">
            <thead>
                <tr class="border-b border-border">
                    <th class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">ID
                    </th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">
                        Nombre</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">
                        Descripción</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">
                        Precio</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">
                        Estado</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">
                        Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                <tr class="transition-colors hover:bg-hover">
                    <td class="px-5 py-3.5 text-xs text-gray-500 border-b border-border/50">{{ $service->id }}</td>
                    <td class="px-5 py-3.5 text-sm font-medium text-white border-b border-border/50">
                        {{ $service->name }}
                    </td>
                    <td class="px-5 py-3.5 text-sm text-gray-400 border-b border-border/50">
                        {{ Str::limit($service->description, 50) }}
                    </td>
                    <td class="px-5 py-3.5 text-sm font-semibold text-accent border-b border-border/50">
                        ${{ number_format($service->price, 2) }}
                    </td>
                    <td class="px-5 py-3.5 border-b border-border/50">
                        <span
                            class="inline-flex items-center gap-1.5 px-2 py-1 text-xs font-medium rounded-full
                            {{ $service->active ? 'bg-green-500/10 text-green-400' : 'bg-gray-500/10 text-gray-400' }}">
                            <span
                                class="w-1.5 h-1.5 rounded-full {{ $service->active ? 'bg-green-400' : 'bg-gray-400' }}"></span>
                            {{ $service->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 border-b border-border/50">
                        <div class="flex items-center gap-2">
                            <!-- Editar -->
                            <button
                                wire:click="$dispatchTo('services.service-form', 'openModal', { serviceId: {{ $service->id }} })"
                                class="flex items-center justify-center w-8 h-8 transition-colors rounded-lg bg-accent/10 text-accent hover:bg-accent/20">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                            </button>

                            <!-- Activar/Desactivar -->
                            <button wire:click="toggleActive({{ $service->id }})"
                                class="flex items-center justify-center w-8 h-8 transition-colors rounded-lg
                                    {{ $service->active ? 'bg-yellow-500/10 text-yellow-400 hover:bg-yellow-500/20' : 'bg-green-500/10 text-green-400 hover:bg-green-500/20' }}">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    @if($service->active)
                                    <path
                                        d="M18.364 5.636L5.636 18.364M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" />
                                    @else
                                    <path d="M5 12h14M12 5l7 7-7 7" />
                                    @endif
                                </svg>
                            </button>

                            <!-- Eliminar -->
                            <button wire:click="openDeleteModal({{ $service->id }})"
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
                    <td colspan="6" class="px-5 py-12 text-sm text-center text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <svg class="w-12 h-12 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5">
                                <path d="M20 7L4 7M16 3L8 3M12 11v6M9 14h6" />
                                <path d="M5 7L5 19a2 2 0 002 2h10a2 2 0 002-2V7" />
                            </svg>
                            <span>No hay servicios registrados aún.</span>
                            <button wire:click="$dispatchTo('services.service-form', 'openModal')"
                                class="mt-2 text-sm text-accent hover:text-orange-400">
                                Crear el primer servicio
                            </button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- PAGINATION -->
        @if($services->hasPages())
        <div class="px-5 py-4 border-t border-border">
            {{ $services->links() }}
        </div>
        @endif
    </div>

    <!-- MODAL DE CONFIRMACIÓN -->
    <x-confirm-modal :show="$showConfirmModal" title="¿Eliminar servicio?"
        message="Esta acción eliminará el servicio permanentemente. ¿Estás seguro?" confirm-action="deleteService"
        cancel-action="cancelDelete" type="danger" />

    <!-- FORMULARIO DE SERVICIO -->
    <livewire:services.service-form />
</div>