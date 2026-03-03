<div>
    @if($open)
    <!-- BACKDROP -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60]" wire:click="close"></div>

    <!-- MODAL -->
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div class="w-full max-w-md border shadow-2xl bg-card border-border rounded-2xl">

            <!-- Header -->
            <div class="flex items-center gap-4 px-6 py-5">
                <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-xl bg-red-500/10">
                    <svg class="w-5 h-5 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        <line x1="12" y1="9" x2="12" y2="13" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold font-rajdhani">{{ $title }}</h2>
                    <p class="text-sm text-gray-400 mt-0.5">{{ $message }}</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-border">
                <button wire:click="close"
                    class="px-4 py-2.5 text-sm font-semibold text-gray-400 border border-border rounded-lg hover:bg-hover hover:text-white transition-all">
                    Cancelar
                </button>
                <button wire:click="confirm"
                    class="px-4 py-2.5 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 rounded-lg transition-all shadow-[0_4px_12px_rgba(239,68,68,0.25)] hover:-translate-y-0.5">
                    Confirmar
                </button>
            </div>

        </div>
    </div>
    @endif
</div>