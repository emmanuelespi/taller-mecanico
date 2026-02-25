<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AutoTaller') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased text-white font-dm bg-base">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        <aside class="fixed z-50 flex flex-col flex-shrink-0 h-screen border-r w-60 bg-card border-border">

            <!-- Logo -->
            <div class="px-6 border-b py-7 border-border">
                <div
                    class="w-9 h-9 bg-accent rounded-lg flex items-center justify-center mb-2.5 shadow-[0_0_16px_rgba(249,115,22,0.3)]">
                    <svg class="w-5 h-5 fill-white" viewBox="0 0 24 24">
                        <path
                            d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z" />
                    </svg>
                </div>
                <div class="text-xl font-bold leading-none tracking-wide font-rajdhani">AutoTaller</div>
                <div class="text-[10px] text-accent font-semibold tracking-[2px] uppercase mt-1">Sistema de Gestión
                </div>
            </div>

            <!-- Nav -->
            <nav class="flex-1 px-3 py-4 overflow-y-auto">

                <p class="text-[10px] font-semibold tracking-[2px] uppercase text-gray-500 px-3 mb-2">Principal</p>

                <a href="{{ route('dashboard') }}" wire:navigate
                    class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium mb-0.5 transition-all
                        {{ request()->routeIs('dashboard') ? 'bg-accent/10 text-accent' : 'text-gray-400 hover:bg-hover hover:text-white' }}">
                    <svg class="flex-shrink-0 w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" />
                        <rect x="14" y="3" width="7" height="7" />
                        <rect x="3" y="14" width="7" height="7" />
                        <rect x="14" y="14" width="7" height="7" />
                    </svg>
                    Dashboard
                </a>

                <a href="#" wire:navigate
                    class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium mb-0.5 transition-all text-gray-400 hover:bg-hover hover:text-white">
                    <svg class="flex-shrink-0 w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Órdenes de Trabajo
                </a>

                <p class="text-[10px] font-semibold tracking-[2px] uppercase text-gray-500 px-3 mb-2 mt-4">Gestión</p>

                <a href="#" wire:navigate
                    class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium mb-0.5 transition-all text-gray-400 hover:bg-hover hover:text-white">
                    <svg class="flex-shrink-0 w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                    </svg>
                    Clientes
                </a>

                <a href="#" wire:navigate
                    class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium mb-0.5 transition-all text-gray-400 hover:bg-hover hover:text-white">
                    <svg class="flex-shrink-0 w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <rect x="1" y="3" width="15" height="13" rx="2" />
                        <path
                            d="M16 8h4l3 3v5h-7V8zM5.5 18a1.5 1.5 0 100 3 1.5 1.5 0 000-3zm11 0a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" />
                    </svg>
                    Vehículos
                </a>

                <a href="#" wire:navigate
                    class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium mb-0.5 transition-all text-gray-400 hover:bg-hover hover:text-white">
                    <svg class="flex-shrink-0 w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path
                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                    Servicios
                </a>

                <a href="#" wire:navigate
                    class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium mb-0.5 transition-all text-gray-400 hover:bg-hover hover:text-white">
                    <svg class="flex-shrink-0 w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path
                            d="M20.54 5.23l-1.39-1.68C18.88 3.21 18.47 3 18 3H6c-.47 0-.88.21-1.16.55L3.46 5.23C3.17 5.57 3 6.02 3 6.5V19c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6.5c0-.48-.17-.93-.46-1.27z" />
                        <path d="M12 17V9M9 12l3-3 3 3" />
                    </svg>
                    Inventario
                </a>

                <p class="text-[10px] font-semibold tracking-[2px] uppercase text-gray-500 px-3 mb-2 mt-4">Sistema</p>

                <a href="#" wire:navigate
                    class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium mb-0.5 transition-all text-gray-400 hover:bg-hover hover:text-white">
                    <svg class="flex-shrink-0 w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    Usuarios
                </a>

            </nav>

            <!-- User -->
            <div class="px-3 py-4 border-t border-border">
                <div
                    class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg hover:bg-hover transition-all cursor-pointer group">
                    <div
                        class="flex items-center justify-center flex-shrink-0 w-8 h-8 text-sm font-bold border rounded-full bg-accent/10 border-accent/40 font-rajdhani text-accent">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold truncate">{{ auth()->user()->name }}</div>
                        <div class="text-[11px] text-accent capitalize">{{ auth()->user()->role }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-500 transition-colors hover:text-white">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

        </aside>

        <!-- MAIN -->
        <div class="flex flex-col flex-1 min-h-screen ml-60">

            <!-- TOPBAR -->
            <header
                class="sticky top-0 z-40 flex items-center justify-between h-16 px-8 border-b bg-card border-border">
                <div>
                    <h1 class="text-xl font-bold tracking-wide font-rajdhani">{{ $title ?? config('app.name') }}</h1>
                    <p class="text-xs text-gray-500 mt-0.5">{{ now()->isoFormat('dddd, D [de] MMMM YYYY') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <div
                        class="relative flex items-center justify-center transition-colors border rounded-lg cursor-pointer w-9 h-9 bg-base border-border hover:bg-hover">
                        <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0" />
                        </svg>
                        <div class="absolute top-1.5 right-1.5 w-1.5 h-1.5 bg-accent rounded-full"></div>
                    </div>
                    {{ $actions ?? '' }}
                </div>
            </header>

            <!-- CONTENT -->
            <main class="flex-1 p-8">
                {{ $slot }}
            </main>

        </div>

    </div>

</body>

</html>
