<div>
    @if($open)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="w-full max-w-4xl bg-gray-900 border border-gray-800 shadow-2xl rounded-2xl">

                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-800">
                    <h2 class="text-xl font-bold text-white">
                        {{ $orderId ? 'Editar Orden de Trabajo' : 'Nueva Orden de Trabajo' }}
                    </h2>
                    <button wire:click="close" class="text-gray-500 transition-colors hover:text-white">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-6 max-h-[calc(100vh-200px)] overflow-y-auto">

                    <!-- Datos del Cliente y Vehículo -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label
                                class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                                Cliente *
                            </label>
                            <select wire:model="client_id" wire:change="updatedClientId"
                                class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white focus:outline-none focus:border-orange-500">
                                <option value="">Seleccionar cliente</option>
                                @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }} {{ $client->last_name }}</option>
                                @endforeach
                            </select>
                            @error('client_id') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label
                                class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                                Vehículo *
                            </label>
                            <select wire:model="vehicle_id"
                                class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white focus:outline-none focus:border-orange-500"
                                {{ !$client_id ? 'disabled' : '' }}>
                                <option value="">Seleccionar vehículo</option>
                                @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">{{ $vehicle->plate }} - {{ $vehicle->brand }} {{
                                    $vehicle->model }}</option>
                                @endforeach
                            </select>
                            @error('vehicle_id') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label
                                class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                                Mecánico Asignado
                            </label>
                            <select wire:model="mechanic_id"
                                class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white focus:outline-none focus:border-orange-500">
                                <option value="">Sin asignar</option>
                                @foreach($mechanics as $mechanic)
                                <option value="{{ $mechanic->id }}">{{ $mechanic->name }}</option>
                                @endforeach
                            </select>
                            @error('mechanic_id') <span class="mt-1 text-xs text-red-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                                Fecha de Entrega Estimada
                            </label>
                            <input type="date" wire:model="delivery_date"
                                class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white focus:outline-none focus:border-orange-500">
                            @error('delivery_date') <span class="mt-1 text-xs text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                            Descripción del Problema *
                        </label>
                        <textarea wire:model="problem_description" rows="3"
                            placeholder="Describa detalladamente el problema que presenta el vehículo..."
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-orange-500 resize-none"></textarea>
                        @error('problem_description') <span class="mt-1 text-xs text-red-400">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-1.5">
                            Observaciones
                        </label>
                        <textarea wire:model="observations" rows="2" placeholder="Observaciones adicionales..."
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-orange-500 resize-none"></textarea>
                        @error('observations') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                    </div>

                    <!-- Servicios -->
                    <div class="pt-4 border-t border-gray-800">
                        <h3 class="mb-3 text-lg font-semibold text-white">Servicios</h3>

                        <div class="flex gap-3 mb-4">
                            <select wire:model="selectedServiceId"
                                class="flex-1 bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white">
                                <option value="">Seleccionar servicio</option>
                                @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }} - ${{
                                    number_format($service->price, 2) }}</option>
                                @endforeach
                            </select>
                            <input type="number" wire:model="selectedServiceQuantity" min="1" value="1"
                                class="w-24 bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white text-center">
                            <button wire:click="addService"
                                class="px-4 py-2 text-white bg-orange-600 rounded-lg hover:bg-orange-700">
                                Agregar
                            </button>
                        </div>

                        @if(count($services) > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="border-b border-gray-800">
                                    <tr>
                                        <th class="px-3 py-2 text-xs text-left text-gray-500">Servicio</th>
                                        <th class="px-3 py-2 text-xs text-center text-gray-500">Cantidad</th>
                                        <th class="px-3 py-2 text-xs text-right text-gray-500">Precio</th>
                                        <th class="px-3 py-2 text-xs text-right text-gray-500">Total</th>
                                        <th class="px-3 py-2 text-xs text-center text-gray-500"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($services as $index => $service)
                                    <tr class="border-b border-gray-800">
                                        <td class="px-3 py-2 text-sm text-white">{{ $service['name'] }}</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-400">{{ $service['quantity']
                                            }}</td>
                                        <td class="px-3 py-2 text-sm text-right text-gray-400">${{
                                            number_format($service['unit_price'], 2) }}</td>
                                        <td class="px-3 py-2 text-sm text-right text-orange-400">${{
                                            number_format($service['total'], 2) }}</td>
                                        <td class="px-3 py-2 text-center">
                                            <button wire:click="removeService({{ $index }})"
                                                class="text-red-400 hover:text-red-300">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <polyline points="3 6 5 6 21 6" />
                                                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
                                                    <path d="M10 11v6M14 11v6" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-sm text-gray-500">No hay servicios agregados.</p>
                        @endif
                    </div>

                    <!-- Repuestos -->
                    <div class="pt-4 border-t border-gray-800">
                        <h3 class="mb-3 text-lg font-semibold text-white">Repuestos</h3>

                        <div class="flex gap-3 mb-4">
                            <select wire:model="selectedSparePartId"
                                class="flex-1 bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white">
                                <option value="">Seleccionar repuesto</option>
                                @foreach($spareParts as $part)
                                <option value="{{ $part->id }}">{{ $part->name }} (Stock: {{ $part->stock }}) - ${{
                                    number_format($part->unit_price, 2) }}</option>
                                @endforeach
                            </select>
                            <input type="number" wire:model="selectedSparePartQuantity" min="1" value="1"
                                class="w-24 bg-gray-800 border border-gray-700 rounded-lg py-2.5 px-3.5 text-sm text-white text-center">
                            <button wire:click="addSparePart"
                                class="px-4 py-2 text-white bg-orange-600 rounded-lg hover:bg-orange-700">
                                Agregar
                            </button>
                        </div>

                        @if(count($spareParts) > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="border-b border-gray-800">
                                    <tr>
                                        <th class="px-3 py-2 text-xs text-left text-gray-500">Repuesto</th>
                                        <th class="px-3 py-2 text-xs text-center text-gray-500">Cantidad</th>
                                        <th class="px-3 py-2 text-xs text-right text-gray-500">Precio</th>
                                        <th class="px-3 py-2 text-xs text-right text-gray-500">Total</th>
                                        <th class="px-3 py-2 text-xs text-center text-gray-500"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($spareParts as $index => $part)
                                    <tr class="border-b border-gray-800">
                                        <td class="px-3 py-2 text-sm text-white">{{ $part['name'] }}</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-400">{{ $part['quantity'] }}
                                        </td>
                                        <td class="px-3 py-2 text-sm text-right text-gray-400">${{
                                            number_format($part['unit_price'], 2) }}</td>
                                        <td class="px-3 py-2 text-sm text-right text-orange-400">${{
                                            number_format($part['total'], 2) }}</td>
                                        <td class="px-3 py-2 text-center">
                                            <button wire:click="removeSparePart({{ $index }})"
                                                class="text-red-400 hover:text-red-300">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <polyline points="3 6 5 6 21 6" />
                                                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
                                                    <path d="M10 11v6M14 11v6" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-sm text-gray-500">No hay repuestos agregados.</p>
                        @endif
                    </div>

                    <!-- Totales -->
                    <div class="pt-4 border-t border-gray-800">
                        <div class="flex justify-end">
                            <div class="w-64 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400">Subtotal:</span>
                                    <span class="text-white">${{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400">IVA (16%):</span>
                                    <span class="text-white">${{ number_format($tax, 2) }}</span>
                                </div>
                                <div class="flex justify-between pt-2 text-lg font-bold border-t border-gray-800">
                                    <span class="text-white">Total:</span>
                                    <span class="text-orange-400">${{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-800">
                    <button wire:click="close"
                        class="px-4 py-2.5 text-sm font-semibold text-gray-400 border border-gray-700 rounded-lg hover:bg-gray-800 hover:text-white transition-all">
                        Cancelar
                    </button>
                    <button wire:click="save" wire:loading.attr="disabled"
                        class="px-4 py-2.5 text-sm font-semibold text-white bg-orange-600 hover:bg-orange-700 rounded-lg transition-all shadow-lg hover:shadow-orange-600/25 disabled:opacity-50">
                        <span wire:loading.remove wire:target="save">{{ $orderId ? 'Actualizar' : 'Guardar' }}</span>
                        <span wire:loading wire:target="save">Guardando...</span>
                    </button>
                </div>

            </div>
        </div>
    </div>
    @endif
</div>
