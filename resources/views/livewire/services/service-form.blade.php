<div>
    @if($open)
    <!-- BACKDROP -->
    <div class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm" wire:click="close"></div>

    <!-- MODAL -->
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-lg border shadow-2xl bg-card border-border rounded-2xl">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-border">
                <h2 class="text-xl font-bold font-rajdhani">
                    {{ $serviceId ? 'Editar Servicio' : 'Nuevo Servicio' }}
                </h2>
                <button wire:click="close" class="text-gray-500 transition-colors hover:text-white">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="px-6 py-5 space-y-4">

                <!-- Nombre -->
                <div>
                    <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                        Nombre *
                    </label>
                    <input wire:model="name" type="text" placeholder="Ej: Afinación Mayor, Cambio de Aceite, etc."
                        class="w-full bg-base border border-border rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all" />
                    @error('name')
                    <span class="mt-1 text-xs text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Descripción -->
                <div>
                    <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                        Descripción
                    </label>
                    <textarea wire:model="description" rows="3" placeholder="Descripción detallada del servicio..."
                        class="w-full bg-base border border-border rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all resize-none"></textarea>
                    @error('description')
                    <span class="mt-1 text-xs text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Precio -->
                <div>
                    <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                        Precio *
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                        <input wire:model="price" type="number" step="0.01" placeholder="0.00"
                            class="w-full bg-base border border-border rounded-lg py-2.5 pl-8 pr-3.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all" />
                    </div>
                    @error('price')
                    <span class="mt-1 text-xs text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Activo -->
                <div class="flex items-center gap-3 pt-2">
                    <label class="flex items-center gap-2 text-sm text-gray-400 cursor-pointer">
                        <input type="checkbox" wire:model="active"
                            class="w-4 h-4 rounded border-border bg-card text-accent focus:ring-accent/20 focus:ring-2">
                        <span>Servicio activo</span>
                    </label>
                    <span class="text-xs text-gray-500">
                        (Los servicios inactivos no aparecerán en las órdenes de trabajo)
                    </span>
                </div>

            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-border">
                <button wire:click="close"
                    class="px-4 py-2.5 text-sm font-semibold text-gray-400 border border-border rounded-lg hover:bg-hover hover:text-white transition-all">
                    Cancelar
                </button>
                <button wire:click="save"
                    class="px-4 py-2.5 text-sm font-semibold text-white bg-accent hover:bg-orange-600 rounded-lg transition-all shadow-[0_4px_12px_rgba(249,115,22,0.25)] hover:-translate-y-0.5">
                    {{ $serviceId ? 'Actualizar' : 'Guardar' }}
                </button>
            </div>

        </div>
    </div>
    @endif
</div>