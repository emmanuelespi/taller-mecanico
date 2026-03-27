@props([
    'show' => false,
    'title' => 'Confirmar acción',
    'message' => '¿Estás seguro de realizar esta acción?',
    'confirmAction' => '',
    'cancelAction' => '',
    'type' => 'danger'
])

@if($show)
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60]" wire:click="{{ $cancelAction }}"></div>
<div class="fixed inset-0 z-[60] flex items-center justify-center p-4">
    <div class="w-full max-w-md border shadow-2xl bg-card border-border rounded-2xl">

        <!-- Header -->
        <div class="flex items-center gap-4 px-6 py-5">
            <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-xl
                {{ $type === 'danger' ? 'bg-red-500/10' : ($type === 'warning' ? 'bg-yellow-500/10' : 'bg-blue-500/10') }}">
                <svg class="w-5 h-5 {{ $type === 'danger' ? 'text-red-400' : ($type === 'warning' ? 'text-yellow-400' : 'text-blue-400') }}"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    @if($type === 'danger')
                        <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        <line x1="12" y1="9" x2="12" y2="13" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    @elseif($type === 'warning')
                        <path d="M12 9v4M12 17h.01" />
                        <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" />
                    @else
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    @endif
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold font-rajdhani">{{ $title }}</h2>
                <p class="text-sm text-gray-400 mt-0.5">{{ $message }}</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-border">
            <button wire:click="{{ $cancelAction }}"
                class="px-4 py-2.5 text-sm font-semibold text-gray-400 border border-border rounded-lg hover:bg-hover hover:text-white transition-all">
                Cancelar
            </button>
            <button wire:click="{{ $confirmAction }}"
                class="px-4 py-2.5 text-sm font-semibold text-white rounded-lg transition-all hover:-translate-y-0.5
                {{ $type === 'danger' ? 'bg-red-500 hover:bg-red-600 shadow-[0_4px_12px_rgba(239,68,68,0.25)]' :
                   ($type === 'warning' ? 'bg-yellow-500 hover:bg-yellow-600 shadow-[0_4px_12px_rgba(234,179,8,0.25)]' :
                   'bg-blue-500 hover:bg-blue-600 shadow-[0_4px_12px_rgba(59,130,246,0.25)]') }}">
                Confirmar
            </button>
        </div>

    </div>
</div>
@endif
