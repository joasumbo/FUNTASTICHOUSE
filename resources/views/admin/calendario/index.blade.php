@extends('layouts.admin')
@section('title', 'Calendário')

@section('content')
<div
    x-data="calendarApp()"
    x-init="init()"
    @click.window="closeCtx()"
    @keydown.escape.window="closeCtx(); dialog.show = false"
    class="flex flex-col gap-4 pt-2"
    style="height: calc(100vh - 105px)">

    {{-- ── TOP BAR ─────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 flex-shrink-0">
        <div>
            <h1 class="text-[22px] font-bold text-gray-900">Calendário</h1>
            <p class="text-sm text-gray-500">Gestão de disponibilidade por experiência</p>
        </div>

        {{-- Experience tabs --}}
        <div class="flex items-center gap-2 p-1 bg-white rounded-2xl" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
            @foreach($experiences as $exp)
            <button
                @click="switchExperience({{ $exp->id }})"
                :class="experienceId === {{ $exp->id }}
                    ? 'text-white shadow-sm'
                    : 'text-gray-600 hover:bg-gray-50'"
                :style="experienceId === {{ $exp->id }} ? 'background:#c99f5b' : ''"
                class="px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200">
                {{ $exp->name_pt }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- ── CALENDAR CARD ───────────────────────────────────────── --}}
    <div class="bg-white rounded-3xl flex flex-col flex-1 min-h-0 overflow-hidden"
         style="box-shadow:0 1px 8px rgba(0,0,0,0.07)">

        {{-- Month navigation --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 flex-shrink-0">
            <button
                @click="prevMonth()"
                :disabled="animating"
                class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-150 hover:bg-gray-100 active:scale-95 disabled:opacity-40">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#374151" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <div class="flex flex-col items-center gap-0.5">
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-bold text-gray-900 capitalize leading-tight"
                        x-text="monthName + ' ' + year"></h2>
                    <button
                        @click="goToday()"
                        class="text-xs font-semibold px-3 py-1.5 rounded-xl border border-gray-200 text-gray-500 hover:border-[#c99f5b] hover:text-[#c99f5b] transition-all duration-150">
                        Hoje
                    </button>
                </div>
                <div x-show="!loading" class="flex items-center gap-2 text-[11px] text-gray-400 h-4">
                    <template x-if="monthResCount > 0">
                        <span>
                            <span class="font-semibold text-gray-500" x-text="monthResCount"></span>
                            <span x-text="' reserva' + (monthResCount !== 1 ? 's' : '')"></span>
                        </span>
                    </template>
                    <template x-if="blocked.filter(d => d.startsWith(year+'-'+String(month).padStart(2,'0'))).length > 0">
                        <span class="flex items-center gap-1">
                            <span class="text-gray-300" x-show="monthResCount > 0">·</span>
                            <span class="font-semibold text-gray-500" x-text="blocked.filter(d => d.startsWith(year+'-'+String(month).padStart(2,'0'))).length"></span>
                            dia(s) bloqueado(s)
                        </span>
                    </template>
                    <template x-if="monthResCount === 0 && blocked.filter(d => d.startsWith(year+'-'+String(month).padStart(2,'0'))).length === 0">
                        <span class="text-gray-300">Mês livre</span>
                    </template>
                </div>
            </div>

            <button
                @click="nextMonth()"
                :disabled="animating"
                class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-150 hover:bg-gray-100 active:scale-95 disabled:opacity-40">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#374151" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        {{-- Day headers --}}
        <div class="grid grid-cols-7 border-b border-gray-100 flex-shrink-0">
            @foreach(['Seg','Ter','Qua','Qui','Sex','Sáb','Dom'] as $i => $d)
            <div class="text-center py-2.5 text-[11px] font-bold uppercase tracking-widest
                        {{ $i >= 5 ? 'text-[#c99f5b]' : 'text-gray-400' }}">
                {{ $d }}
            </div>
            @endforeach
        </div>

        {{-- Calendar grid --}}
        <div class="relative flex-1 min-h-0 overflow-hidden">

            {{-- Loading overlay --}}
            <div
                x-show="loading"
                x-transition:enter="transition duration-150"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-white/80 backdrop-blur-sm z-20 flex items-center justify-center">
                <div class="flex items-center gap-2 text-gray-400">
                    <svg class="animate-spin" width="20" height="20" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                    <span class="text-sm font-medium">A carregar...</span>
                </div>
            </div>

            {{-- Grid cells --}}
            <div
                :class="animClass"
                :style="`grid-template-rows: repeat(${Math.ceil(days.length/7)}, 1fr)`"
                class="grid grid-cols-7 h-full">

                <template x-for="(cell, idx) in days" :key="'c' + idx">
                    <div
                        :class="cellCls(cell)"
                        @contextmenu.prevent="cell && !isPast(cell) && onRightClick($event, cell)"
                        @dblclick.prevent="cell && !isPast(cell) && openDialog(cell)"
                        @click.prevent>

                        <template x-if="cell">
                            <div class="h-full flex flex-col p-2 select-none">
                                {{-- Row: day number + icon --}}
                                <div class="flex items-start justify-between">
                                    <span
                                        :class="[
                                            'text-sm font-bold w-7 h-7 flex items-center justify-center rounded-full leading-none transition-all duration-150',
                                            isToday(cell) ? 'text-white' : '',
                                            isToday(cell) ? '' : (isBlocked(cell) ? 'text-white/90' : ''),
                                            getRes(cell) && !isBlocked(cell) ? (getRes(cell).status === 'confirmed' ? 'text-green-700' : 'text-amber-700') : '',
                                            !isToday(cell) && !isBlocked(cell) && !getRes(cell) ? 'text-gray-800' : '',
                                        ]"
                                        :style="isToday(cell) ? 'background:#c99f5b' : ''"
                                        x-text="cell.day">
                                    </span>

                                    {{-- Status icons --}}
                                    <template x-if="isBlocked(cell)">
                                        <svg class="opacity-60 mt-0.5 flex-shrink-0" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </template>
                                    <template x-if="!isBlocked(cell) && getRes(cell) && getRes(cell).status === 'confirmed'">
                                        <svg class="mt-0.5 flex-shrink-0 text-green-500" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </template>
                                    <template x-if="!isBlocked(cell) && getRes(cell) && getRes(cell).status === 'pending'">
                                        <svg class="mt-0.5 flex-shrink-0 text-amber-500" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </template>
                                </div>

                                {{-- Reservation: name only on first visible day of the period --}}
                                <template x-if="!isBlocked(cell) && getRes(cell) && isResStart(cell)">
                                    <div class="mt-1 flex-1 overflow-hidden">
                                        <p
                                            :class="getRes(cell).status === 'confirmed' ? 'text-green-700' : 'text-amber-700'"
                                            class="text-[10px] font-semibold leading-tight truncate"
                                            x-text="getRes(cell).name.split(' ')[0]">
                                        </p>
                                        <p
                                            :class="getRes(cell).status === 'confirmed' ? 'text-green-400' : 'text-amber-400'"
                                            class="text-[9px] leading-tight"
                                            x-text="'#' + getRes(cell).id">
                                        </p>
                                    </div>
                                </template>

                                {{-- Continuation day: subtle bar at bottom --}}
                                <template x-if="!isBlocked(cell) && getRes(cell) && !isResStart(cell)">
                                    <div class="absolute bottom-0 left-0 right-0 h-0.5 opacity-40 rounded-full"
                                         :class="getRes(cell).status === 'confirmed' ? 'bg-green-500' : 'bg-amber-500'">
                                    </div>
                                </template>

                                {{-- Blocked label --}}
                                <template x-if="isBlocked(cell)">
                                    <p class="mt-1 text-[9px] text-white/50 font-medium uppercase tracking-wide leading-tight">Bloqueado</p>
                                </template>

                                {{-- Hover hint for available dates --}}
                                <template x-if="!isBlocked(cell) && !getRes(cell) && !isPast(cell) && !isToday(cell)">
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                                        <span class="text-[9px] text-gray-300 font-medium">2× / btn dir.</span>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        {{-- Legend --}}
        <div class="border-t border-gray-100 px-6 py-3 flex flex-wrap items-center gap-4 flex-shrink-0">
            <div class="flex items-center gap-1.5">
                <div class="w-3 h-3 rounded-sm bg-white border border-gray-200"></div>
                <span class="text-[11px] text-gray-500">Disponível</span>
            </div>
            <div class="flex items-center gap-1.5">
                <div class="w-3 h-3 rounded-sm bg-gray-900"></div>
                <span class="text-[11px] text-gray-500">Bloqueado (admin)</span>
            </div>
            <div class="flex items-center gap-1.5">
                <div class="w-3 h-3 rounded-sm bg-green-100 border border-green-200"></div>
                <span class="text-[11px] text-gray-500">Reserva confirmada</span>
            </div>
            <div class="flex items-center gap-1.5">
                <div class="w-3 h-3 rounded-sm bg-amber-100 border border-amber-200"></div>
                <span class="text-[11px] text-gray-500">Reserva pendente</span>
            </div>
            <div class="ml-auto flex items-center gap-1.5 text-[11px] text-gray-400">
                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Duplo clique ou botão direito para gerir
            </div>
        </div>
    </div>

    {{-- ── CONTEXT MENU ────────────────────────────────────────── --}}
    <div
        x-show="ctx.show"
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        :style="`position:fixed;top:${ctx.y}px;left:${ctx.x}px;z-index:999`"
        @click.stop
        class="bg-white rounded-2xl py-1.5 w-52 origin-top-left"
        style="box-shadow:0 8px 30px rgba(0,0,0,0.14);border:1px solid rgba(0,0,0,0.06)">

        {{-- Blocked date: unblock options --}}
        <template x-if="ctx.cell && isBlocked(ctx.cell)">
            <div>
                <div class="px-3 py-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Data bloqueada</div>
                <button
                    @click="doUnblock([ctx.cell.date])"
                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2.5 transition-colors">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#c99f5b" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                    </svg>
                    Desbloquear este dia
                </button>
                <button
                    @click="doUnblock(weekDates(ctx.cell))"
                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2.5 transition-colors">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#c99f5b" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Desbloquear semana
                </button>
            </div>
        </template>

        {{-- Available date: block options --}}
        <template x-if="ctx.cell && !isBlocked(ctx.cell) && !getRes(ctx.cell)">
            <div>
                <div class="px-3 py-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Bloquear</div>
                <button
                    @click="doBlock([ctx.cell.date])"
                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2.5 transition-colors">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#374151" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Bloquear este dia
                </button>
                <button
                    @click="doBlock(weekDates(ctx.cell))"
                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2.5 transition-colors">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#374151" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Bloquear semana
                </button>
                <button
                    @click="doBlock(monthDates())"
                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-red-50 flex items-center gap-2.5 transition-colors">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#ef4444" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                    <span class="text-red-500">Bloquear todo o mês</span>
                </button>
            </div>
        </template>

        {{-- Reservation date: view reservation --}}
        <template x-if="ctx.cell && getRes(ctx.cell)">
            <div>
                <div class="px-3 py-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Reserva</div>
                <a
                    :href="'/admin/reservas/' + getRes(ctx.cell).id"
                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2.5 transition-colors">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#c99f5b" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Ver reserva <span x-text="'#' + getRes(ctx.cell).id" class="font-semibold ml-0.5"></span>
                </a>
            </div>
        </template>
    </div>

    {{-- ── DIALOG MODAL ────────────────────────────────────────── --}}
    <div
        x-show="dialog.show"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center px-4"
        @click.self="dialog.show = false">

        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm"></div>

        <div
            x-show="dialog.show"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-2"
            class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm z-10 overflow-hidden">

            {{-- Dialog header --}}
            <div class="px-6 pt-6 pb-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-gray-900" x-text="dialog.title"></h3>
                        <p class="text-sm text-gray-500 mt-0.5" x-text="dialog.subtitle"></p>
                    </div>
                    <button
                        @click="dialog.show = false"
                        class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition text-gray-400">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Dialog body --}}
            <div class="px-6 py-5 space-y-2.5">

                {{-- Block options --}}
                <template x-if="dialog.mode === 'block'">
                    <div class="space-y-2">
                        <button
                            @click="doBlock([dialog.cell.date]); dialog.show = false"
                            class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl border border-gray-200 hover:border-[#c99f5b] hover:bg-amber-50/30 transition-all group">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#fdf8f0">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#c99f5b" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-semibold text-gray-800">Bloquear este dia</p>
                                <p class="text-xs text-gray-400" x-text="formatDate(dialog.cell.date)"></p>
                            </div>
                        </button>

                        <button
                            @click="doBlock(weekDates(dialog.cell)); dialog.show = false"
                            class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl border border-gray-200 hover:border-[#c99f5b] hover:bg-amber-50/30 transition-all group">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#fdf8f0">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#c99f5b" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-semibold text-gray-800">Bloquear semana</p>
                                <p class="text-xs text-gray-400">7 dias a partir de segunda</p>
                            </div>
                        </button>

                        <button
                            @click="doBlock(monthDates()); dialog.show = false"
                            class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl border border-gray-200 hover:border-red-300 hover:bg-red-50/30 transition-all group">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 bg-red-50">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#ef4444" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-semibold text-red-600">Bloquear todo o mês</p>
                                <p class="text-xs text-gray-400" x-text="'Bloqueia ' + daysInMonth() + ' dias'"></p>
                            </div>
                        </button>
                    </div>
                </template>

                {{-- Unblock option --}}
                <template x-if="dialog.mode === 'unblock'">
                    <div class="space-y-2">
                        <button
                            @click="doUnblock([dialog.cell.date]); dialog.show = false"
                            class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl border border-gray-200 hover:border-green-300 hover:bg-green-50/30 transition-all">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 bg-green-50">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-semibold text-gray-800">Desbloquear este dia</p>
                                <p class="text-xs text-gray-400" x-text="formatDate(dialog.cell.date)"></p>
                            </div>
                        </button>

                        <button
                            @click="doUnblock(weekDates(dialog.cell)); dialog.show = false"
                            class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl border border-gray-200 hover:border-green-300 hover:bg-green-50/30 transition-all">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 bg-green-50">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-semibold text-gray-800">Desbloquear semana</p>
                                <p class="text-xs text-gray-400">Remove bloqueio de 7 dias</p>
                            </div>
                        </button>
                    </div>
                </template>

                {{-- Reservation info --}}
                <template x-if="dialog.mode === 'reservation'">
                    <div class="text-center py-2">
                        <p class="text-sm text-gray-600 mb-4">Esta data tem uma reserva associada.</p>
                        <a
                            :href="'/admin/reservas/' + dialog.reservation.id"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-semibold text-white transition hover:opacity-90"
                            style="background:#c99f5b">
                            Ver reserva <span x-text="'#' + dialog.reservation.id"></span>
                        </a>
                    </div>
                </template>
            </div>

            {{-- Cancel button --}}
            <div class="px-6 pb-6">
                <button
                    @click="dialog.show = false"
                    class="w-full py-2.5 rounded-2xl text-sm font-semibold text-gray-500 bg-gray-100 hover:bg-gray-200 transition">
                    Fechar
                </button>
            </div>
        </div>
    </div>

    {{-- ── TOAST ───────────────────────────────────────────────── --}}
    <div
        x-show="toast.show"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2 translate-x-2"
        x-transition:enter-end="opacity-100 translate-y-0 translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-3.5 rounded-2xl text-white text-sm font-semibold shadow-xl"
        :style="toast.type === 'error' ? 'background:#ef4444' : 'background:#1a1a1a'">
        <template x-if="toast.type !== 'error'">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </template>
        <span x-text="toast.msg"></span>
    </div>

</div>

@push('head')
<style>
  [x-cloak] { display: none !important; }

  /* Month enter animations */
  @keyframes calFromRight {
    from { opacity: 0; transform: translateX(28px); }
    to   { opacity: 1; transform: translateX(0); }
  }
  @keyframes calFromLeft {
    from { opacity: 0; transform: translateX(-28px); }
    to   { opacity: 1; transform: translateX(0); }
  }
  .cal-anim-next { animation: calFromRight 0.32s cubic-bezier(0.25,0.46,0.45,0.94); }
  .cal-anim-prev { animation: calFromLeft  0.32s cubic-bezier(0.25,0.46,0.45,0.94); }

  /* Pulse when a date is blocked/unblocked */
  @keyframes datePulse {
    0%   { box-shadow: 0 0 0 0 rgba(201,159,91,0.6); }
    70%  { box-shadow: 0 0 0 8px rgba(201,159,91,0); }
    100% { box-shadow: 0 0 0 0 rgba(201,159,91,0); }
  }
  .date-pulse { animation: datePulse 0.6s ease-out; }
</style>
@endpush

@push('scripts')
<script>
function calendarApp() {
  return {
    /* ─── state ──────────────────────────────────────────────── */
    year:         new Date().getFullYear(),
    month:        new Date().getMonth() + 1,
    experienceId: @json($experiences->first()?->id ?? null),

    blocked:      [],   // ['YYYY-MM-DD', ...]
    reservations: {},   // {'YYYY-MM-DD': {id, name, status}}

    loading:   false,
    animating: false,
    animClass: '',
    animDir:   'next',

    ctx:    { show: false, x: 0, y: 0, cell: null },
    dialog: { show: false, mode: 'block', title: '', subtitle: '', cell: null, reservation: null },
    toast:  { show: false, msg: '', type: 'success' },

    /* ─── computed ───────────────────────────────────────────── */
    get monthName() {
      return new Date(this.year, this.month - 1, 1)
        .toLocaleString('pt-PT', { month: 'long' });
    },

    get days() {
      const dim    = new Date(this.year, this.month, 0).getDate();
      const first  = new Date(this.year, this.month - 1, 1).getDay();
      const offset = (first + 6) % 7; // Monday-start
      const cells  = [];

      for (let i = 0; i < offset; i++) cells.push(null);
      for (let d = 1; d <= dim; d++) {
        cells.push({ day: d, date: this._ds(d) });
      }
      while (cells.length % 7 !== 0) cells.push(null);
      return cells;
    },

    /* ─── helpers ────────────────────────────────────────────── */
    _ds(d) {
      return `${this.year}-${String(this.month).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
    },

    isToday(cell) {
      const t = new Date(); t.setHours(0,0,0,0);
      const c = new Date(cell.date + 'T00:00:00');
      return c.getTime() === t.getTime();
    },

    isPast(cell) {
      const t = new Date(); t.setHours(0,0,0,0);
      const c = new Date(cell.date + 'T00:00:00');
      return c < t;
    },

    isBlocked(cell) { return this.blocked.includes(cell.date); },

    getRes(cell) { return this.reservations[cell.date] || null; },

    /* True if this is the first visible day of this reservation in the month */
    isResStart(cell) {
      const res = this.getRes(cell);
      if (!res) return false;
      const prev = new Date(cell.date + 'T00:00:00');
      prev.setDate(prev.getDate() - 1);
      const pd = `${prev.getFullYear()}-${String(prev.getMonth()+1).padStart(2,'0')}-${String(prev.getDate()).padStart(2,'0')}`;
      const prevRes = this.reservations[pd];
      return !prevRes || prevRes.id !== res.id;
    },

    /* Count distinct reservations in current month view */
    get monthResCount() {
      const ids = new Set(Object.values(this.reservations).map(r => r.id));
      return ids.size;
    },

    formatDate(ds) {
      return new Date(ds + 'T00:00:00').toLocaleDateString('pt-PT', {
        weekday: 'short', day: 'numeric', month: 'long'
      });
    },

    daysInMonth() {
      return new Date(this.year, this.month, 0).getDate();
    },

    /* Cell CSS class string */
    cellCls(cell) {
      const base = 'relative border-r border-b border-gray-100 transition-colors duration-150 group';
      if (!cell) return base + ' bg-gray-50/30 cursor-default';

      if (this.isPast(cell))    return base + ' opacity-30 cursor-default bg-white';
      if (this.isBlocked(cell)) return base + ' cursor-pointer bg-gray-900 hover:bg-gray-800';

      const res = this.getRes(cell);
      if (res?.status === 'confirmed') return base + ' cursor-pointer bg-green-50 hover:bg-green-100';
      if (res?.status === 'pending')   return base + ' cursor-pointer bg-amber-50 hover:bg-amber-100';

      if (this.isToday(cell)) return base + ' cursor-pointer bg-amber-50/40 ring-2 ring-inset ring-[#c99f5b] hover:bg-amber-50/70';

      return base + ' cursor-pointer bg-white hover:bg-gray-50';
    },

    /* Compute week dates (Mon–Sun) in current month */
    weekDates(cell) {
      const d   = new Date(cell.date + 'T00:00:00');
      const dow = d.getDay();
      const mon = new Date(d); mon.setDate(d.getDate() - ((dow + 6) % 7));
      const out = [];
      for (let i = 0; i < 7; i++) {
        const dd = new Date(mon); dd.setDate(mon.getDate() + i);
        if (dd.getMonth() + 1 === this.month && dd.getFullYear() === this.year) {
          const ds = `${dd.getFullYear()}-${String(dd.getMonth()+1).padStart(2,'0')}-${String(dd.getDate()).padStart(2,'0')}`;
          out.push(ds);
        }
      }
      return out;
    },

    /* All dates in current month */
    monthDates() {
      const dim = new Date(this.year, this.month, 0).getDate();
      const out = [];
      for (let d = 1; d <= dim; d++) out.push(this._ds(d));
      return out;
    },

    /* ─── API ────────────────────────────────────────────────── */
    _csrf() {
      return document.querySelector('meta[name="csrf-token"]').content;
    },

    async loadMonth(dir = null) {
      this.loading = true;
      try {
        const res  = await fetch(
          `/admin/api/calendar?experience_id=${this.experienceId}&year=${this.year}&month=${this.month}`,
          { headers: { 'X-CSRF-TOKEN': this._csrf() } }
        );
        const data = await res.json();
        this.blocked      = data.blocked      || [];
        this.reservations = data.reservations || {};

        if (dir) {
          this.animClass = dir === 'next' ? 'cal-anim-next' : 'cal-anim-prev';
          setTimeout(() => { this.animClass = ''; }, 400);
        }
      } catch(e) {
        this.showToast('Erro ao carregar dados', 'error');
      } finally {
        this.loading = false;
      }
    },

    async doBlock(dates) {
      this.closeCtx();
      try {
        const res = await fetch('/admin/api/calendar/block', {
          method:  'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this._csrf() },
          body:    JSON.stringify({ experience_id: this.experienceId, dates }),
        });
        if (!res.ok) throw new Error();
        // Merge into blocked array reactively
        const newBlocked = [...this.blocked];
        dates.forEach(d => { if (!newBlocked.includes(d)) newBlocked.push(d); });
        this.blocked = newBlocked;
        this.showToast(dates.length > 1 ? `${dates.length} datas bloqueadas` : 'Data bloqueada');
      } catch { this.showToast('Erro ao bloquear', 'error'); }
    },

    async doUnblock(dates) {
      this.closeCtx();
      try {
        const res = await fetch('/admin/api/calendar/unblock', {
          method:  'DELETE',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this._csrf() },
          body:    JSON.stringify({ experience_id: this.experienceId, dates }),
        });
        if (!res.ok) throw new Error();
        this.blocked = this.blocked.filter(d => !dates.includes(d));
        this.showToast(dates.length > 1 ? `${dates.length} datas desbloqueadas` : 'Data desbloqueada');
      } catch { this.showToast('Erro ao desbloquear', 'error'); }
    },

    /* ─── navigation ─────────────────────────────────────────── */
    async prevMonth() {
      if (this.animating) return;
      this.animating = true;
      this.animDir   = 'prev';
      if (this.month === 1) { this.month = 12; this.year--; }
      else this.month--;
      await this.loadMonth('prev');
      this.animating = false;
    },

    async nextMonth() {
      if (this.animating) return;
      this.animating = true;
      this.animDir   = 'next';
      if (this.month === 12) { this.month = 1; this.year++; }
      else this.month++;
      await this.loadMonth('next');
      this.animating = false;
    },

    async goToday() {
      const t    = new Date();
      const prev = this.month !== t.getMonth() + 1 || this.year !== t.getFullYear();
      this.year  = t.getFullYear();
      this.month = t.getMonth() + 1;
      if (prev) await this.loadMonth('next');
    },

    async switchExperience(id) {
      this.experienceId = id;
      await this.loadMonth(null);
    },

    /* ─── context menu ───────────────────────────────────────── */
    onRightClick(e, cell) {
      const padding = 12;
      const w = 208; // menu width
      const h = 160; // approx menu height
      const x = Math.min(e.clientX + padding, window.innerWidth  - w - padding);
      const y = Math.min(e.clientY + padding, window.innerHeight - h - padding);
      this.ctx = { show: true, x, y, cell };
    },

    closeCtx() { this.ctx.show = false; },

    /* ─── dialog ─────────────────────────────────────────────── */
    openDialog(cell) {
      this.closeCtx();
      const res = this.getRes(cell);
      if (res) {
        this.dialog = {
          show: true, mode: 'reservation',
          title: `Reserva #${res.id}`,
          subtitle: res.name + ' · ' + (res.status === 'confirmed' ? 'Confirmada' : 'Pendente'),
          cell, reservation: res,
        };
      } else if (this.isBlocked(cell)) {
        this.dialog = {
          show: true, mode: 'unblock',
          title: 'Data bloqueada',
          subtitle: this.formatDate(cell.date),
          cell, reservation: null,
        };
      } else {
        this.dialog = {
          show: true, mode: 'block',
          title: 'Bloquear disponibilidade',
          subtitle: this.formatDate(cell.date),
          cell, reservation: null,
        };
      }
    },

    /* ─── toast ──────────────────────────────────────────────── */
    showToast(msg, type = 'success') {
      this.toast = { show: true, msg, type };
      setTimeout(() => { this.toast.show = false; }, 3200);
    },

    /* ─── init ───────────────────────────────────────────────── */
    init() {
      this.loadMonth(null);
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') { this.ctx.show = false; this.dialog.show = false; }
      });
    },
  };
}
</script>
@endpush
@endsection
