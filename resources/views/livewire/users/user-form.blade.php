<div>
    @if($open)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="w-full max-w-lg bg-gray-900 border border-gray-800 shadow-2xl rounded-2xl">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-800">
                <h2 class="text-xl font-bold text-white">
                    {{ $userId ? 'Editar Usuario' : 'Nuevo Usuario' }}
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
                    <input wire:model="name" type="text" placeholder="Nombre completo"
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all"
                        autocomplete="off">
                    @error('name')
                    <span class="mt-1 text-xs text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                        Email *
                    </label>
                    <input wire:model="email" type="email" placeholder="usuario@ejemplo.com"
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all"
                        autocomplete="off">
                    @error('email')
                    <span class="mt-1 text-xs text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div>
                    <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                        Contraseña {{ $userId ? '(dejar en blanco para mantener)' : '*' }}
                    </label>
                    <input wire:model="password" type="password" placeholder="********"
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all">
                    @error('password')
                    <span class="mt-1 text-xs text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirmar Contraseña -->
                <div>
                    <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                        Confirmar Contraseña {{ $userId ? '' : '*' }}
                    </label>
                    <input wire:model="password_confirmation" type="password" placeholder="********"
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all">
                </div>

                <!-- Rol -->
                <div>
                    <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                        Rol *
                    </label>
                    <select wire:model="role"
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all">
                        @foreach($roles as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('role')
                    <span class="mt-1 text-xs text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Información de roles -->
                <div class="p-3 mt-2 rounded-lg bg-gray-800/50">
                    <p class="text-xs text-gray-400">
                        <span class="font-semibold text-orange-400">Administrador:</span> Acceso total al sistema<br>
                        <span class="font-semibold text-orange-400">Recepcionista:</span> Gestión de clientes, vehículos
                        y órdenes<br>
                        <span class="font-semibold text-orange-400">Mecánico:</span> Solo ver órdenes asignadas y
                        actualizar diagnósticos
                    </p>
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
                        {{ $userId ? 'Actualizar' : 'Guardar' }}
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