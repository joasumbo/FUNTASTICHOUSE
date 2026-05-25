@extends('layouts.admin')

@section('title', 'Dashboard')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')
@php
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Bom dia' : ($hour < 19 ? 'Boa tarde' : 'Boa noite');

    $statusMap = [
        'pending'   => ['label' => 'Pendente',   'cls' => 'bg-amber-50 text-amber-600 border border-amber-100'],
        'confirmed' => ['label' => 'Confirmado', 'cls' => 'bg-green-50 text-green-600 border border-green-100'],
        'cancelled' => ['label' => 'Cancelado',  'cls' => 'bg-red-50 text-red-600 border border-red-100'],
    ];

    $trend = $stats['last_month'] > 0
        ? round((($stats['this_month'] - $stats['last_month']) / $stats['last_month']) * 100, 1)
        : ($stats['this_month'] > 0 ? 100 : 0);
@endphp

<div class="space-y-5">

    {{-- ───── HEADER ───── --}}
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 pt-2">
        <div>
            <h1 class="text-[26px] lg:text-[28px] font-bold text-gray-900 flex items-center gap-2">
                {{ $greeting }}, {{ auth()->user()->name }}
                <span style="display:inline-block;animation:fhWave 2.5s ease-in-out infinite;transform-origin:70% 70%">👋</span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">Acompanhe reservas, disponibilidade e desempenho num só lugar.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ url('/') }}" target="_blank"
               class="flex items-center gap-2 bg-white rounded-full px-4 h-10 text-sm text-gray-700 hover:shadow-sm transition"
               style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Ver site público
            </a>
        </div>
    </div>

    {{-- ───── MAIN GRID ───── --}}
    <div class="grid grid-cols-12 gap-5 items-stretch">

        {{-- KPI CARDS 2×2 --}}
        <div class="col-span-12 lg:col-span-4 grid grid-cols-2 gap-4">

            {{-- Total Reservas --}}
            <div class="bg-white rounded-3xl p-4 hover:shadow-md transition" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <div class="flex items-start justify-between mb-2">
                    <p class="text-xs text-gray-500 font-medium leading-snug">Total de Reservas</p>
                    <div class="w-7 h-7 rounded-full bg-gray-50 flex items-center justify-center flex-shrink-0">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#374151" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-[22px] font-bold text-gray-900 mb-2 leading-none">{{ $stats['total'] }}</p>
                <div class="flex items-center gap-1 text-[11px]">
                    <svg width="9" height="9" viewBox="0 0 10 10" fill="#10B981"><path d="M5 0L10 8H0L5 0Z"/></svg>
                    <span class="font-semibold text-green-600">acumulado</span>
                </div>
            </div>

            {{-- Pendentes --}}
            <div class="bg-white rounded-3xl p-4 hover:shadow-md transition" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <div class="flex items-start justify-between mb-2">
                    <p class="text-xs text-gray-500 font-medium leading-snug">Pendentes</p>
                    <div class="w-7 h-7 rounded-full bg-gray-50 flex items-center justify-center flex-shrink-0">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#374151" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-[22px] font-bold text-gray-900 mb-2 leading-none">{{ $stats['pending'] }}</p>
                <div class="flex items-center gap-1 text-[11px]">
                    @if($stats['pending'] > 0)
                        <svg width="9" height="9" viewBox="0 0 10 10" fill="#F59E0B"><path d="M5 0L10 8H0L5 0Z"/></svg>
                        <span class="font-semibold text-amber-500">aguardam resposta</span>
                    @else
                        <span class="text-gray-400">nenhum pendente</span>
                    @endif
                </div>
            </div>

            {{-- Confirmadas --}}
            <div class="bg-white rounded-3xl p-4 hover:shadow-md transition" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <div class="flex items-start justify-between mb-2">
                    <p class="text-xs text-gray-500 font-medium leading-snug">Confirmadas</p>
                    <div class="w-7 h-7 rounded-full bg-gray-50 flex items-center justify-center flex-shrink-0">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#374151" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-[22px] font-bold text-gray-900 mb-2 leading-none">{{ $stats['confirmed'] }}</p>
                <div class="flex items-center gap-1 text-[11px]">
                    <svg width="9" height="9" viewBox="0 0 10 10" fill="#10B981"><path d="M5 0L10 8H0L5 0Z"/></svg>
                    <span class="font-semibold text-green-600">confirmadas</span>
                </div>
            </div>

            {{-- Este Mês --}}
            <div class="bg-white rounded-3xl p-4 hover:shadow-md transition" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <div class="flex items-start justify-between mb-2">
                    <p class="text-xs text-gray-500 font-medium leading-snug">Este Mês</p>
                    <div class="w-7 h-7 rounded-full bg-gray-50 flex items-center justify-center flex-shrink-0">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#374151" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7v10"/>
                        </svg>
                    </div>
                </div>
                <p class="text-[22px] font-bold text-gray-900 mb-2 leading-none">{{ $stats['this_month'] }}</p>
                <div class="flex items-center gap-1 text-[11px]">
                    @if($trend >= 0)
                        <svg width="9" height="9" viewBox="0 0 10 10" fill="#10B981"><path d="M5 0L10 8H0L5 0Z"/></svg>
                        <span class="font-semibold text-green-600">+{{ $trend }}%</span>
                    @else
                        <svg width="9" height="9" viewBox="0 0 10 10" fill="#EF4444"><path d="M5 10L0 2H10L5 10Z"/></svg>
                        <span class="font-semibold text-red-500">{{ $trend }}%</span>
                    @endif
                    <span class="text-gray-400">vs mês passado</span>
                </div>
            </div>

        </div>

        {{-- BAR CHART — Reservas por Mês --}}
        <div class="col-span-12 lg:col-span-5 bg-white rounded-3xl p-6 h-full flex flex-col" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
            <div class="flex items-start justify-between mb-2">
                <h3 class="text-base font-semibold text-gray-900">Reservas por Mês</h3>
            </div>
            <div class="flex items-center gap-3 mb-4">
                <p class="text-[34px] font-bold text-gray-900 leading-none">{{ $stats['total'] }}</p>
                <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full {{ $trend >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                    {{ $trend >= 0 ? '+' : '' }}{{ $trend }}%
                    <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $trend >= 0 ? 'M7 17L17 7M17 7H7M17 7v10' : 'M17 7L7 17M7 17h10M7 17V7' }}"/>
                    </svg>
                </span>
            </div>
            <div class="flex items-center gap-4 text-xs text-gray-500 mb-2 justify-end">
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full" style="background:#e5d5b0"></span>Meses anteriores
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full" style="background:#c99f5b"></span>Mês actual
                </span>
            </div>
            <div class="flex-1" style="min-height:180px">
                <canvas id="chartMonthly"></canvas>
            </div>
        </div>

        {{-- DOUGHNUT — Taxa de Confirmação --}}
        <div class="col-span-12 lg:col-span-3 bg-white rounded-3xl p-6 h-full flex flex-col" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
            <div class="flex items-start justify-between mb-2">
                <h3 class="text-base font-semibold text-gray-900">Taxa de Confirmação</h3>
            </div>
            <div class="flex flex-col items-center relative flex-1 justify-center">
                <div style="position:relative;width:100%;max-width:220px;margin:0 auto">
                    <canvas id="chartGauge"></canvas>
                    <div style="position:absolute;bottom:0;left:50%;transform:translateX(-50%);text-align:center;padding-bottom:8px">
                        <p class="text-3xl font-bold text-gray-900">{{ $confirmRate }}%</p>
                        <p class="text-xs text-gray-500 mt-0.5">confirmadas</p>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2 mt-4">
                <div class="bg-gray-50/50 rounded-2xl p-3">
                    <p class="text-[11px] text-gray-500">Confirmadas</p>
                    <div class="flex items-center justify-between mt-0.5">
                        <p class="text-sm font-bold text-gray-900">{{ $stats['confirmed'] }}</p>
                        <span class="bg-green-100 text-green-700 text-[10px] font-semibold px-1.5 py-0.5 rounded-full">ok</span>
                    </div>
                </div>
                <div class="bg-gray-50/50 rounded-2xl p-3">
                    <p class="text-[11px] text-gray-500">Pendentes</p>
                    <div class="flex items-center justify-between mt-0.5">
                        <p class="text-sm font-bold text-gray-900">{{ $stats['pending'] }}</p>
                        <span class="bg-amber-100 text-amber-600 text-[10px] font-semibold px-1.5 py-0.5 rounded-full">wait</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- RECENT RESERVATIONS TABLE --}}
        <div class="col-span-12 lg:col-span-8 bg-white rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
            <div class="flex flex-wrap items-center justify-between mb-5 gap-3">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Reservas Recentes</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Últimos pedidos recebidos</p>
                </div>
                <a href="{{ route('admin.reservas') }}" class="text-xs font-medium px-4 py-2 rounded-full transition hover:opacity-80" style="background:#c99f5b;color:#fff">
                    Ver todas
                </a>
            </div>

            @if($recent->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-14 h-14 rounded-full bg-gray-50 flex items-center justify-center mb-3">
                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500">Sem reservas ainda</p>
                    <p class="text-xs text-gray-400 mt-1">Aparecem aqui quando chegar o primeiro pedido</p>
                </div>
            @else
                <div class="overflow-x-auto -mx-2">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-400 text-xs">
                                <th class="font-medium px-2 py-3 bg-gray-50/60 first:rounded-l-xl">#</th>
                                <th class="font-medium px-2 py-3 bg-gray-50/60">Hóspede</th>
                                <th class="font-medium px-2 py-3 bg-gray-50/60">Experiência</th>
                                <th class="font-medium px-2 py-3 bg-gray-50/60">Check-in</th>
                                <th class="font-medium px-2 py-3 bg-gray-50/60">Check-out</th>
                                <th class="font-medium px-2 py-3 bg-gray-50/60">Estado</th>
                                <th class="font-medium px-2 py-3 bg-gray-50/60">Noites</th>
                                <th class="font-medium px-2 py-3 bg-gray-50/60 last:rounded-r-xl">Acção</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent as $r)
                                @php
                                    $st = $statusMap[$r->status] ?? ['label' => ucfirst($r->status), 'cls' => 'bg-gray-100 text-gray-600'];
                                    $nights = \Carbon\Carbon::parse($r->check_in)->diffInDays(\Carbon\Carbon::parse($r->check_out));
                                @endphp
                                <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition">
                                    <td class="px-2 py-4 text-gray-500 font-medium text-xs">#{{ $r->id }}</td>
                                    <td class="px-2 py-4">
                                        <div>
                                            <p class="text-gray-800 font-medium leading-tight">{{ $r->name }}</p>
                                            <p class="text-[11px] text-gray-400">{{ $r->guests }} hóspede{{ $r->guests > 1 ? 's' : '' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-2 py-4 text-gray-700">{{ $r->experience?->name_pt ?? '—' }}</td>
                                    <td class="px-2 py-4 text-gray-500 text-xs">{{ \Carbon\Carbon::parse($r->check_in)->format('d M Y') }}</td>
                                    <td class="px-2 py-4 text-gray-500 text-xs">{{ \Carbon\Carbon::parse($r->check_out)->format('d M Y') }}</td>
                                    <td class="px-2 py-4">
                                        <span class="text-xs font-medium px-3 py-1 rounded-full {{ $st['cls'] }}">{{ $st['label'] }}</span>
                                    </td>
                                    <td class="px-2 py-4 text-gray-700 font-semibold text-xs">{{ $nights }}n</td>
                                    <td class="px-2 py-4">
                                        <a href="{{ route('admin.reservas.show', $r) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium text-white transition hover:opacity-80"
                                           style="background:#c99f5b">
                                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- BY EXPERIENCE --}}
        <div class="col-span-12 lg:col-span-4 bg-white rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-900">Por Experiência</h3>
            </div>

            @php
                $expColors = ['#c99f5b', '#7C82F9', '#10B981', '#F59E0B'];
                $totalExp  = $byExperience->sum('reservations_count') ?: 1;
            @endphp

            <div class="space-y-4 mb-5">
                @foreach($byExperience as $idx => $exp)
                    @php
                        $pct   = round(($exp->reservations_count / $totalExp) * 100);
                        $color = $expColors[$idx % count($expColors)];
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-medium text-gray-700">{{ $exp->name_pt }}</span>
                            <span class="text-xs font-bold text-gray-900">{{ $exp->reservations_count }}</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700" style="width:{{ $pct }}%;background:{{ $color }}"></div>
                        </div>
                        <div class="flex gap-3 mt-1.5">
                            <span class="text-[10px] text-gray-400">✓ {{ $exp->confirmed_count }} conf.</span>
                            <span class="text-[10px] text-gray-400">⏳ {{ $exp->pending_count }} pend.</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="border-t border-gray-50 pt-4">
                <div class="grid grid-cols-3 gap-2 text-center">
                    @foreach([
                        ['label' => 'Total',     'value' => $stats['total'],     'color' => 'text-gray-900'],
                        ['label' => 'Confirm.',  'value' => $stats['confirmed'], 'color' => 'text-green-600'],
                        ['label' => 'Pendente',  'value' => $stats['pending'],   'color' => 'text-amber-500'],
                    ] as $s)
                        <div class="bg-gray-50/60 rounded-2xl p-3">
                            <p class="text-base font-bold {{ $s['color'] }}">{{ $s['value'] }}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $s['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>{{-- end grid --}}

    {{-- ═══════════════════════════════════════════════════════════════════
         ESTATÍSTICAS DO SITE
    ════════════════════════════════════════════════════════════════════ --}}
    <div class="pt-4 space-y-5">

        {{-- Section header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-[20px] font-bold text-gray-900">Estatísticas do Site</h2>
                <p class="text-sm text-gray-500 mt-0.5">Visitantes e páginas — últimos 30 dias</p>
            </div>
        </div>

        {{-- 4 KPI analytics cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach([
                ['label' => 'Hoje',              'value' => $analytics['today'],        'icon' => 'M12 3v1m0 16v1m8.66-9h-1M4.34 12H3m15.07-6.07l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707',    'color' => '#7C82F9'],
                ['label' => 'Esta Semana',       'value' => $analytics['week'],         'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',                                              'color' => '#c99f5b'],
                ['label' => 'Este Mês',          'value' => $analytics['month'],        'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z', 'color' => '#10B981'],
                ['label' => 'Visitantes Únicos', 'value' => $analytics['unique_month'], 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',                                                                    'color' => '#F59E0B'],
            ] as $kpi)
            <div class="bg-white rounded-3xl p-5 hover:shadow-md transition" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <div class="flex items-start justify-between mb-3">
                    <p class="text-xs text-gray-500 font-medium leading-snug">{{ $kpi['label'] }}</p>
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background:{{ $kpi['color'] }}18">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="{{ $kpi['color'] }}" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $kpi['icon'] }}"/>
                        </svg>
                    </div>
                </div>
                <p class="text-[26px] font-bold text-gray-900 leading-none">{{ number_format($kpi['value']) }}</p>
                <p class="text-[11px] text-gray-400 mt-1.5">visualizações</p>
            </div>
            @endforeach
        </div>

        {{-- Line chart + Sources --}}
        <div class="grid grid-cols-12 gap-5">

            {{-- 30-day line chart --}}
            <div class="col-span-12 lg:col-span-8 bg-white rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <div class="flex items-center justify-between mb-1">
                    <h3 class="text-base font-semibold text-gray-900">Tendência (30 dias)</h3>
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full" style="background:#c99f5b"></span>Visualizações
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full" style="background:#7C82F9"></span>Visitantes únicos
                        </span>
                    </div>
                </div>
                <div style="height:220px">
                    <canvas id="chartDailyViews"></canvas>
                </div>
            </div>

            {{-- Traffic sources --}}
            <div class="col-span-12 lg:col-span-4 bg-white rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Origem do Tráfego</h3>
                @if($sources->isEmpty())
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <p class="text-sm text-gray-400">Ainda sem dados</p>
                    </div>
                @else
                    <div style="max-width:180px;margin:0 auto 16px">
                        <canvas id="chartSources"></canvas>
                    </div>
                    @php $totalSrc = $sources->sum('views') ?: 1; @endphp
                    <div class="space-y-2">
                        @php $srcColors = ['#c99f5b','#7C82F9','#10B981','#F59E0B','#EF4444','#8B5CF6','#06B6D4']; @endphp
                        @foreach($sources as $idx => $src)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:{{ $srcColors[$idx % 7] }}"></span>
                                <span class="text-xs text-gray-600 truncate max-w-[110px]">{{ $src->source }}</span>
                            </div>
                            <span class="text-xs font-semibold text-gray-900">{{ number_format($src->views) }}</span>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        {{-- Top pages + Devices --}}
        <div class="grid grid-cols-12 gap-5">

            {{-- Top pages --}}
            <div class="col-span-12 lg:col-span-8 bg-white rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Páginas Mais Vistas</h3>
                @if($topPages->isEmpty())
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <p class="text-sm text-gray-400">Ainda sem dados de navegação</p>
                        <p class="text-xs text-gray-300 mt-1">As visitas ao site serão registadas automaticamente</p>
                    </div>
                @else
                    @php $maxViews = $topPages->max('views') ?: 1; @endphp
                    <div class="space-y-3">
                        @foreach($topPages as $page)
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm text-gray-700 font-medium">{{ $page->page_name }}</span>
                                <div class="flex items-center gap-3 text-xs text-gray-500">
                                    <span class="font-semibold text-gray-900">{{ number_format($page->views) }}</span>
                                    <span class="text-gray-300">|</span>
                                    <span>{{ number_format($page->uniq) }} únicos</span>
                                </div>
                            </div>
                            <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-700"
                                     style="width:{{ round(($page->views / $maxViews) * 100) }}%;background:#c99f5b"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Devices --}}
            <div class="col-span-12 lg:col-span-4 bg-white rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Dispositivos</h3>
                @if($devices->isEmpty())
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <p class="text-sm text-gray-400">Ainda sem dados</p>
                    </div>
                @else
                    @php
                        $devTotal  = $devices->sum('views') ?: 1;
                        $devLabels = ['desktop' => 'Computador', 'mobile' => 'Telemóvel', 'tablet' => 'Tablet'];
                        $devIcons  = [
                            'desktop' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                            'mobile'  => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
                            'tablet'  => 'M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z',
                        ];
                        $devColors = ['desktop' => '#c99f5b', 'mobile' => '#7C82F9', 'tablet' => '#10B981'];
                    @endphp
                    <div style="max-width:180px;margin:0 auto 20px">
                        <canvas id="chartDevices"></canvas>
                    </div>
                    <div class="space-y-3">
                        @foreach($devices as $dev)
                        @php
                            $pct  = round(($dev->views / $devTotal) * 100);
                            $col  = $devColors[$dev->device] ?? '#9ca3af';
                            $lbl  = $devLabels[$dev->device] ?? ucfirst($dev->device);
                            $icon = $devIcons[$dev->device] ?? 'M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2v-4M9 21H5a2 2 0 01-2-2v-4m0 0h18';
                        @endphp
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                                 style="background:{{ $col }}18">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="{{ $col }}" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-medium text-gray-700">{{ $lbl }}</span>
                                    <span class="text-xs font-semibold text-gray-900">{{ $pct }}%</span>
                                </div>
                                <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full" style="width:{{ $pct }}%;background:{{ $col }}"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>{{-- end analytics --}}

</div>{{-- end space-y-5 --}}
@endsection

@push('scripts')
<script>
(function() {
    // ── Bar chart ──
    const monthlyData = @json($monthly);
    const barColors   = monthlyData.map(m => m.current ? '#c99f5b' : '#e5d5b0');

    new Chart(document.getElementById('chartMonthly'), {
        type: 'bar',
        data: {
            labels:   monthlyData.map(m => m.label),
            datasets: [{
                data:            monthlyData.map(m => m.count),
                backgroundColor: barColors,
                borderRadius:    6,
                barThickness:    28,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.y} reserva${ctx.parsed.y !== 1 ? 's' : ''}`
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, border: { display: false }, ticks: { color: '#9ca3af', font: { size: 12 } } },
                y: { grid: { color: '#f3f4f6' }, border: { display: false }, ticks: { color: '#9ca3af', font: { size: 11 }, stepSize: 1 } }
            }
        }
    });

    // ── Gauge (half doughnut) ──
    const rate = {{ $confirmRate }};
    new Chart(document.getElementById('chartGauge'), {
        type: 'doughnut',
        data: {
            datasets: [{
                data:            [rate, 100 - rate],
                backgroundColor: ['#c99f5b', '#e5d5b0'],
                borderWidth:     0,
            }]
        },
        options: {
            circumference: 180,
            rotation:      -90,
            cutout:        '65%',
            plugins: { legend: { display: false }, tooltip: { enabled: false } },
            animation:     { animateRotate: true }
        }
    });

    // ── Site analytics charts ──
    const dailyData = @json($dailyViews);

    new Chart(document.getElementById('chartDailyViews'), {
        type: 'line',
        data: {
            labels:   dailyData.map(d => d.date),
            datasets: [
                {
                    label:           'Visualizações',
                    data:            dailyData.map(d => d.views),
                    borderColor:     '#c99f5b',
                    backgroundColor: 'rgba(201,159,91,0.08)',
                    borderWidth:     2,
                    pointRadius:     0,
                    pointHoverRadius:4,
                    fill:            true,
                    tension:         0.4,
                },
                {
                    label:           'Visitantes únicos',
                    data:            dailyData.map(d => d.unique),
                    borderColor:     '#7C82F9',
                    backgroundColor: 'rgba(124,130,249,0.06)',
                    borderWidth:     1.5,
                    pointRadius:     0,
                    pointHoverRadius:4,
                    fill:            true,
                    tension:         0.4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: ctx => ctx[0].label,
                        label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y}`
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: {
                        color: '#9ca3af',
                        font: { size: 11 },
                        maxTicksLimit: 8,
                    }
                },
                y: {
                    grid: { color: '#f3f4f6' },
                    border: { display: false },
                    ticks: { color: '#9ca3af', font: { size: 11 }, stepSize: 1 },
                    beginAtZero: true,
                }
            }
        }
    });

    @if($sources->isNotEmpty())
    const srcColors = ['#c99f5b','#7C82F9','#10B981','#F59E0B','#EF4444','#8B5CF6','#06B6D4'];
    const srcData   = @json($sources->values());
    new Chart(document.getElementById('chartSources'), {
        type: 'doughnut',
        data: {
            labels:   srcData.map(s => s.source),
            datasets: [{ data: srcData.map(s => s.views), backgroundColor: srcColors, borderWidth: 0 }]
        },
        options: {
            cutout: '60%',
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed}` } } },
        }
    });
    @endif

    @if($devices->isNotEmpty())
    const devColors = { desktop: '#c99f5b', mobile: '#7C82F9', tablet: '#10B981' };
    const devData   = @json($devices->values());
    new Chart(document.getElementById('chartDevices'), {
        type: 'doughnut',
        data: {
            labels:   devData.map(d => d.device),
            datasets: [{ data: devData.map(d => d.views), backgroundColor: devData.map(d => devColors[d.device] || '#9ca3af'), borderWidth: 0 }]
        },
        options: {
            cutout: '60%',
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ` ${ctx.parsed}` } } },
        }
    });
    @endif
})();
</script>
<style>
@keyframes fhWave {
    0%,60%,100% { transform:rotate(0deg); }
    10%,30%     { transform:rotate(14deg); }
    20%         { transform:rotate(-8deg); }
    40%         { transform:rotate(-4deg); }
    50%         { transform:rotate(10deg); }
}
</style>
@endpush
