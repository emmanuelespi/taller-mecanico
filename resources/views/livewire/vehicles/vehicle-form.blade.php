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
                    {{ $vehicleId ? 'Editar Vehículo' : 'Nuevo Vehículo' }}
                </h2>
                <button wire:click="close" class="text-gray-500 transition-colors hover:text-white">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="px-6 py-5 space-y-4">

                <!-- Cliente -->
                <div>
                    <label
                        class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">Cliente
                        *</label>
                    <select wire:model="client_id"
                        class="w-full bg-base border border-border rounded-lg py-2.5 px-3.5 text-sm text-white focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                        <option value="">Selecciona un cliente</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }} {{ $client->last_name }}</option>
                        @endforeach
                    </select>
                    @error('client_id') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                </div>

                <!-- Placa -->
                <div>
                    <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">Placa
                        *</label>
                    <input wire:model="plate" type="text" placeholder="P-123ABC"
                        class="w-full bg-base border border-border rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all uppercase" />
                    @error('plate') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                </div>

                <!-- Marca y Modelo -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">Marca
                            *</label>
                        <input wire:model="brand" type="text" placeholder="Toyota"
                            class="w-full bg-base border border-border rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all" />
                        @error('brand') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label
                            class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">Modelo
                            *</label>
                        <input wire:model="model" type="text" placeholder="Corolla"
                            class="w-full bg-base border border-border rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all" />
                        @error('model') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Año y Color -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">Año
                            *</label>
                        <input wire:model="year" type="number" placeholder="{{ date('Y') }}"
                            class="w-full bg-base border border-border rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all" />
                        @error('year') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label
                            class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">Color
                            *</label>
                        <input wire:model="color" type="text" placeholder="Blanco"
                            class="w-full bg-base border border-border rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all" />
                        @error('color') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                    </div>
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
                    {{ $vehicleId ? 'Actualizar' : 'Guardar' }}
                </button>
            </div>

        </div>
    </div>
    @endif
</div>