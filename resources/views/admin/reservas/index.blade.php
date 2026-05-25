@extends('layouts.admin')

@section('title', 'Reservas')

@push('head')
<style>[x-cloak]{display:none!important}</style>
@endpush

@section('content')
@php
    $statusMap = [
        'pending'   => ['label' => 'Pendente',   'cls' => 'bg-amber-50 text-amber-600 border border-amber-100'],
        'confirmed' => ['label' => 'Confirmado', 'cls' => 'bg-green-50 text-green-600 border border-green-100'],
        'cancelled' => ['label' => 'Cancelado',  'cls' => 'bg-red-50 text-red-600 border border-red-100'],
    ];
    $activeStatus = request('status', '');
    $searchUrl    = route('admin.api.reservas.search');
@endphp

<div class="space-y-5 pt-2" x-data="{
    q:        {{ Js::from(request('search', '')) }},
    expId:    {{ Js::from(request('experience_id', '')) }},
    dateFrom: {{ Js::from(request('date_from', '')) }},
    dateTo:   {{ Js::from(request('date_to', '')) }},
    status:   {{ Js::from(request('status', '')) }},
    loading:  false,
    rows:     null,
    _t:       null,
    modal:    { open: false, id: null, name: '', label: '', action: '', url: '' },

    search(fast) {
        clearTimeout(this._t);
        const hasFilter = this.q.trim() || this.expId || this.dateFrom || this.dateTo;
        if (!hasFilter) { this.rows = null; return; }
        this.loading = true;
        this._t = setTimeout(() => this._fetch(), fast ? 0 : 420);
    },

    async _fetch() {
        const p = new URLSearchParams({
            q:             this.q.trim(),
            status:        this.status,
            experience_id: this.expId,
            date_from:     this.dateFrom,
            date_to:       this.dateTo,
        });
        try {
            const r = await fetch('{{ $searchUrl }}?' + p, { headers: { Accept: 'application/json' } });
            this.rows = await r.json();
        } catch(e) { this.rows = []; }
        this.loading = false;
    },

    clear() {
        this.q = ''; this.expId = ''; this.dateFrom = ''; this.dateTo = '';
        this.rows = null;
    },

    sCls(s) {
        return ({
            pending:   'bg-amber-50 text-amber-600 border border-amber-100',
            confirmed: 'bg-green-50 text-green-600 border border-green-100',
            cancelled: 'bg-red-50 text-red-600 border border-red-100',
        })[s] || 'bg-gray-100 text-gray-600';
    },
    sLabel(s) {
        return ({ pending: 'Pendente', confirmed: 'Confirmado', cancelled: 'Cancelado' })[s] || s;
    },
    openModal(r, action, label) {
        this.modal = { open: true, id: r.id, name: r.name, label, action, url: r.status_url };
    }
}">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-[22px] font-bold text-gray-900">Reservas</h1>
            <p class="text-sm text-gray-500">Gestão de todos os pedidos de reserva</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-100 rounded-2xl px-4 py-3 flex items-center gap-3">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Status pills --}}
    <div class="flex items-center gap-2 flex-wrap">
        @php
            $pills = [
                ''          => ['label' => 'Todas',      'count' => $counts['all']],
                'pending'   => ['label' => 'Pendentes',  'count' => $counts['pending']],
                'confirmed' => ['label' => 'Confirmadas','count' => $counts['confirmed']],
                'cancelled' => ['label' => 'Canceladas', 'count' => $counts['cancelled']],
            ];
        @endphp
        @foreach($pills as $val => $pill)
            @php $isActive = $activeStatus === $val; @endphp
            <a href="{{ request()->fullUrlWithQuery(['status' => $val, 'page' => null, 'search' => null]) }}"
               class="flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-medium transition"
               style="{{ $isActive ? 'background:#c99f5b;color:#fff' : 'background:#fff;color:#374151;box-shadow:0 1px 3px rgba(0,0,0,0.06)' }}">
                {{ $pill['label'] }}
                <span class="text-xs px-1.5 py-0.5 rounded-full font-semibold"
                      style="{{ $isActive ? 'background:rgba(255,255,255,.25);color:#fff' : 'background:#f3f4f6;color:#6b7280' }}">
                    {{ $pill['count'] }}
                </span>
            </a>
        @endforeach
    </div>

    {{-- Filters (live search) --}}
    <div class="bg-white rounded-3xl p-4" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
        <div class="grid grid-cols-12 gap-3">

            {{-- Text search --}}
            <div class="col-span-12 md:col-span-4 relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input
                    type="text"
                    name="search"
                    x-model="q"
                    @input.debounce.400ms="search()"
                    placeholder="Nome, email, telefone, #código..."
                    class="w-full rounded-2xl border border-gray-200 pl-10 pr-4 py-2.5 text-sm text-gray-900 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition"
                >
            </div>

            {{-- Experience --}}
            <div class="col-span-12 md:col-span-3">
                <select name="experience_id" x-model="expId" @change="search(true)"
                        class="w-full rounded-2xl border border-gray-200 px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition bg-white">
                    <option value="">Todas as experiências</option>
                    @foreach($experiences as $exp)
                        <option value="{{ $exp->id }}" {{ request('experience_id') == $exp->id ? 'selected' : '' }}>
                            {{ $exp->name_pt }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Date from --}}
            <div class="col-span-6 md:col-span-2">
                <input type="date" name="date_from" x-model="dateFrom" @change="search(true)"
                       value="{{ request('date_from') }}"
                       class="w-full rounded-2xl border border-gray-200 px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition">
            </div>

            {{-- Date to --}}
            <div class="col-span-6 md:col-span-2">
                <input type="date" name="date_to" x-model="dateTo" @change="search(true)"
                       value="{{ request('date_to') }}"
                       class="w-full rounded-2xl border border-gray-200 px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition">
            </div>

            {{-- Clear --}}
            <div class="col-span-12 md:col-span-1 flex gap-2">
                <button type="button" @click="clear()"
                        x-show="q || expId || dateFrom || dateTo || rows !== null"
                        class="flex-1 flex items-center justify-center rounded-2xl border border-gray-200 text-gray-400 hover:bg-gray-50 transition"
                        title="Limpar filtros">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Loading bar --}}
        <div x-show="loading" x-cloak class="mt-3 h-0.5 rounded-full overflow-hidden bg-gray-100">
            <div class="h-full rounded-full animate-pulse" style="background:#c99f5b;width:60%"></div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-3xl overflow-hidden" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">

        {{-- Empty state (PHP no results) --}}
        @if($reservations->isEmpty())
        <div x-show="rows === null">
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-14 h-14 rounded-full flex items-center justify-center mb-4" style="background:#fdf8f0">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#c99f5b" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-900">Nenhuma reserva encontrada</p>
                <p class="text-xs text-gray-400 mt-1">Tenta ajustar os filtros.</p>
            </div>
        </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead x-show="rows !== null || {{ $reservations->isNotEmpty() ? 'true' : 'false' }}">
                    <tr class="border-b border-gray-100">
                        <th class="text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">#</th>
                        <th class="text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-4 py-4">Hóspede</th>
                        <th class="text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-4 py-4 hidden md:table-cell">Experiência</th>
                        <th class="text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-4 py-4">Check-in</th>
                        <th class="text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-4 py-4 hidden lg:table-cell">Check-out</th>
                        <th class="text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-4 py-4 hidden lg:table-cell">Hósp.</th>
                        <th class="text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-4 py-4">Estado</th>
                        <th class="text-right text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Acções</th>
                    </tr>
                </thead>

                {{-- ── Alpine live-search results ── --}}
                <tbody x-show="rows !== null" x-cloak class="divide-y divide-gray-50">
                    <template x-for="r in (rows || [])" :key="r.id">
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-sm font-bold text-gray-900" x-text="'#'+r.id"></span>
                                    <template x-if="r.is_nova">
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-emerald-500 text-white tracking-wide">Nova</span>
                                    </template>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-sm font-semibold text-gray-900" x-text="r.name"></p>
                                <p class="text-xs text-gray-400" x-text="r.email"></p>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-700 hidden md:table-cell" x-text="r.experience"></td>
                            <td class="px-4 py-4 text-sm text-gray-700" x-text="r.check_in"></td>
                            <td class="px-4 py-4 text-sm text-gray-700 hidden lg:table-cell" x-text="r.check_out"></td>
                            <td class="px-4 py-4 text-sm text-gray-700 text-center hidden lg:table-cell" x-text="r.guests"></td>
                            <td class="px-4 py-4">
                                <span class="text-xs font-semibold px-3 py-1 rounded-full"
                                      :class="sCls(r.status)"
                                      x-text="sLabel(r.status)"></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a :href="r.url"
                                       class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-white transition hover:opacity-80"
                                       style="background:#c99f5b">
                                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Ver
                                    </a>
                                    <template x-if="r.status !== 'confirmed'">
                                        <button @click="openModal(r, 'confirmed', 'confirmar')"
                                                class="flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-semibold text-white bg-green-500 hover:bg-green-600 transition">
                                            <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Confirmar
                                        </button>
                                    </template>
                                    <template x-if="r.status !== 'cancelled'">
                                        <button @click="openModal(r, 'cancelled', 'cancelar')"
                                                class="flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 border border-red-100 transition">
                                            <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Cancelar
                                        </button>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </template>

                    {{-- No live results --}}
                    <tr x-show="rows !== null && rows.length === 0">
                        <td colspan="8">
                            <div class="flex flex-col items-center justify-center py-14 text-center">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center mb-3" style="background:#fdf8f0">
                                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#c99f5b" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-900">Nenhum resultado</p>
                                <p class="text-xs text-gray-400 mt-1">Tenta outros termos de pesquisa.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>

                {{-- ── PHP server-rendered results ── --}}
                @if($reservations->isNotEmpty())
                <tbody x-show="rows === null" class="divide-y divide-gray-50">
                    @foreach($reservations as $r)
                    @php
                        $st     = $statusMap[$r->status] ?? ['label' => ucfirst($r->status), 'cls' => 'bg-gray-100 text-gray-600'];
                        $isNova = $r->created_at->gt(now()->subMinutes(30)) && is_null($r->viewed_at);
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition" x-data="{ confirmModal: false, newStatus: '', newLabel: '' }">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5">
                                <span class="text-sm font-bold text-gray-900">#{{ $r->id }}</span>
                                @if($isNova)
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-emerald-500 text-white tracking-wide animate-pulse">Nova</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <p class="text-sm font-semibold text-gray-900">{{ $r->name }}</p>
                            <p class="text-xs text-gray-400">{{ $r->email }}</p>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-700 hidden md:table-cell">{{ $r->experience?->name_pt ?? '—' }}</td>
                        <td class="px-4 py-4 text-sm text-gray-700">{{ $r->check_in->format('d/m/Y') }}</td>
                        <td class="px-4 py-4 text-sm text-gray-700 hidden lg:table-cell">{{ $r->check_out->format('d/m/Y') }}</td>
                        <td class="px-4 py-4 text-sm text-gray-700 text-center hidden lg:table-cell">{{ $r->guests }}</td>
                        <td class="px-4 py-4">
                            <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $st['cls'] }}">{{ $st['label'] }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.reservas.show', $r) }}"
                                   class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-white transition hover:opacity-80"
                                   style="background:#c99f5b">
                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Ver
                                </a>
                                @if($r->status !== 'confirmed')
                                <button @click="newStatus='confirmed'; newLabel='confirmar'; confirmModal=true"
                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-white bg-green-500 hover:bg-green-600 transition">
                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Confirmar
                                </button>
                                @endif
                                @if($r->status !== 'cancelled')
                                <button @click="newStatus='cancelled'; newLabel='cancelar'; confirmModal=true"
                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 border border-red-100 transition">
                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Cancelar
                                </button>
                                @endif
                            </div>

                            {{-- Per-row modal (PHP rows) --}}
                            <div x-show="confirmModal" x-cloak
                                 class="fixed inset-0 z-50 flex items-center justify-center px-4"
                                 @keydown.escape.window="confirmModal=false">
                                <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" @click="confirmModal=false"></div>
                                <div class="relative bg-white rounded-3xl shadow-xl p-6 w-full max-w-sm z-10">
                                    <h3 class="text-base font-bold text-gray-900 mb-2">Confirmas a acção?</h3>
                                    <p class="text-sm text-gray-500 mb-5">
                                        Vais <span class="font-semibold text-gray-700" x-text="newLabel"></span> a reserva <strong>#{{ $r->id }}</strong>.
                                        O hóspede receberá um email de notificação.
                                    </p>
                                    <div class="flex gap-3">
                                        <button @click="confirmModal=false"
                                                class="flex-1 px-4 py-2.5 rounded-2xl text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                                            Cancelar
                                        </button>
                                        <form method="POST" action="{{ route('admin.reservas.status', $r) }}" class="flex-1">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" :value="newStatus">
                                            <button type="submit"
                                                    class="w-full px-4 py-2.5 rounded-2xl text-sm font-semibold text-white transition hover:opacity-90"
                                                    style="background:#c99f5b">
                                                Confirmar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                @endif
            </table>
        </div>

        {{-- Pagination (only for PHP rows) --}}
        @if($reservations->hasPages())
        <div x-show="rows === null" class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-xs text-gray-400">
                A mostrar {{ $reservations->firstItem() }}–{{ $reservations->lastItem() }} de {{ $reservations->total() }} reservas
            </p>
            <div class="flex items-center gap-1">
                @if($reservations->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-xl text-gray-300 cursor-not-allowed">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </span>
                @else
                    <a href="{{ $reservations->previousPageUrl() }}"
                       class="w-8 h-8 flex items-center justify-center rounded-xl text-gray-500 hover:bg-gray-100 transition">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                @endif
                @foreach($reservations->getUrlRange(max(1, $reservations->currentPage()-2), min($reservations->lastPage(), $reservations->currentPage()+2)) as $page => $url)
                    @if($page == $reservations->currentPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-xl text-xs font-bold text-white" style="background:#c99f5b">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-xl text-xs font-medium text-gray-600 hover:bg-gray-100 transition">{{ $page }}</a>
                    @endif
                @endforeach
                @if($reservations->hasMorePages())
                    <a href="{{ $reservations->nextPageUrl() }}"
                       class="w-8 h-8 flex items-center justify-center rounded-xl text-gray-500 hover:bg-gray-100 transition">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded-xl text-gray-300 cursor-not-allowed">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </span>
                @endif
            </div>
        </div>
        @endif

    </div>{{-- /Table card --}}

    {{-- Shared modal for live-search status change --}}
    <div x-show="modal.open" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         @keydown.escape.window="modal.open=false">
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" @click="modal.open=false"></div>
        <div class="relative bg-white rounded-3xl shadow-xl p-6 w-full max-w-sm z-10">
            <h3 class="text-base font-bold text-gray-900 mb-2">Confirmas a acção?</h3>
            <p class="text-sm text-gray-500 mb-5">
                Vais <span class="font-semibold text-gray-700" x-text="modal.label"></span> a reserva
                <strong x-text="'#'+modal.id"></strong>.
                O hóspede receberá um email de notificação.
            </p>
            <div class="flex gap-3">
                <button @click="modal.open=false"
                        class="flex-1 px-4 py-2.5 rounded-2xl text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                    Fechar
                </button>
                <form method="POST" :action="modal.url" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" :value="modal.action">
                    <button type="submit"
                            class="w-full px-4 py-2.5 rounded-2xl text-sm font-semibold text-white transition hover:opacity-90"
                            style="background:#c99f5b">
                        Confirmar
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
