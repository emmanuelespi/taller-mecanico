<div>
    @if($show)
    <div x-data="{ show: true }" x-init="setTimeout(() => { show = false; $wire.hide() }, 3000)" x-show="show"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="fixed bottom-6 right-6 z-[70] flex items-center gap-3 px-4 py-3 rounded-xl border shadow-2xl
        {{ $type === 'success' ? 'bg-card border-green-500/30' : 'bg-card border-red-500/30' }}">

        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
            {{ $type === 'success' ? 'bg-green-500/10' : 'bg-red-500/10' }}">
            @if($type === 'success')
            <svg class="w-4 h-4 text-green-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.5">
                <polyline points="20 6 9 17 4 12" />
            </svg>
            @else
            <svg class="w-4 h-4 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M18 6L6 18M6 6l12 12" />
            </svg>
            @endif
        </div>

        <span class="text-sm font-medium">{{ $message }}</span>

        <button wire:click="hide" class="ml-2 text-gray-500 transition-colors hover:text-white">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 6L6 18M6 6l12 12" />
            </svg>
        </button>

    </div>
    @endif
</div>