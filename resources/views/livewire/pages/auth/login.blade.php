<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex min-h-screen bg-base">

    <!-- LEFT: FORM -->
    <div
        class="w-[480px] flex-shrink-0 flex flex-col justify-center px-14 py-16 bg-card border-r border-border relative">

        <!-- Accent line -->
        <div
            class="absolute top-0 bottom-0 right-0 w-px bg-gradient-to-b from-transparent via-accent to-transparent opacity-60">
        </div>

        <!-- Logo -->
        <div class="mb-12">
            <div
                class="w-11 h-11 bg-accent rounded-xl flex items-center justify-center mb-3 shadow-[0_0_24px_rgba(249,115,22,0.3)]">
                <svg class="w-5 h-5 fill-white" viewBox="0 0 24 24">
                    <path
                        d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z" />
                </svg>
            </div>
            <div class="text-2xl font-bold leading-none tracking-wide font-rajdhani">AutoTaller</div>
            <div class="text-[11px] text-accent font-semibold tracking-[2.5px] uppercase mt-1">Sistema de Gestión</div>
        </div>

        <!-- Heading -->
        <div class="mb-8">
            <h2 class="mb-1 text-3xl font-bold font-rajdhani">Bienvenido de vuelta</h2>
            <p class="text-sm text-gray-400">Ingresa tus credenciales para acceder al sistema.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="login">

            <!-- Email -->
            <div class="mb-5">
                <label class="block text-[11px] font-semibold tracking-[0.8px] uppercase text-gray-400 mb-2">
                    Correo electrónico
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                    </span>
                    <input wire:model="form.email" id="email" type="email" placeholder="admin@taller.com" required
                        autofocus autocomplete="username"
                        class="w-full py-3 pl-10 pr-4 text-sm text-white placeholder-gray-600 transition-all border rounded-lg bg-base border-border focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20" />
                </div>
                <x-input-error :messages="$errors->get('form.email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div class="mb-5">
                <label class="block text-[11px] font-semibold tracking-[0.8px] uppercase text-gray-400 mb-2">
                    Contraseña
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" />
                            <path d="M7 11V7a5 5 0 0110 0v4" />
                        </svg>
                    </span>
                    <input wire:model="form.password" id="password" type="password" placeholder="••••••••" required
                        autocomplete="current-password"
                        class="w-full py-3 pl-10 pr-4 text-sm text-white placeholder-gray-600 transition-all border rounded-lg bg-base border-border focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20" />
                </div>
                <x-input-error :messages="$errors->get('form.password')" class="mt-1" />
            </div>

            <!-- Remember + Forgot -->
            <div class="flex items-center justify-between mb-7">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input wire:model="form.remember" type="checkbox" class="w-4 h-4 rounded accent-accent">
                    <span class="text-sm text-gray-400">Recordarme</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate
                        class="text-sm font-medium text-accent hover:underline">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-accent hover:bg-orange-600 text-white font-rajdhani font-bold text-sm tracking-widest uppercase rounded-lg py-3.5 transition-all shadow-[0_4px_20px_rgba(249,115,22,0.25)] hover:shadow-[0_6px_28px_rgba(249,115,22,0.35)] hover:-translate-y-0.5 active:translate-y-0">
                Iniciar Sesión
            </button>

        </form>

        <p class="absolute bottom-6 left-0 right-0 text-center text-[11px] text-gray-600 tracking-widest">
            © {{ date('Y') }} AutoTaller — Todos los derechos reservados
        </p>
    </div>

    <!-- RIGHT: BRANDING -->
    <div class="relative flex items-center justify-center flex-1 p-16 overflow-hidden">

        <div class="absolute inset-0"
            style="background: radial-gradient(ellipse at 20% 50%, rgba(249,115,22,0.08) 0%, transparent 60%), radial-gradient(ellipse at 80% 20%, rgba(59,130,246,0.06) 0%, transparent 50%), #0F1117;">
        </div>

        <div class="absolute inset-0 opacity-30"
            style="background-image: linear-gradient(#2D3148 1px, transparent 1px), linear-gradient(90deg, #2D3148 1px, transparent 1px); background-size: 40px 40px;">
        </div>

        <div class="relative z-10 max-w-sm text-center">
            <div class="flex items-center justify-center w-20 h-20 mx-auto border bg-accent/10 border-accent/30 rounded-2xl mb-7 animate-spin"
                style="animation-duration: 12s;">
                <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="#F97316" stroke-width="1.5">
                    <circle cx="12" cy="12" r="3" />
                    <path
                        d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z" />
                </svg>
            </div>

            <h2 class="mb-4 text-4xl font-bold leading-tight font-rajdhani">
                Gestión inteligente<br>para tu <span class="text-accent">taller</span>
            </h2>

            <p class="mb-10 text-sm leading-relaxed text-gray-400">
                Control total de órdenes de trabajo, inventario,<br>clientes y mecánicos en un solo lugar.
            </p>

            <div class="flex flex-col gap-3 text-left">
                @foreach ([['Órdenes de trabajo', 'seguimiento en tiempo real'], ['Inventario inteligente', 'alertas de stock bajo'], ['Roles y permisos', 'admin, mecánico, recepcionista'], ['Recibos en PDF', 'al cerrar cada orden']] as $feature)
                    <div
                        class="flex items-center gap-3 px-4 py-3 border bg-card/80 border-border rounded-xl backdrop-blur-sm">
                        <div class="w-2 h-2 rounded-full bg-accent flex-shrink-0 shadow-[0_0_8px_#F97316]"></div>
                        <span class="text-sm text-gray-400">
                            <strong class="font-semibold text-white">{{ $feature[0] }}</strong> — {{ $feature[1] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
