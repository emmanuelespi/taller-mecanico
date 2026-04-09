<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Reportes</h1>
        <p class="mt-1 text-gray-400">Exporta datos del sistema a archivos CSV</p>
    </div>

    <!-- Filtros (solo para órdenes) -->
    <div class="p-5 mb-6 bg-gray-900 border border-gray-800 rounded-xl">
        <h2 class="mb-4 text-lg font-semibold text-white">Filtros para Reporte de Órdenes</h2>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                    Fecha Desde
                </label>
                <input type="date" wire:model="dateFrom"
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white">
            </div>
            <div>
                <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                    Fecha Hasta
                </label>
                <input type="date" wire:model="dateTo"
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white">
            </div>
            <div>
                <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                    Estado
                </label>
                <select wire:model="status"
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white">
                    @foreach($statuses as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Botones de Reportes -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">

        <!-- Reporte de Órdenes -->
        <button wire:click="exportOrders" wire:loading.attr="disabled"
            class="flex flex-col items-center gap-3 p-6 bg-gray-900 border border-gray-800 rounded-xl hover:bg-gray-800/50 transition-all hover:-translate-y-0.5">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-blue-500/10">
                <svg class="w-6 h-6 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <div class="font-semibold text-white">Órdenes de Trabajo</div>
                <div class="mt-1 text-xs text-gray-500">Exportar a CSV</div>
            </div>
        </button>

        <!-- Reporte de Inventario -->
        <button wire:click="exportInventory" wire:loading.attr="disabled"
            class="flex flex-col items-center gap-3 p-6 bg-gray-900 border border-gray-800 rounded-xl hover:bg-gray-800/50 transition-all hover:-translate-y-0.5">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-green-500/10">
                <svg class="w-6 h-6 text-green-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path
                        d="M20.54 5.23l-1.39-1.68C18.88 3.21 18.47 3 18 3H6c-.47 0-.88.21-1.16.55L3.46 5.23C3.17 5.57 3 6.02 3 6.5V19c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6.5c0-.48-.17-.93-.46-1.27z" />
                    <path d="M12 17V9M9 12l3-3 3 3" />
                </svg>
            </div>
            <div>
                <div class="font-semibold text-white">Inventario</div>
                <div class="mt-1 text-xs text-gray-500">Exportar a CSV</div>
            </div>
        </button>

        <!-- Reporte de Clientes -->
        <button wire:click="exportClients" wire:loading.attr="disabled"
            class="flex flex-col items-center gap-3 p-6 bg-gray-900 border border-gray-800 rounded-xl hover:bg-gray-800/50 transition-all hover:-translate-y-0.5">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-purple-500/10">
                <svg class="w-6 h-6 text-purple-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                </svg>
            </div>
            <div>
                <div class="font-semibold text-white">Clientes</div>
                <div class="mt-1 text-xs text-gray-500">Exportar a CSV</div>
            </div>
        </button>

        <!-- Reporte de Servicios -->
        <button wire:click="exportServices" wire:loading.attr="disabled"
            class="flex flex-col items-center gap-3 p-6 bg-gray-900 border border-gray-800 rounded-xl hover:bg-gray-800/50 transition-all hover:-translate-y-0.5">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-yellow-500/10">
                <svg class="w-6 h-6 text-yellow-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path
                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                </svg>
            </div>
            <div>
                <div class="font-semibold text-white">Servicios</div>
                <div class="mt-1 text-xs text-gray-500">Exportar a CSV</div>
            </div>
        </button>

        <!-- Reporte de Usuarios -->
        <button wire:click="exportUsers" wire:loading.attr="disabled"
            class="flex flex-col items-center gap-3 p-6 bg-gray-900 border border-gray-800 rounded-xl hover:bg-gray-800/50 transition-all hover:-translate-y-0.5">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-red-500/10">
                <svg class="w-6 h-6 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
            </div>
            <div>
                <div class="font-semibold text-white">Usuarios</div>
                <div class="mt-1 text-xs text-gray-500">Exportar a CSV</div>
            </div>
        </button>
    </div>

    <!-- Indicadores de carga -->
    <div wire:loading class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="flex items-center gap-3 p-6 bg-gray-900 rounded-xl">
            <svg class="w-6 h-6 text-orange-400 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2">
                <path d="M21 12a9 9 0 1 1-6.219-8.56" />
            </svg>
            <span class="text-white">Generando reporte...</span>
        </div>
    </div>
</div>
