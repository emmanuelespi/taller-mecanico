<div>
    @if($open)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto bg-black/60 backdrop-blur-sm">
        <div class="w-full max-w-2xl my-8 bg-gray-900 border border-gray-800 shadow-2xl rounded-2xl">

            <!-- Header -->
            <div
                class="sticky top-0 flex items-center justify-between px-6 py-4 bg-gray-900 border-b border-gray-800 rounded-t-2xl">
                <h2 class="text-xl font-bold text-white">
                    {{ $productId ? 'Editar Producto' : 'Nuevo Producto' }}
                </h2>
                <button wire:click="close" class="text-gray-500 transition-colors hover:text-white">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="px-6 py-5 space-y-4 max-h-[calc(100vh-200px)] overflow-y-auto">

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                            Nombre *
                        </label>
                        <input wire:model="name" type="text" placeholder="Ej: Aceite de Motor 5W-30"
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all">
                        @error('name') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                            SKU (Código)
                        </label>
                        <input wire:model="sku" type="text" placeholder="Ej: OIL-001"
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-orange-500">
                        @error('sku') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                            Categoría *
                        </label>
                        <select wire:model="category"
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white focus:outline-none focus:border-orange-500">
                            <option value="">Seleccionar categoría</option>
                            @foreach($categories as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('category') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                            Unidad *
                        </label>
                        <select wire:model="unit"
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white focus:outline-none focus:border-orange-500">
                            <option value="pieza">Pieza</option>
                            <option value="litro">Litro</option>
                            <option value="juego">Juego</option>
                            <option value="par">Par</option>
                        </select>
                        @error('unit') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                            Precio Venta *
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                            <input wire:model="unit_price" type="number" step="0.01" placeholder="0.00"
                                class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 pl-8 pr-3.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-orange-500">
                        </div>
                        @error('unit_price') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                            Precio Compra
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                            <input wire:model="purchase_price" type="number" step="0.01" placeholder="0.00"
                                class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 pl-8 pr-3.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-orange-500">
                        </div>
                        @error('purchase_price') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                            Stock *
                        </label>
                        <input wire:model="stock" type="number" step="1" placeholder="0"
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-orange-500">
                        @error('stock') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                            Stock Mínimo *
                        </label>
                        <input wire:model="minimum_stock" type="number" step="1" placeholder="5"
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-orange-500">
                        @error('minimum_stock') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                            Ubicación
                        </label>
                        <input wire:model="location" type="text" placeholder="Ej: Estante A, Pasillo 3"
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-orange-500">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                            Proveedor
                        </label>
                        <input wire:model="supplier" type="text" placeholder="Nombre del proveedor"
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-orange-500">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                        Descripción
                    </label>
                    <textarea wire:model="description" rows="3" placeholder="Descripción detallada del producto..."
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-orange-500 resize-none"></textarea>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <label class="flex items-center gap-2 text-sm text-gray-400 cursor-pointer">
                        <input type="checkbox" wire:model="is_active"
                            class="text-green-500 bg-gray-800 border-gray-700 rounded">
                        <span>Producto activo</span>
                    </label>
                </div>

            </div>

            <!-- Footer -->
            <div
                class="sticky bottom-0 flex items-center justify-end gap-3 px-6 py-4 bg-gray-900 border-t border-gray-800 rounded-b-2xl">
                <button wire:click="close"
                    class="px-4 py-2.5 text-sm font-semibold text-gray-400 border border-gray-700 rounded-lg hover:bg-gray-800 hover:text-white transition-all">
                    Cancelar
                </button>
                <button wire:click="save" wire:loading.attr="disabled"
                    class="px-4 py-2.5 text-sm font-semibold text-white bg-orange-600 hover:bg-orange-700 rounded-lg transition-all shadow-lg hover:shadow-orange-600/25 disabled:opacity-50">
                    <span wire:loading.remove wire:target="save">{{ $productId ? 'Actualizar' : 'Guardar' }}</span>
                    <span wire:loading wire:target="save">Guardando...</span>
                </button>
            </div>

        </div>
    </div>
    @endif
</div>