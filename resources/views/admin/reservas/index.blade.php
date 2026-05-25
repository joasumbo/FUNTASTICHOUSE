@extends('layouts.admin')

@section('title', 'Reservas')

@section('content')
@php
    $statusMap = [
        'pending'   => ['label' => 'Pendente',   'cls' => 'bg-amber-50 text-amber-600 border border-amber-100'],
        'confirmed' => ['label' => 'Confirmado', 'cls' => 'bg-green-50 text-green-600 border border-green-100'],
        'cancelled' => ['label' => 'Cancelado',  'cls' => 'bg-red-50 text-red-600 border border-red-100'],
    ];
    $activeStatus = request('status', '');
@endphp

<div class="space-y-5 pt-2">

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
                '' => ['label' => 'Todas', 'count' => $counts['all']],
                'pending'   => ['label' => 'Pendentes',   'count' => $counts['pending']],
                'confirmed' => ['label' => 'Confirmadas', 'count' => $counts['confirmed']],
                'cancelled' => ['label' => 'Canceladas',  'count' => $counts['cancelled']],
            ];
        @endphp
        @foreach($pills as $val => $pill)
            @php
                $isActive = $activeStatus === $val;
                $href = request()->fullUrlWithQuery(['status' => $val, 'page' => null]);
            @endphp
            <a href="{{ $href }}"
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

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.reservas') }}" class="bg-white rounded-3xl p-4" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
        @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
        <div class="grid grid-cols-12 gap-3">
            <div class="col-span-12 md:col-span-4">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Pesquisar por nome, email ou telefone..."
                       class="w-full rounded-2xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition">
            </div>
            <div class="col-span-12 md:col-span-3">
                <select name="experience_id"
                        class="w-full rounded-2xl border border-gray-200 px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition bg-white">
                    <option value="">Todas as experiências</option>
                    @foreach($experiences as $exp)
                        <option value="{{ $exp->id }}" {{ request('experience_id') == $exp->id ? 'selected' : '' }}>
                            {{ $exp->name_pt }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-6 md:col-span-2">
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full rounded-2xl border border-gray-200 px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition"
                       placeholder="Check-in de">
            </div>
            <div class="col-span-6 md:col-span-2">
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full rounded-2xl border border-gray-200 px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition"
                       placeholder="Check-in até">
            </div>
            <div class="col-span-12 md:col-span-1 flex gap-2">
                <button type="submit"
                        class="flex-1 rounded-2xl py-2.5 text-sm font-semibold text-white transition hover:opacity-90"
                        style="background:#c99f5b">
                    Filtrar
                </button>
                @if(request()->hasAny(['search','experience_id','date_from','date_to']))
                <a href="{{ route('admin.reservas', request()->only('status')) }}"
                   class="flex items-center justify-center w-10 rounded-2xl border border-gray-200 text-gray-400 hover:bg-gray-50 transition">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
                @endif
            </div>
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-3xl overflow-hidden" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
        @if($reservations->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-14 h-14 rounded-full flex items-center justify-center mb-4" style="background:#fdf8f0">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#c99f5b" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-900">Nenhuma reserva encontrada</p>
                <p class="text-xs text-gray-400 mt-1">Tenta ajustar os filtros.</p>
            </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">#</th>
                        <th class="text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-4 py-4">Hóspede</th>
                        <th class="text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-4 py-4">Experiência</th>
                        <th class="text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-4 py-4">Check-in</th>
                        <th class="text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-4 py-4">Check-out</th>
                        <th class="text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-4 py-4">Hósp.</th>
                        <th class="text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-4 py-4">Estado</th>
                        <th class="text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-4 py-4">Criado em</th>
                        <th class="text-right text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Acções</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($reservations as $r)
                    @php $st = $statusMap[$r->status] ?? ['label' => ucfirst($r->status), 'cls' => 'bg-gray-100 text-gray-600']; @endphp
                    <tr class="hover:bg-gray-50/50 transition" x-data="{ confirmModal: false, newStatus: '', newLabel: '' }">
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">#{{ $r->id }}</td>
                        <td class="px-4 py-4">
                            <p class="text-sm font-semibold text-gray-900">{{ $r->name }}</p>
                            <p class="text-xs text-gray-400">{{ $r->email }}</p>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-700">{{ $r->experience?->name_pt ?? '—' }}</td>
                        <td class="px-4 py-4 text-sm text-gray-700">{{ $r->check_in->format('d/m/Y') }}</td>
                        <td class="px-4 py-4 text-sm text-gray-700">{{ $r->check_out->format('d/m/Y') }}</td>
                        <td class="px-4 py-4 text-sm text-gray-700 text-center">{{ $r->guests }}</td>
                        <td class="px-4 py-4">
                            <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $st['cls'] }}">{{ $st['label'] }}</span>
                        </td>
                        <td class="px-4 py-4 text-xs text-gray-400">{{ $r->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Ver --}}
                                <a href="{{ route('admin.reservas.show', $r) }}"
                                   class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-white transition hover:opacity-80"
                                   style="background:#c99f5b">
                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Ver
                                </a>

                                {{-- Confirmar (only if not confirmed) --}}
                                @if($r->status !== 'confirmed')
                                <button @click="newStatus='confirmed'; newLabel='confirmar'; confirmModal=true"
                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-white bg-green-500 hover:bg-green-600 transition">
                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Confirmar
                                </button>
                                @endif

                                {{-- Cancelar (only if not cancelled) --}}
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

                            {{-- Confirmation modal --}}
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
                                        <form method="POST"
                                              action="{{ route('admin.reservas.status', $r) }}"
                                              class="flex-1">
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
            </table>
        </div>

        {{-- Pagination --}}
        @if($reservations->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
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
                        <span class="w-8 h-8 flex items-center justify-center rounded-xl text-xs font-bold text-white"
                              style="background:#c99f5b">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                           class="w-8 h-8 flex items-center justify-center rounded-xl text-xs font-medium text-gray-600 hover:bg-gray-100 transition">{{ $page }}</a>
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
        @endif
    </div>

</div>

@push('head')
<style>[x-cloak]{display:none!important}</style>
@endpush
@endsection
