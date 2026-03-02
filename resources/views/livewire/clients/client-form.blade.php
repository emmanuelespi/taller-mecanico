<div>
    @if($open)
    <!-- BACKDROP -->
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">

    <!-- MODAL -->
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-lg border shadow-2xl bg-card border-border rounded-2xl">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-border">
                <h2 class="text-xl font-bold font-rajdhani">
                    {{ $clientId ? 'Editar Cliente' : 'Nuevo Cliente' }}
                </h2>
                <button wire:click="close" class="text-gray-500 transition-colors hover:text-white">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="px-6 py-5 space-y-4">

                <!-- Nombre y Apellido -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">Nombre
                            *</label>
                        <input wire:model="name" type="text" placeholder="Juan"
                            class="w-full bg-base border border-border rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all" />
                        @error('name') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label
                            class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">Apellido
                            *</label>
                        <input wire:model="last_name" type="text" placeholder="Pérez"
                            class="w-full bg-base border border-border rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all" />
                        @error('last_name') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Teléfono y Email -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">Teléfono
                            *</label>
                        <input wire:model="phone" type="text" placeholder="5512345678"
                            class="w-full bg-base border border-border rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all" />
                        @error('phone') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label
                            class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">Email</label>
                        <input wire:model="email" type="email" placeholder="juan@correo.com"
                            class="w-full bg-base border border-border rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all" />
                        @error('email') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Dirección -->
                <div class="pt-4 border-t border-border">
                    <p class="text-[11px] font-semibold tracking-widest uppercase text-gray-500 mb-3">Dirección</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">Calle</label>
                            <input wire:model="street" type="text" placeholder="Calle Principal"
                                class="w-full bg-base border border-border rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all" />
                        </div>
                        <div>
                            <label
                                class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">Avenida</label>
                            <input wire:model="avenue" type="text" placeholder="Avenida Central"
                                class="w-full bg-base border border-border rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all" />
                        </div>
                        <div>
                            <label
                                class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">Número</label>
                            <input wire:model="number" type="text" placeholder="123"
                                class="w-full bg-base border border-border rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all" />
                        </div>
                        <div>
                            <label
                                class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">Código
                                Postal</label>
                            <input wire:model="postal_code" type="text" placeholder="01000"
                                class="w-full bg-base border border-border rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all" />
                        </div>
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
                    {{ $clientId ? 'Actualizar' : 'Guardar' }}
                </button>
            </div>

        </div>
    </div>
    @endif
</div>
