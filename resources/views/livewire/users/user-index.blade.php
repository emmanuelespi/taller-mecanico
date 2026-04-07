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
            <input wire:model.live.debounce.300ms="search" type="text"
                placeholder="Buscar usuario por nombre o email..."
                class="w-full bg-gray-900 border border-gray-700 rounded-lg py-2.5 pl-10 pr-4 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all">
        </div>

        <div class="flex items-center gap-3">
            <select wire:model.live="roleFilter"
                class="bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-orange-500">
                <option value="all">Todos los roles</option>
                @foreach ($roles as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>

            <button wire:click="$dispatch('openUserModal', { userId: null })"
                class="flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white font-semibold text-sm px-4 py-2.5 rounded-lg transition-all shadow-lg hover:shadow-orange-600/25 hover:-translate-y-0.5">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Nuevo Usuario
            </button>
        </div>
    </div>

    <!-- TABLE -->
    <div class="overflow-hidden bg-gray-900 border border-gray-800 rounded-xl">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-800 bg-gray-900/50">
                    <th class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">ID
                    </th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">
                        Nombre</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">
                        Email</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">
                        Rol</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">
                        Estado</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold tracking-widest uppercase text-gray-500">
                        Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="transition-colors border-b border-gray-800 hover:bg-gray-800/50">
                        <td class="px-5 py-3.5 text-xs text-gray-500">{{ $user->id }}</td>
                        <td class="px-5 py-3.5 text-sm font-medium text-white">{{ $user->name }}</td>
                        <td class="px-5 py-3.5 text-sm text-gray-400">{{ $user->email }}</td>
                        <td class="px-5 py-3.5">
                            <span
                                class="inline-flex items-center gap-1.5 px-2 py-1 text-xs font-medium rounded-full
                {{ $user->isAdmin()
                    ? 'bg-purple-500/10 text-purple-400'
                    : ($user->isMechanic()
                        ? 'bg-blue-500/10 text-blue-400'
                        : 'bg-green-500/10 text-green-400') }}">
                                <span
                                    class="w-1.5 h-1.5 rounded-full
                    {{ $user->isAdmin() ? 'bg-purple-400' : ($user->isMechanic() ? 'bg-blue-400' : 'bg-green-400') }}"></span>
                                {{ $user->getRoleName() }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span
                                class="inline-flex items-center gap-1.5 px-2 py-1 text-xs font-medium rounded-full
                {{ $user->is_active ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400' }}">
                                <span
                                    class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2">
                                <!-- Editar -->
                                <button wire:click="$dispatch('openUserModal', { userId: {{ $user->id }} })"
                                    class="flex items-center justify-center w-8 h-8 text-blue-400 transition-colors rounded-lg bg-blue-500/10 hover:bg-blue-500/20"
                                    title="Editar usuario">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </button>

                                <!-- Activar/Desactivar -->
                                @if (auth()->id() !== $user->id)
                                    <button wire:click="toggleActive({{ $user->id }})"
                                        class="flex items-center justify-center w-8 h-8 transition-colors rounded-lg
                        {{ $user->is_active ? 'bg-yellow-500/10 text-yellow-400 hover:bg-yellow-500/20' : 'bg-green-500/10 text-green-400 hover:bg-green-500/20' }}"
                                        title="{{ $user->is_active ? 'Desactivar usuario' : 'Activar usuario' }}">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            @if ($user->is_active)
                                                <path
                                                    d="M18.364 5.636L5.636 18.364M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" />
                                            @else
                                                <path d="M5 12h14M12 5l7 7-7 7" />
                                            @endif
                                        </svg>
                                    </button>

                                    <!-- Eliminar -->
                                    <button wire:click="openDeleteModal({{ $user->id }})"
                                        class="flex items-center justify-center w-8 h-8 text-red-400 transition-colors rounded-lg bg-red-500/10 hover:bg-red-500/20"
                                        title="Eliminar usuario">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
                                            <path d="M10 11v6M14 11v6" />
                                            <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-sm text-center text-gray-500">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-12 h-12 text-gray-600" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.5">
                                    <path d="M12 4v1M12 8v1M12 12v1M12 16v1M12 20v1" />
                                    <path d="M4 12h1M8 12h1M12 12h1M16 12h1M20 12h1" />
                                    <circle cx="12" cy="12" r="10" />
                                </svg>
                                <span>No hay usuarios registrados aún.</span>
                                <button wire:click="$dispatch('openUserModal', { userId: null })"
                                    class="mt-2 text-sm text-orange-400 hover:text-orange-300">
                                    Crear el primer usuario
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- PAGINATION -->
        @if ($users->hasPages())
            <div class="px-5 py-4 border-t border-gray-800">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL DE CONFIRMACIÓN -->
    @if ($showConfirmModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
            <div class="w-full max-w-md bg-gray-900 border border-gray-800 shadow-2xl rounded-2xl">
                <div class="flex items-center gap-4 px-6 py-5">
                    <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-xl bg-red-500/10">
                        <svg class="w-5 h-5 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path
                                d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                            <line x1="12" y1="9" x2="12" y2="13" />
                            <line x1="12" y1="17" x2="12.01" y2="17" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">¿Eliminar usuario?</h2>
                        <p class="text-sm text-gray-400 mt-0.5">Esta acción eliminará el usuario permanentemente.
                            ¿Estás
                            seguro?</p>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-800">
                    <button wire:click="cancelDelete"
                        class="px-4 py-2.5 text-sm font-semibold text-gray-400 border border-gray-700 rounded-lg hover:bg-gray-800 hover:text-white transition-all">
                        Cancelar
                    </button>
                    <button wire:click="deleteUser"
                        class="px-4 py-2.5 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 rounded-lg transition-all shadow-lg hover:shadow-red-500/25">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- FORMULARIO DE USUARIO -->
    <livewire:users.user-form />
</div>
