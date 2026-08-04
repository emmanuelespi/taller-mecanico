<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <!-- Botón Campanita -->
    <button @click="open = !open" 
        class="relative flex items-center justify-center transition-colors border rounded-lg cursor-pointer w-9 h-9 bg-base border-border hover:bg-hover focus:outline-none focus:ring-1 focus:ring-accent/40">
        <svg class="w-4 h-4 text-gray-400 transition-colors hover:text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0" />
        </svg>

        @if($this->unreadCount > 0)
            <!-- Badge de cantidad -->
            <span class="absolute -top-1.5 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-accent text-[9px] font-bold text-white shadow-[0_0_8px_rgba(249,115,22,0.6)] animate-pulse">
                {{ $this->unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown de Notificaciones -->
    <div x-show="open" 
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-2.5 w-80 md:w-96 rounded-xl bg-card border border-border shadow-[0_10px_30px_rgba(0,0,0,0.5)] z-50 overflow-hidden" 
        style="display: none;">
        
        <!-- Header del Dropdown -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-border bg-base/50">
            <div class="flex items-center gap-2">
                <h3 class="text-xs font-semibold tracking-wider uppercase text-gray-400 font-rajdhani">Notificaciones</h3>
                @if($this->unreadCount > 0)
                    <span class="px-1.5 py-0.5 text-[10px] font-medium bg-accent/10 text-accent rounded-full">
                        {{ $this->unreadCount }} nuevas
                    </span>
                @endif
            </div>
            @if($this->unreadCount > 0)
                <button wire:click="markAllAsRead" @click="open = false" 
                    class="text-[11px] font-medium text-accent hover:text-accent-hover transition-colors">
                    Marcar todo leído
                </button>
            @endif
        </div>

        <!-- Cuerpo del Dropdown (Lista) -->
        <div class="divide-y divide-border/60 max-h-80 overflow-y-auto">
            @forelse($this->notifications as $notif)
                <div class="p-3.5 flex gap-3 hover:bg-hover/40 transition-colors group relative">
                    <!-- Icono por tipo -->
                    <div class="flex-shrink-0 mt-0.5">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center
                            @if(($notif->data['type'] ?? '') === 'warning') bg-yellow-500/10 text-yellow-500
                            @elseif(($notif->data['type'] ?? '') === 'success') bg-green-500/10 text-green-500
                            @else bg-blue-500/10 text-blue-500 @endif">
                            
                            @if(($notif->data['icon'] ?? '') === 'inventory')
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            @elseif(($notif->data['icon'] ?? '') === 'order_created')
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @elseif(($notif->data['icon'] ?? '') === 'order_completed')
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @else
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            @endif
                        </div>
                    </div>

                    <!-- Contenido de la notificación -->
                    <div class="flex-1 min-w-0 pr-4">
                        <p class="text-xs font-semibold text-white truncate">
                            {{ $notif->data['title'] ?? 'Notificación' }}
                        </p>
                        <p class="text-[11px] text-gray-400 mt-0.5 leading-snug">
                            {{ $notif->data['message'] ?? '' }}
                        </p>
                        <span class="text-[9px] text-gray-500 mt-1 block">
                            {{ $notif->created_at->diffForHumans() }}
                        </span>
                    </div>

                    <!-- Acción rápida: marcar individual como leída -->
                    <button wire:click="markAsRead('{{ $notif->id }}')" 
                        class="absolute right-3 top-3.5 text-gray-500 hover:text-white transition-colors focus:outline-none"
                        title="Marcar como leída">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @empty
                <!-- Estado vacío -->
                <div class="px-4 py-8 text-center flex flex-col items-center justify-center">
                    <div class="w-10 h-10 rounded-full bg-base/50 flex items-center justify-center text-gray-600 mb-2.5">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-400">Sin notificaciones pendientes</p>
                    <p class="text-[10px] text-gray-600 mt-1">El taller está funcionando al día.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
