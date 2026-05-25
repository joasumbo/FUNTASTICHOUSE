<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Funtastic House</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('head')
    <style>
        *, *::before, *::after { font-family: 'Inter', sans-serif; box-sizing: border-box; }
        body { background: #f5f5f7; }

        .fh-drawer {
            transform: translateX(-100%);
            transition: transform .35s cubic-bezier(.16,1,.3,1);
        }
        .fh-drawer.is-open {
            transform: translateX(0);
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        .fh-nav-slide {
            animation: fhSlideIn .4s cubic-bezier(.16,1,.3,1) both;
        }
        @keyframes fhSlideIn {
            from { opacity: 0; transform: translateX(-12px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes fhFadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen">
@php
    $pendingCount  = \App\Models\Reservation::where('status','pending')->count();
    $pendingRecent = \App\Models\Reservation::where('status','pending')
        ->with('experience')->latest()->take(5)->get();
@endphp

<div
    x-data="{ drawer: false, userMenu: false, notif: false }"
    x-init="$watch('drawer', v => document.body.style.overflow = v ? 'hidden' : '')"
    class="min-h-screen"
>

    {{-- ───── TOP BAR ───── --}}
    <header class="sticky top-0 z-30 px-4 lg:px-8 py-4" style="background:#f5f5f7">
        <div class="flex items-center gap-3">

            {{-- Logo --}}
            <a href="{{ route('admin.dashboard') }}" class="flex-shrink-0 mr-2 select-none">
                <span class="font-bold text-[18px] tracking-tight text-gray-900">
                    <span style="color:#c99f5b">F</span>untastic<span style="color:#c99f5b">H</span>ouse
                </span>
            </a>

            {{-- Hamburger --}}
            <button
                @click="drawer = true"
                aria-label="Abrir menu"
                class="w-11 h-11 rounded-full bg-white flex items-center justify-center transition hover:shadow-md"
                style="box-shadow:0 1px 3px rgba(0,0,0,0.06)"
            >
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#1f2937" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Pill nav --}}
            <nav class="hidden md:flex items-center gap-1 bg-white rounded-full px-2 py-1.5 ml-2" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                @php
                    $mainNav = [
                        ['route' => 'admin.dashboard',  'label' => 'Dashboard'],
                        ['route' => 'admin.reservas',   'label' => 'Reservas'],
                        ['route' => 'admin.calendario', 'label' => 'Calendário'],
                        ['route' => 'admin.precario',   'label' => 'Preçário'],
                    ];
                @endphp
                @foreach($mainNav as $item)
                    @php $active = request()->routeIs($item['route'] . '*'); @endphp
                    <a
                        href="{{ route($item['route']) }}"
                        class="flex items-center gap-1.5 px-5 py-2 rounded-full text-sm font-medium transition-all {{ $active ? 'text-white' : 'text-gray-600 hover:bg-gray-50' }}"
                        @if($active) style="background:#c99f5b; box-shadow:0 2px 6px rgba(201,159,91,0.3)" @endif
                    >
                        {{ $item['label'] }}
                        @if($item['route'] === 'admin.reservas' && $pendingCount > 0)
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none {{ $active ? 'bg-white/30 text-white' : 'bg-red-100 text-red-600' }}">
                                {{ $pendingCount > 99 ? '99+' : $pendingCount }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </nav>

            <div class="flex-1"></div>

            {{-- Notification bell --}}
            <div class="relative">
                <button
                    @click="notif = !notif; userMenu = false"
                    class="w-11 h-11 rounded-full bg-white flex items-center justify-center transition hover:shadow-md relative"
                    style="box-shadow:0 1px 3px rgba(0,0,0,0.06)"
                    aria-label="Notificações"
                >
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#1f2937" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if($pendingCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 bg-red-500 rounded-full flex items-center justify-center text-[10px] text-white font-bold leading-none">
                        {{ $pendingCount > 9 ? '9+' : $pendingCount }}
                    </span>
                    @endif
                </button>

                <div
                    x-show="notif"
                    @click.outside="notif = false"
                    x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    class="absolute right-0 mt-2 w-80 bg-white rounded-2xl overflow-hidden z-40"
                    style="box-shadow:0 10px 40px rgba(0,0,0,0.12), 0 0 0 1px rgba(0,0,0,0.04)"
                >
                    {{-- Header --}}
                    <div class="flex items-center justify-between px-4 py-3.5 border-b border-gray-100">
                        <h3 class="font-semibold text-sm text-gray-900">Notificações</h3>
                        @if($pendingCount > 0)
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-600">
                            {{ $pendingCount }} pendente{{ $pendingCount > 1 ? 's' : '' }}
                        </span>
                        @endif
                    </div>

                    @if($pendingCount > 0)
                        {{-- Pending reservations list --}}
                        <div class="divide-y divide-gray-50 max-h-72 overflow-y-auto">
                            @foreach($pendingRecent as $pr)
                            <a href="{{ route('admin.reservas.show', $pr) }}"
                               @click="notif = false"
                               class="flex items-start gap-3 px-4 py-3 hover:bg-amber-50/40 transition-colors group">
                                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate group-hover:text-amber-700 transition-colors">
                                        {{ $pr->name }}
                                    </p>
                                    <p class="text-xs text-gray-500 truncate">
                                        {{ $pr->experience?->name_pt ?? '—' }}
                                        · {{ $pr->check_in->format('d M Y') }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ $pr->created_at->diffForHumans() }}</p>
                                </div>
                                <svg class="flex-shrink-0 mt-1 text-gray-300 group-hover:text-amber-400 transition-colors" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            @endforeach
                        </div>

                        {{-- Footer: ver todas --}}
                        <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50">
                            <a href="{{ route('admin.reservas', ['status' => 'pending']) }}"
                               @click="notif = false"
                               class="text-xs font-semibold text-[#c99f5b] hover:underline flex items-center gap-1">
                                Ver todas as reservas pendentes
                                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-10 text-center px-4">
                            <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center mb-3">
                                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-gray-700">Tudo em dia</p>
                            <p class="text-xs text-gray-400 mt-1">Sem reservas pendentes</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- User avatar + dropdown --}}
            <div class="relative">
                <button
                    @click="userMenu = !userMenu; notif = false"
                    class="flex items-center gap-2.5 bg-white rounded-full pl-1 pr-3 py-1 transition hover:shadow-md"
                    style="box-shadow:0 1px 3px rgba(0,0,0,0.06)"
                >
                    @if(auth()->user()->photo)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url(auth()->user()->photo) }}"
                             alt="Foto"
                             class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                    @else
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold text-white flex-shrink-0" style="background:#c99f5b">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="hidden sm:flex flex-col items-start leading-tight">
                        <span class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</span>
                        <span class="text-[11px] text-gray-400">Super Admin</span>
                    </div>
                    <svg class="hidden sm:block ml-1" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#6b7280" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div
                    x-show="userMenu"
                    @click.outside="userMenu = false"
                    x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="absolute right-0 mt-2 w-60 bg-white rounded-2xl p-2 z-40"
                    style="box-shadow:0 10px 40px rgba(0,0,0,0.12), 0 0 0 1px rgba(0,0,0,0.04)"
                >
                    <div class="px-3 py-3 border-b border-gray-50 mb-1">
                        <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('admin.perfil') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 transition text-sm text-gray-700">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Meu perfil
                    </a>
                    <a href="{{ url('/') }}" target="_blank" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 transition text-sm text-gray-700">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Ver site público
                    </a>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-red-50 transition text-sm text-red-600">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Terminar sessão
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </header>

    {{-- ───── DRAWER OVERLAY ───── --}}
    <div
        x-show="drawer"
        @click="drawer = false"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40"
        style="background:rgba(15,23,42,0.35);backdrop-filter:blur(4px)"
    ></div>

    {{-- ───── DRAWER ───── --}}
    <aside
        class="fh-drawer fixed top-0 left-0 h-full w-80 z-50 flex flex-col bg-white"
        :class="{ 'is-open': drawer }"
    >
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <span class="font-bold text-[18px] tracking-tight text-gray-900">
                <span style="color:#c99f5b">F</span>untastic<span style="color:#c99f5b">H</span>ouse
            </span>
            <button
                @click="drawer = false"
                class="w-9 h-9 rounded-full hover:bg-gray-100 flex items-center justify-center transition"
                aria-label="Fechar"
            >
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#374151" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
            <p class="px-3 mb-2 text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Principal</p>

            @php
                $drawerMain = [
                    ['route' => 'admin.dashboard',  'label' => 'Dashboard',
                     'd' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['route' => 'admin.reservas',   'label' => 'Reservas',
                     'd' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['route' => 'admin.calendario', 'label' => 'Calendário',
                     'd' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['route' => 'admin.precario',   'label' => 'Preçário',
                     'd' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ];
                $drawerSec = [
                    ['route' => 'admin.galeria',       'label' => 'Galeria',
                     'd' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['route' => 'admin.pois',          'label' => 'Pontos de Interesse',
                     'd' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['route' => 'admin.testemunhos',   'label' => 'Testemunhos',
                     'd' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
                    ['route' => 'admin.configuracoes', 'label' => 'Configurações',
                     'd' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                ];
            @endphp

            @foreach($drawerMain as $i => $item)
                @php $active = request()->routeIs($item['route'] . '*'); @endphp
                <a
                    href="{{ route($item['route']) }}"
                    @click="drawer = false"
                    class="fh-nav-slide flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ $active ? 'text-white' : 'text-gray-700 hover:bg-gray-50' }}"
                    style="{{ $active ? 'background:#c99f5b;animation-delay:'.($i*30).'ms' : 'animation-delay:'.($i*30).'ms' }}"
                >
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['d'] }}"/>
                    </svg>
                    <span class="flex-1">{{ $item['label'] }}</span>
                    @if($item['route'] === 'admin.reservas' && $pendingCount > 0)
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full leading-none {{ $active ? 'bg-white/30 text-white' : 'bg-red-100 text-red-600' }}">
                            {{ $pendingCount > 99 ? '99+' : $pendingCount }}
                        </span>
                    @endif
                </a>
            @endforeach

            <p class="px-3 mt-5 mb-2 text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Gestão</p>

            @foreach($drawerSec as $i => $item)
                @php $active = request()->routeIs($item['route'] . '*'); @endphp
                <a
                    href="{{ route($item['route']) }}"
                    @click="drawer = false"
                    class="fh-nav-slide flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition {{ $active ? 'font-medium text-white' : 'text-gray-600 hover:bg-gray-50' }}"
                    style="{{ $active ? 'background:#c99f5b;animation-delay:'.(($i+count($drawerMain))*30).'ms' : 'animation-delay:'.(($i+count($drawerMain))*30).'ms' }}"
                >
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['d'] }}"/>
                    </svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="px-6 py-4 border-t border-gray-100">
            <p class="text-[11px] text-gray-400">Funtastic House Admin v1.0</p>
        </div>
    </aside>

    {{-- ───── MAIN CONTENT ───── --}}
    <main class="px-4 lg:px-8 pb-10">
        @yield('content')
    </main>

</div>

@stack('scripts')
</body>
</html>
