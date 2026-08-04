<div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Orden #{{ $order->order_number }}</h1>
            <p class="mt-1 text-gray-400">Creada el {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="flex gap-3 print:hidden">
            <a href="{{ route('work-orders.index') }}"
                class="flex items-center gap-2 px-4 py-2 text-gray-400 transition-all border border-gray-700 rounded-lg hover:bg-gray-800 hover:text-white">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
                Volver
            </a>

            <button onclick="window.print()"
                class="flex items-center gap-2 px-4 py-2 text-gray-300 transition-all border border-gray-700 rounded-lg hover:bg-gray-800 hover:text-white">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 6 2 18 2 18 9" />
                    <path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2" />
                    <rect x="6" y="14" width="12" height="8" />
                </svg>
                Imprimir Nota
            </button>

            @if($order->canBeEdited())
            <button wire:click="$dispatch('openOrderModal', { orderId: {{ $order->id }} })"
                class="flex items-center gap-2 px-4 py-2 text-white transition-all bg-blue-600 rounded-lg hover:bg-blue-700">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                </svg>
                Editar Orden
            </button>
            @endif
        </div>
    </div>

    <style>
    @media print {
        aside, header, .print\:hidden, button, a, select, input {
            display: none !important;
        }
        .ml-60 {
            margin-left: 0 !important;
        }
        main, .p-8 {
            padding: 0 !important;
        }
        body, .bg-base {
            background: white !important;
            color: black !important;
        }
        .bg-gray-900, .bg-card, .border-gray-800, .border-border {
            background: transparent !important;
            border-color: #e5e7eb !important;
            color: black !important;
            box-shadow: none !important;
        }
        .text-white, .text-gray-300, .text-gray-400, .text-gray-500 {
            color: black !important;
        }
        .text-orange-400 {
            color: #d97706 !important;
        }
        .grid {
            display: block !important;
        }
        .grid > div {
            margin-bottom: 1.5rem !important;
            page-break-inside: avoid;
        }
        table {
            border-collapse: collapse !important;
            width: 100% !important;
        }
        th, td {
            border: 1px solid #e5e7eb !important;
            padding: 8px 12px !important;
            color: black !important;
        }
        .divide-y > * + * {
            border-top-width: 1px !important;
            border-color: #e5e7eb !important;
        }
    }
    </style>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Información Principal -->
        <div class="space-y-6 lg:col-span-2">
            <!-- Datos del Cliente y Vehículo -->
            <div class="overflow-hidden bg-gray-900 border border-gray-800 rounded-xl">
                <div class="px-6 py-4 border-b border-gray-800">
                    <h2 class="text-lg font-semibold text-white">Información del Cliente y Vehículo</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label
                                class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400">Cliente</label>
                            <p class="mt-1 text-white">{{ $order->client->name }} {{ $order->client->last_name }}</p>
                            <p class="text-sm text-gray-400">{{ $order->client->phone }}</p>
                            <p class="text-sm text-gray-400">{{ $order->client->email }}</p>
                        </div>
                        <div>
                            <label
                                class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400">Vehículo</label>
                            <p class="mt-1 text-white">{{ $order->vehicle->brand }} {{ $order->vehicle->model }}</p>
                            <p class="text-sm text-gray-400">Placa: {{ $order->vehicle->plate }}</p>
                            <p class="text-sm text-gray-400">Año: {{ $order->vehicle->year }} - Color: {{
                                $order->vehicle->color }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Descripción y Diagnóstico -->
            <div class="overflow-hidden bg-gray-900 border border-gray-800 rounded-xl">
                <div class="px-6 py-4 border-b border-gray-800">
                    <h2 class="text-lg font-semibold text-white">Descripción del Problema</h2>
                </div>
                <div class="p-6">
                    <p class="text-gray-300">{{ $order->problem_description }}</p>

                    @if($order->diagnosis)
                    <div class="pt-4 mt-4 border-t border-gray-800">
                        <label
                            class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400">Diagnóstico
                            del Mecánico</label>
                        <p class="mt-1 text-gray-300">{{ $order->diagnosis }}</p>
                    </div>
                    @endif

                    @if($order->observations)
                    <div class="pt-4 mt-4 border-t border-gray-800">
                        <label
                            class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400">Observaciones</label>
                        <p class="mt-1 text-gray-300">{{ $order->observations }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Servicios -->
            <div class="overflow-hidden bg-gray-900 border border-gray-800 rounded-xl">
                <div class="px-6 py-4 border-b border-gray-800">
                    <h2 class="text-lg font-semibold text-white">Servicios Realizados</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-800/50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold text-left text-gray-400">Servicio</th>
                                <th class="px-6 py-3 text-xs font-semibold text-center text-gray-400">Cantidad</th>
                                <th class="px-6 py-3 text-xs font-semibold text-right text-gray-400">Precio</th>
                                <th class="px-6 py-3 text-xs font-semibold text-right text-gray-400">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @forelse($order->services as $service)
                            <tr>
                                <td class="px-6 py-4 text-sm text-white">{{ $service->name }}</td>
                                <td class="px-6 py-4 text-sm text-center text-gray-400">{{ $service->pivot->quantity }}
                                </td>
                                <td class="px-6 py-4 text-sm text-right text-gray-400">${{
                                    number_format($service->pivot->unit_price, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-right text-orange-400">${{
                                    number_format($service->pivot->unit_price * $service->pivot->quantity, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">No hay servicios registrados
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Repuestos -->
            <div class="overflow-hidden bg-gray-900 border border-gray-800 rounded-xl">
                <div class="px-6 py-4 border-b border-gray-800">
                    <h2 class="text-lg font-semibold text-white">Repuestos Utilizados</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-800/50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold text-left text-gray-400">Repuesto</th>
                                <th class="px-6 py-3 text-xs font-semibold text-center text-gray-400">Cantidad</th>
                                <th class="px-6 py-3 text-xs font-semibold text-right text-gray-400">Precio</th>
                                <th class="px-6 py-3 text-xs font-semibold text-right text-gray-400">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @forelse($order->spareParts as $part)
                            <tr>
                                <td class="px-6 py-4 text-sm text-white">{{ $part->name }}</td>
                                <td class="px-6 py-4 text-sm text-center text-gray-400">{{ $part->pivot->quantity }}
                                </td>
                                <td class="px-6 py-4 text-sm text-right text-gray-400">${{
                                    number_format($part->pivot->unit_price, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-right text-orange-400">${{
                                    number_format($part->pivot->unit_price * $part->pivot->quantity, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">No hay repuestos registrados
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Historial de Transiciones (Bitácora) -->
            <div class="overflow-hidden bg-gray-900 border border-gray-800 rounded-xl print:hidden">
                <div class="px-6 py-4 border-b border-gray-800">
                    <h2 class="text-lg font-semibold text-white">Historial de Transiciones</h2>
                </div>
                <div class="p-6">
                    @if($order->histories->isNotEmpty())
                    <div class="relative pl-6 border-l border-gray-800 space-y-6">
                        @foreach($order->histories as $history)
                        <div class="relative">
                            <!-- Bullet -->
                            <span class="absolute -left-[31px] top-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-gray-900 border-2 border-orange-500"></span>

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                <div class="text-sm font-semibold text-white">
                                    {{ $history->from_status ? $history->from_status->label() : 'Ingreso' }}
                                    →
                                    <span class="text-orange-400 font-bold">{{ $history->to_status->label() }}</span>
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $history->created_at->format('d/m/Y H:i') }} ({{ $history->created_at->diffForHumans() }})
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">
                                Realizado por: <span class="text-gray-300 font-medium">{{ $history->user ? $history->user->name : 'Sistema (Seeder/Automático)' }}</span>
                            </p>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-gray-500 text-center py-4">No hay registros de transiciones para esta orden.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Estado -->
            <div class="overflow-hidden bg-gray-900 border border-gray-800 rounded-xl">
                <div class="px-6 py-4 border-b border-gray-800">
                    <h2 class="text-lg font-semibold text-white">Estado de la Orden</h2>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-semibold rounded-full {{ $order->status_badge }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-{{ $order->status_color }}-400"></span>
                            {{ $order->status_label }}
                        </span>
                    </div>

                    @if(!$order->isDelivered() && !$order->isCancelled())
                    <div class="space-y-2">
                        <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400">Cambiar
                            Estado</label>

                        @if($order->isPending())
                        <button wire:click="changeStatus('in_progress')"
                            class="w-full py-2 text-white transition-all bg-blue-600 rounded-lg hover:bg-blue-700">
                            Marcar como En Progreso
                        </button>
                        @endif

                        @if($order->isInProgress())
                        <button wire:click="changeStatus('completed')"
                            class="w-full py-2 text-white transition-all bg-green-600 rounded-lg hover:bg-green-700">
                            Marcar como Completada
                        </button>
                        @endif

                        @if($order->isCompleted())
                        <button wire:click="changeStatus('delivered')"
                            class="w-full py-2 text-white transition-all bg-purple-600 rounded-lg hover:bg-purple-700">
                            Marcar como Entregada
                        </button>
                        @endif

                        @if(!$order->isCancelled())
                        <button wire:click="changeStatus('cancelled')"
                            class="w-full py-2 text-white transition-all bg-red-600 rounded-lg hover:bg-red-700">
                            Cancelar Orden
                        </button>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Totales -->
            <div class="overflow-hidden bg-gray-900 border border-gray-800 rounded-xl">
                <div class="px-6 py-4 border-b border-gray-800">
                    <h2 class="text-lg font-semibold text-white">Totales</h2>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Subtotal:</span>
                        <span class="text-white">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">IVA (16%):</span>
                        <span class="text-white">${{ number_format($order->tax, 2) }}</span>
                    </div>
                    <div class="flex justify-between pt-3 border-t border-gray-800">
                        <span class="text-lg font-semibold text-white">Total:</span>
                        <span class="text-xl font-bold text-orange-400">${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="overflow-hidden bg-gray-900 border border-gray-800 rounded-xl">
                <div class="px-6 py-4 border-b border-gray-800">
                    <h2 class="text-lg font-semibold text-white">Información Adicional</h2>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <label
                            class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400">Recepcionista</label>
                        <p class="mt-1 text-white">{{ $order->user->name }}</p>
                    </div>

                    @if($order->mechanic)
                    <div>
                        <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400">Mecánico
                            Asignado</label>
                        <p class="mt-1 text-white">{{ $order->mechanic->name }}</p>
                    </div>
                    @endif

                    @if($order->delivery_date)
                    <div>
                        <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400">Fecha
                            Entrega Estimada</label>
                        <p class="mt-1 text-white">{{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }}
                        </p>
                    </div>
                    @endif

                    @if($order->completed_at)
                    <div>
                        <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400">Fecha de
                            Completado</label>
                        <p class="mt-1 text-white">{{ $order->completed_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif

                    @if($order->delivered_at)
                    <div>
                        <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400">Fecha de
                            Entrega</label>
                        <p class="mt-1 text-white">{{ $order->delivered_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Estado de Pago -->
            <div class="overflow-hidden bg-gray-900 border border-gray-800 rounded-xl">
                <div class="px-6 py-4 border-b border-gray-800">
                    <h2 class="text-lg font-semibold text-white">Estado de Pago</h2>
                </div>
                <div class="p-6">
                    <div class="mb-3">
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-semibold rounded-full
                            {{ $order->payment_status === 'paid' ? 'bg-green-500/10 text-green-400' :
                               ($order->payment_status === 'partial' ? 'bg-yellow-500/10 text-yellow-400' : 'bg-red-500/10 text-red-400') }}">
                            {{ $order->payment_status === 'paid' ? 'Pagado' :
                            ($order->payment_status === 'partial' ? 'Pago Parcial' : 'Pendiente') }}
                        </span>
                    </div>

                    @if($order->payment_method)
                    <div>
                        <label class="block text-[11px] font-semibold tracking-widest uppercase text-gray-400">Método de
                            Pago</label>
                        <p class="mt-1 text-white">{{ ucfirst($order->payment_method) }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación -->
    @if($showConfirmModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="w-full max-w-md bg-gray-900 border border-gray-800 shadow-2xl rounded-2xl">
            <div class="flex items-center gap-4 px-6 py-5">
                <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-xl bg-yellow-500/10">
                    <svg class="w-5 h-5 text-yellow-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M12 9v4M12 17h.01" />
                        <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">Confirmar Cambio de Estado</h2>
                    <p class="text-sm text-gray-400 mt-0.5">{{ $confirmMessage }}</p>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-800">
                <button wire:click="cancelConfirm"
                    class="px-4 py-2.5 text-sm font-semibold text-gray-400 border border-gray-700 rounded-lg hover:bg-gray-800 hover:text-white transition-all">
                    Cancelar
                </button>
                <button wire:click="confirmStatusChange"
                    class="px-4 py-2.5 text-sm font-semibold text-white bg-orange-600 hover:bg-orange-700 rounded-lg transition-all">
                    Confirmar
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
