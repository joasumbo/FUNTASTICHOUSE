@extends('layouts.admin')

@section('title', 'Regras')

@push('head')
<style>
    /* ── Layout ── */
    .rg-card {
        background:#fff; border-radius:20px;
        box-shadow:0 1px 3px rgba(0,0,0,.06);
        transition:box-shadow .2s;
    }
    .rg-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.08); }

    /* ── Category pill ── */
    .rg-pill { border-radius:99px; padding:5px 16px; font-size:13px; font-weight:600;
               cursor:pointer; transition:all .2s; border:none; }
    .rg-pill.active   { background:#111827; color:#fff; }
    .rg-pill:not(.active) { background:#fff; color:#6b7280;
                            box-shadow:0 1px 3px rgba(0,0,0,.06); }

    /* ── Category badge ── */
    .cat-availability { background:#eff6ff; color:#2563eb; }
    .cat-pricing      { background:#fef9c3; color:#a16207; }

    /* ── Condition / action pills on card ── */
    .cond-pill { display:inline-flex; align-items:center; gap:6px;
                 background:#f3f4f6; border-radius:10px; padding:6px 14px;
                 font-size:13px; font-weight:500; color:#111827; }
    .action-pill { display:inline-flex; align-items:center; gap:6px;
                   border-radius:10px; padding:6px 14px;
                   font-size:13px; font-weight:600; }
    .action-block   { background:#fee2e2; color:#dc2626; }
    .action-unblock { background:#dcfce7; color:#16a34a; }
    .action-price-up   { background:#fef9c3; color:#a16207; }
    .action-price-down { background:#eff6ff; color:#2563eb; }

    /* ── Toggle switch ── */
    .rg-toggle { position:relative; display:inline-block; width:40px; height:22px; cursor:pointer; }
    .rg-toggle input { opacity:0; width:0; height:0; }
    .rg-slider {
        position:absolute; inset:0; background:#e5e7eb;
        border-radius:99px; transition:background .2s;
    }
    .rg-slider::before {
        content:''; position:absolute; width:16px; height:16px; background:#fff;
        border-radius:50%; left:3px; top:3px; transition:transform .2s;
        box-shadow:0 1px 3px rgba(0,0,0,.2);
    }
    input:checked + .rg-slider { background:#c99f5b; }
    input:checked + .rg-slider::before { transform:translateX(18px); }

    /* ── Inputs ── */
    .rg-input {
        width:100%; padding:9px 13px; border-radius:10px; font-size:14px;
        border:1.5px solid #e5e7eb; outline:none; transition:border-color .2s;
        background:#fff;
    }
    .rg-input:focus { border-color:#c99f5b; }

    /* ── Buttons ── */
    .rg-btn-primary {
        display:inline-flex; align-items:center; gap:6px;
        background:#111827; color:#fff; border:none; border-radius:12px;
        padding:9px 20px; font-size:13px; font-weight:600; cursor:pointer;
        transition:background .2s;
    }
    .rg-btn-primary:hover { background:#1f2937; }
    .rg-btn-ghost {
        display:inline-flex; align-items:center; gap:5px;
        background:transparent; border:1.5px solid #e5e7eb; color:#6b7280;
        border-radius:10px; padding:7px 14px; font-size:13px; font-weight:500;
        cursor:pointer; transition:all .15s;
    }
    .rg-btn-ghost:hover { border-color:#9ca3af; color:#374151; }
    .rg-btn-danger { border-color:#fca5a5; color:#dc2626; }
    .rg-btn-danger:hover { background:#fef2f2; border-color:#dc2626; }

    /* ── Modal ── */
    .rg-overlay {
        position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:50;
        display:flex; align-items:center; justify-content:center; padding:16px;
    }
    .rg-modal {
        background:#fff; border-radius:24px; width:100%; max-width:560px;
        max-height:92vh; overflow-y:auto;
        box-shadow:0 20px 60px rgba(0,0,0,.18); padding:28px 32px;
    }

    /* ── Builder sections ── */
    .rg-section {
        border-radius:14px; border:1.5px solid #e5e7eb; padding:16px;
    }
    .rg-section-label {
        font-size:11px; font-weight:700; letter-spacing:.06em;
        text-transform:uppercase; color:#9ca3af; margin-bottom:10px;
    }

    /* ── Arrow connector ── */
    .rg-arrow {
        display:flex; align-items:center; justify-content:center;
        gap:8px; color:#9ca3af; font-size:13px; font-weight:500;
        margin:8px 0;
    }

    /* ── Animations ── */
    @keyframes rgFadeUp {
        from { opacity:0; transform:translateY(8px); }
        to   { opacity:1; transform:translateY(0); }
    }
    .rg-anim { animation:rgFadeUp .28s cubic-bezier(.16,1,.3,1) both; }

    [x-cloak] { display:none !important; }
</style>
@endpush

@section('content')
<div x-data="regrasApp()" x-init="init()" class="space-y-5">

    {{-- ─── HEADER ─── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
        <div>
            <h1 class="text-[26px] font-bold text-gray-900 flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#111827">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#c99f5b" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </span>
                Regras Automáticas
            </h1>
            <p class="text-sm text-gray-500 mt-0.5">Defina condições que ajustam automaticamente disponibilidade e preços.</p>
        </div>
        <button @click="openCreate()" class="rg-btn-primary self-start sm:self-auto">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nova regra
        </button>
    </div>

    {{-- ─── FLASH ─── --}}
    @if(session('success'))
    <div class="rg-card px-5 py-3 flex items-center gap-3 rg-anim" style="border-left:4px solid #16a34a">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        <span class="text-sm font-medium text-gray-700">{{ session('success') }}</span>
    </div>
    @endif
    @if($errors->any())
    <div class="rg-card px-5 py-3 flex items-start gap-3 rg-anim" style="border-left:4px solid #dc2626">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2.5" class="mt-0.5 flex-shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <ul class="text-sm text-gray-700 space-y-0.5">
            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
    </div>
    @endif

    {{-- ─── STATS ─── --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        @php
        $statItems = [
            ['label' => 'Total de regras',       'value' => $counts['all'],          'color' => '#6b7280'],
            ['label' => 'Regras activas',         'value' => $counts['active'],       'color' => '#16a34a'],
            ['label' => 'Disponibilidade',        'value' => $counts['availability'], 'color' => '#2563eb'],
            ['label' => 'Preço dinâmico',         'value' => $counts['pricing'],      'color' => '#a16207'],
        ];
        @endphp
        @foreach($statItems as $s)
        <div class="rg-card px-4 py-3 rg-anim" style="animation-delay:{{ $loop->index * 40 }}ms">
            <p class="text-xs text-gray-500 font-medium">{{ $s['label'] }}</p>
            <p class="text-2xl font-bold mt-1" style="color:{{ $s['color'] }}">{{ $s['value'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ─── FILTER PILLS ─── --}}
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.regras') }}"
           class="rg-pill {{ !request('category') ? 'active' : '' }}">
            Todas <span class="ml-1 opacity-60 text-xs font-normal">({{ $counts['all'] }})</span>
        </a>
        <a href="{{ route('admin.regras', ['category' => 'availability']) }}"
           class="rg-pill {{ request('category') === 'availability' ? 'active' : '' }}">
            Disponibilidade <span class="ml-1 opacity-60 text-xs font-normal">({{ $counts['availability'] }})</span>
        </a>
        <a href="{{ route('admin.regras', ['category' => 'pricing']) }}"
           class="rg-pill {{ request('category') === 'pricing' ? 'active' : '' }}">
            Preço dinâmico <span class="ml-1 opacity-60 text-xs font-normal">({{ $counts['pricing'] }})</span>
        </a>
    </div>

    {{-- ─── RULES LIST ─── --}}
    @if($rules->isEmpty())
    <div class="rg-card px-8 py-16 text-center rg-anim">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:#f9fafb">
            <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#d1d5db" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
        <p class="text-base font-semibold text-gray-700">Nenhuma regra definida</p>
        <p class="text-sm text-gray-400 mt-1 mb-5">Crie a primeira regra para automatizar disponibilidade ou preços.</p>
        <button @click="openCreate()" class="rg-btn-primary mx-auto">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Criar primeira regra
        </button>
    </div>
    @else
    <div class="space-y-3">
        @foreach($rules as $i => $rule)
        @php
            $actionCls = match($rule->action_type) {
                'block_date'     => 'action-block',
                'unblock_date'   => 'action-unblock',
                'price_increase' => 'action-price-up',
                'price_decrease' => 'action-price-down',
                default          => 'action-price-up',
            };
            $actionIcon = match($rule->action_type) {
                'block_date'     => 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636',
                'unblock_date'   => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'price_increase' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
                'price_decrease' => 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6',
                default          => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
            };
        @endphp
        <div class="rg-card p-5 rg-anim" style="animation-delay:{{ $i * 40 }}ms">
            <div class="flex flex-col sm:flex-row sm:items-start gap-4">

                {{-- Left: rule info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $rule->category === 'availability' ? 'cat-availability' : 'cat-pricing' }}">
                            {{ $rule->category === 'availability' ? 'Disponibilidade' : 'Preço dinâmico' }}
                        </span>
                        @if($rule->experience)
                        <span class="text-[11px] text-gray-500 bg-gray-100 rounded-full px-2.5 py-1">
                            {{ $rule->experience->name_pt }}
                        </span>
                        @else
                        <span class="text-[11px] text-gray-400 bg-gray-50 rounded-full px-2.5 py-1">Todas as experiências</span>
                        @endif
                        @if($rule->last_run_at)
                        <span class="text-[10px] text-gray-400">
                            Executada {{ $rule->last_run_at->diffForHumans() }}
                        </span>
                        @endif
                    </div>

                    <h3 class="text-base font-bold text-gray-900 mb-3">{{ $rule->name }}</h3>

                    @if($rule->description)
                    <p class="text-xs text-gray-500 mb-3 leading-relaxed">{{ $rule->description }}</p>
                    @endif

                    {{-- Condition → Action visual --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="cond-pill">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $rule->triggerLabel() }}
                        </span>
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                        <span class="action-pill {{ $actionCls }}">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $actionIcon }}"/>
                            </svg>
                            {{ $rule->actionLabel() }}
                        </span>
                    </div>
                </div>

                {{-- Right: toggle + actions --}}
                <div class="flex sm:flex-col items-center sm:items-end gap-3 flex-shrink-0">

                    {{-- Toggle --}}
                    <form method="POST" action="{{ route('admin.regras.toggle', $rule) }}">
                        @csrf @method('PATCH')
                        <label class="rg-toggle" title="{{ $rule->active ? 'Clique para desactivar' : 'Clique para activar' }}">
                            <input type="checkbox" {{ $rule->active ? 'checked' : '' }} onchange="this.form.submit()">
                            <span class="rg-slider"></span>
                        </label>
                    </form>

                    {{-- Edit / Delete --}}
                    <div class="flex items-center gap-2">
                        <button
                            @click="openEdit({{ $rule->id }},
                                '{{ addslashes($rule->name) }}',
                                '{{ addslashes($rule->description ?? '') }}',
                                '{{ $rule->experience_id ?? '' }}',
                                '{{ $rule->category }}',
                                '{{ $rule->trigger_metric }}',
                                '{{ $rule->trigger_operator }}',
                                {{ (float)$rule->trigger_value }},
                                '{{ $rule->action_type }}',
                                {{ $rule->action_value !== null ? (float)$rule->action_value : 'null' }},
                                '{{ $rule->action_unit }}',
                                {{ $rule->priority }}
                            )"
                            class="rg-btn-ghost py-1.5 px-3 text-xs">
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </button>
                        <button
                            @click="openDelete({{ $rule->id }}, '{{ addslashes($rule->name) }}')"
                            class="rg-btn-ghost rg-btn-danger py-1.5 px-3 text-xs">
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif


    {{-- ═══════════════════════════════════════ --}}
    {{-- ─── CREATE / EDIT MODAL (rule builder) ─── --}}
    {{-- ═══════════════════════════════════════ --}}
    <div x-show="modal.open" class="rg-overlay" x-cloak @click.self="modal.open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="rg-modal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.stop>

            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900" x-text="modal.mode === 'create' ? 'Nova regra' : 'Editar regra'"></h3>
                    <p class="text-xs text-gray-400 mt-0.5">Defina a condição e a acção automática</p>
                </div>
                <button @click="modal.open = false" class="w-9 h-9 rounded-full hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" :action="modal.mode === 'create' ? '{{ route('admin.regras.store') }}' : ('/admin/regras/' + modal.id)" class="space-y-4">
                @csrf
                <template x-if="modal.mode === 'edit'">
                    <input type="hidden" name="_method" value="PATCH">
                </template>

                {{-- Nome --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nome da regra</label>
                    <input type="text" name="name" x-model="form.name"
                        placeholder="ex: Bloquear quando lotado" class="rg-input" required maxlength="120">
                </div>

                {{-- Descrição --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Descrição <span class="font-normal text-gray-400">(opcional)</span>
                    </label>
                    <textarea name="description" x-model="form.description"
                        placeholder="Notas sobre esta regra..."
                        rows="2" class="rg-input" style="resize:vertical" maxlength="500"></textarea>
                </div>

                {{-- Experiência --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Experiência</label>
                        <select name="experience_id" x-model="form.experience_id" class="rg-input">
                            <option value="">Todas</option>
                            @foreach($experiences as $exp)
                            <option value="{{ $exp->id }}">{{ $exp->name_pt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Categoria</label>
                        <select name="category" x-model="form.category" @change="syncActionType()" class="rg-input">
                            <option value="availability">Disponibilidade</option>
                            <option value="pricing">Preço dinâmico</option>
                        </select>
                    </div>
                </div>

                {{-- ── SE (condição) ── --}}
                <div class="rg-section">
                    <p class="rg-section-label">
                        <svg style="display:inline;vertical-align:middle" width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01"/>
                        </svg>
                        Condição — SE
                    </p>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="col-span-3 sm:col-span-1">
                            <label class="block text-[11px] text-gray-500 mb-1">Métrica</label>
                            <select name="trigger_metric" x-model="form.trigger_metric" class="rg-input text-xs">
                                <option value="confirmed_reservations">Reservas confirmadas</option>
                                <option value="pending_reservations">Reservas pendentes</option>
                                <option value="total_reservations">Total de reservas</option>
                                <option value="occupancy_pct">Taxa de ocupação (%)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] text-gray-500 mb-1">Operador</label>
                            <select name="trigger_operator" x-model="form.trigger_operator" class="rg-input text-xs">
                                <option value="gte">≥ maior ou igual</option>
                                <option value="lte">≤ menor ou igual</option>
                                <option value="gt">&gt; maior que</option>
                                <option value="lt">&lt; menor que</option>
                                <option value="eq">= igual a</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] text-gray-500 mb-1">
                                Valor <span x-show="form.trigger_metric === 'occupancy_pct'" class="text-gray-400">(%)</span>
                            </label>
                            <input type="number" name="trigger_value" x-model="form.trigger_value"
                                step="0.01" min="0" max="9999" class="rg-input text-xs" required>
                        </div>
                    </div>
                </div>

                {{-- Arrow --}}
                <div class="rg-arrow">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#c99f5b" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m0 0l-4-4m4 4l4-4"/>
                    </svg>
                    <span style="color:#c99f5b;font-size:11px;font-weight:700">ENTÃO</span>
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#c99f5b" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m0 0l-4-4m4 4l4-4"/>
                    </svg>
                </div>

                {{-- ── ENTÃO (acção) ── --}}
                <div class="rg-section">
                    <p class="rg-section-label">
                        <svg style="display:inline;vertical-align:middle" width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Acção — ENTÃO
                    </p>
                    <div class="grid gap-2" :class="needsActionValue ? 'grid-cols-3' : 'grid-cols-1'">
                        <div :class="needsActionValue ? 'col-span-1' : ''">
                            <label class="block text-[11px] text-gray-500 mb-1">Acção</label>
                            <select name="action_type" x-model="form.action_type" class="rg-input text-xs">
                                <template x-if="form.category === 'availability'">
                                    <option value="block_date">Bloquear data</option>
                                </template>
                                <template x-if="form.category === 'availability'">
                                    <option value="unblock_date">Desbloquear data</option>
                                </template>
                                <template x-if="form.category === 'pricing'">
                                    <option value="price_increase">Aumentar preço</option>
                                </template>
                                <template x-if="form.category === 'pricing'">
                                    <option value="price_decrease">Diminuir preço</option>
                                </template>
                            </select>
                        </div>
                        <template x-if="needsActionValue">
                            <div>
                                <label class="block text-[11px] text-gray-500 mb-1">Valor</label>
                                <input type="number" name="action_value" x-model="form.action_value"
                                    step="0.01" min="0" max="9999" class="rg-input text-xs">
                            </div>
                        </template>
                        <template x-if="needsActionValue">
                            <div>
                                <label class="block text-[11px] text-gray-500 mb-1">Unidade</label>
                                <select name="action_unit" x-model="form.action_unit" class="rg-input text-xs">
                                    <option value="fixed">€ fixo/noite</option>
                                    <option value="percent">% percentagem</option>
                                </select>
                            </div>
                        </template>
                    </div>
                    {{-- Hidden field when no value needed --}}
                    <template x-if="!needsActionValue">
                        <input type="hidden" name="action_unit" value="fixed">
                    </template>
                </div>

                {{-- Prioridade --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Prioridade
                        <span class="font-normal text-gray-400">(0 = mais alta)</span>
                    </label>
                    <input type="number" name="priority" x-model="form.priority"
                        min="0" max="999" class="rg-input" style="max-width:140px">
                </div>

                {{-- Preview --}}
                <div class="rounded-xl p-4 border border-dashed border-gray-200 bg-gray-50/50">
                    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-2">Pré-visualização</p>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="cond-pill text-xs" x-text="conditionPreview"></span>
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                        <span class="action-pill text-xs"
                              :class="actionPreviewCls"
                              x-text="actionPreview"></span>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-2 pt-2 border-t border-gray-100 mt-2">
                    <button type="button" @click="modal.open = false" class="rg-btn-ghost">Cancelar</button>
                    <button type="submit" class="rg-btn-primary">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-text="modal.mode === 'create' ? 'Criar regra' : 'Guardar alterações'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- ─── DELETE MODAL ─── --}}
    <div x-show="deleteModal.open" class="rg-overlay" x-cloak @click.self="deleteModal.open = false"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="rg-modal" style="max-width:400px" @click.stop>
            <div class="flex flex-col items-center text-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background:#fef2f2">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Eliminar regra</h3>
                    <p class="text-sm text-gray-500 mt-1">"<span x-text="deleteModal.name" class="font-semibold"></span>"</p>
                    <p class="text-xs text-gray-400 mt-1">Esta acção não pode ser desfeita.</p>
                </div>
            </div>
            <form method="POST" :action="'/admin/regras/' + deleteModal.id" class="flex gap-2">
                @csrf @method('DELETE')
                <button type="button" @click="deleteModal.open = false" class="rg-btn-ghost flex-1">Cancelar</button>
                <button type="submit" class="rg-btn-primary flex-1" style="background:#dc2626">Eliminar</button>
            </form>
        </div>
    </div>

</div>

<script>
function regrasApp() {
    return {
        modal: {
            open: false, mode: 'create', id: null,
        },
        deleteModal: { open: false, id: null, name: '' },

        form: {
            name: '', description: '',
            experience_id: '', category: 'availability',
            trigger_metric: 'confirmed_reservations',
            trigger_operator: 'gte', trigger_value: 1,
            action_type: 'block_date', action_value: 0,
            action_unit: 'fixed', priority: 0,
        },

        init() {},

        get needsActionValue() {
            return this.form.action_type === 'price_increase'
                || this.form.action_type === 'price_decrease';
        },

        syncActionType() {
            if (this.form.category === 'availability') {
                if (!['block_date','unblock_date'].includes(this.form.action_type)) {
                    this.form.action_type = 'block_date';
                }
            } else {
                if (!['price_increase','price_decrease'].includes(this.form.action_type)) {
                    this.form.action_type = 'price_increase';
                }
            }
        },

        get conditionPreview() {
            const metrics = {
                confirmed_reservations: 'reservas confirmadas',
                pending_reservations:   'reservas pendentes',
                total_reservations:     'total reservas',
                occupancy_pct:          'taxa ocupação',
            };
            const ops = { gte:'≥', lte:'≤', gt:'>', lt:'<', eq:'=' };
            const m = metrics[this.form.trigger_metric] || this.form.trigger_metric;
            const o = ops[this.form.trigger_operator] || this.form.trigger_operator;
            const v = this.form.trigger_metric === 'occupancy_pct'
                ? this.form.trigger_value + '%'
                : this.form.trigger_value;
            return `SE ${m} ${o} ${v}`;
        },

        get actionPreview() {
            if (this.form.action_type === 'block_date')   return '→ Bloquear data';
            if (this.form.action_type === 'unblock_date') return '→ Desbloquear data';
            const sign = this.form.action_type === 'price_increase' ? '+' : '-';
            const unit = this.form.action_unit === 'percent' ? '%' : '€';
            const val  = parseFloat(this.form.action_value || 0).toFixed(2).replace('.',',');
            return `→ Preço ${sign}${val}${unit}`;
        },

        get actionPreviewCls() {
            const m = {
                block_date:     'action-block',
                unblock_date:   'action-unblock',
                price_increase: 'action-price-up',
                price_decrease: 'action-price-down',
            };
            return m[this.form.action_type] || 'action-price-up';
        },

        openCreate() {
            this.form = {
                name: '', description: '',
                experience_id: '', category: 'availability',
                trigger_metric: 'confirmed_reservations',
                trigger_operator: 'gte', trigger_value: 1,
                action_type: 'block_date', action_value: 0,
                action_unit: 'fixed', priority: 0,
            };
            this.modal = { open: true, mode: 'create', id: null };
        },

        openEdit(id, name, description, expId, category, metric, operator, value, actionType, actionValue, actionUnit, priority) {
            this.form = {
                name, description,
                experience_id: expId || '',
                category, trigger_metric: metric,
                trigger_operator: operator, trigger_value: value,
                action_type: actionType,
                action_value: actionValue !== null ? actionValue : 0,
                action_unit: actionUnit, priority,
            };
            this.modal = { open: true, mode: 'edit', id };
        },

        openDelete(id, name) {
            this.deleteModal = { open: true, id, name };
        },
    };
}
</script>
@endsection
