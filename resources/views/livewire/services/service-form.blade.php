<div>
    @if($open)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="w-full max-w-lg bg-gray-900 border border-gray-800 shadow-2xl rounded-2xl">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-800">
                <h2 class="text-xl font-bold text-white">
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
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all"
                        autocomplete="off">
                    @error('name')
                    <span class="mt-1 text-xs text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Descripción -->
                <div>
                    <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                        Descripción
                    </label>
                    <textarea wire:model="description" rows="4" placeholder="Descripción detallada del servicio..."
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all resize-none"></textarea>
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
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 pl-8 pr-3.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all" />
                    </div>
                    @error('price')
                    <span class="mt-1 text-xs text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Estado Activo -->
                <div class="flex items-center gap-3 pt-2">
                    <label class="flex items-center gap-2 text-sm text-gray-400 cursor-pointer">
                        <input type="checkbox" wire:model="active"
                            class="text-orange-500 bg-gray-800 border-gray-700 rounded focus:ring-orange-500 focus:ring-offset-0">
                        <span>Servicio activo</span>
                    </label>
                    <span class="text-xs text-gray-500">
                        (Los servicios inactivos no aparecerán en las órdenes de trabajo)
                    </span>
                </div>

            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-800">
                <button wire:click="close"
                    class="px-4 py-2.5 text-sm font-semibold text-gray-400 border border-gray-700 rounded-lg hover:bg-gray-800 hover:text-white transition-all">
                    Cancelar
                </button>
                <button wire:click="save" wire:loading.attr="disabled"
                    class="px-4 py-2.5 text-sm font-semibold text-white bg-orange-600 hover:bg-orange-700 rounded-lg transition-all shadow-lg hover:shadow-orange-600/25 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="save">
                        {{ $serviceId ? 'Actualizar' : 'Guardar' }}
                    </span>
                    <span wire:loading wire:target="save">
                        Guardando...
                    </span>
                </button>
            </div>

        </div>
    </div>
    @endif
</div>