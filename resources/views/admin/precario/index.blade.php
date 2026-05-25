@extends('layouts.admin')

@section('title', 'Preçário')

@push('head')
<style>
    .pr-tab { transition: all .2s; }
    .pr-tab.active { background:#111827; color:#fff; }
    .pr-tab:not(.active) { background:#fff; color:#374151; }

    .pr-card { background:#fff; border-radius:24px; box-shadow:0 1px 3px rgba(0,0,0,.06); }

    .season-badge {
        display:inline-flex; align-items:center; gap:5px;
        padding:3px 10px; border-radius:99px; font-size:12px; font-weight:600;
    }
    .season-high   { background:#fef2f2; color:#dc2626; }
    .season-medium { background:#fffbeb; color:#d97706; }
    .season-low    { background:#f0fdf4; color:#16a34a; }

    .pr-input {
        width:100%; padding:8px 12px; border-radius:10px; font-size:14px;
        border:1.5px solid #e5e7eb; outline:none; transition:border-color .2s;
    }
    .pr-input:focus { border-color:#c99f5b; }
    .pr-select { background-position:right 10px center; background-repeat:no-repeat; }

    .pr-btn-gold {
        display:inline-flex; align-items:center; gap:6px;
        background:#111827; color:#fff; border:none; border-radius:12px;
        padding:8px 18px; font-size:13px; font-weight:600; cursor:pointer; transition:background .2s;
    }
    .pr-btn-gold:hover { background:#1f2937; }

    .pr-btn-ghost {
        display:inline-flex; align-items:center; gap:4px;
        background:transparent; border:1.5px solid #e5e7eb; color:#374151;
        border-radius:10px; padding:6px 14px; font-size:13px; font-weight:500;
        cursor:pointer; transition:all .15s;
    }
    .pr-btn-ghost:hover { border-color:#c99f5b; color:#c99f5b; }

    .pr-modal-overlay {
        position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:50;
        display:flex; align-items:center; justify-content:center; padding:16px;
    }
    .pr-modal {
        background:#fff; border-radius:24px; width:100%; max-width:480px;
        box-shadow:0 20px 60px rgba(0,0,0,.15); padding:28px;
    }

    .pr-table th { font-size:11px; font-weight:600; text-transform:uppercase;
        letter-spacing:.05em; color:#9ca3af; padding:10px 16px; text-align:left; }
    .pr-table td { padding:12px 16px; border-top:1px solid #f3f4f6; font-size:14px; }
    .pr-table tr:hover td { background:#fafafa; }

    @keyframes prFadeUp {
        from { opacity:0; transform:translateY(10px); }
        to   { opacity:1; transform:translateY(0); }
    }
    .pr-anim { animation:prFadeUp .3s cubic-bezier(.16,1,.3,1) both; }
</style>
@endpush

@section('content')
@php
    $seasonLabels = ['high' => 'Alta', 'medium' => 'Média', 'low' => 'Baixa'];
    $seasonCls    = ['high' => 'season-high', 'medium' => 'season-medium', 'low' => 'season-low'];
    $seasonDot    = ['high' => '#dc2626', 'medium' => '#d97706', 'low' => '#16a34a'];
@endphp

<div
    x-data="precarioApp()"
    x-init="init()"
    class="space-y-5"
>

    {{-- ───── HEADER ───── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
        <div>
            <h1 class="text-[26px] font-bold text-gray-900">Preçário</h1>
            <p class="text-sm text-gray-500 mt-0.5">Preços base, fins de semana e épocas por experiência.</p>
        </div>
        <button @click="openAdd()" class="pr-btn-gold self-start sm:self-auto">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nova época
        </button>
    </div>

    {{-- ───── FLASH ───── --}}
    @if(session('success'))
    <div class="pr-card px-5 py-3 flex items-center gap-3 pr-anim" style="border-left:4px solid #16a34a">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        <span class="text-sm font-medium text-gray-700">{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="pr-card px-5 py-3 flex items-start gap-3 pr-anim" style="border-left:4px solid #dc2626">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2.5" class="mt-0.5 flex-shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <ul class="text-sm text-gray-700 space-y-0.5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- ───── EXPERIENCE TABS ───── --}}
    <div class="flex gap-2">
        @foreach($experiences as $exp)
        <button
            @click="activeExp = {{ $exp->id }}"
            :class="activeExp === {{ $exp->id }} ? 'active' : ''"
            class="pr-tab rounded-xl px-5 py-2 text-sm font-semibold"
            style="box-shadow:0 1px 3px rgba(0,0,0,.06)"
        >
            {{ $exp->name_pt }}
        </button>
        @endforeach
    </div>

    {{-- ───── PER EXPERIENCE ───── --}}
    @foreach($experiences as $exp)
    <div x-show="activeExp === {{ $exp->id }}" class="space-y-5" x-cloak>

        {{-- BASE PRICES --}}
        <div class="pr-card p-6 pr-anim">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-base font-bold text-gray-900">Preços de base</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Aplicados quando não existe regra de época activa</p>
                </div>
                <button
                    @click="toggleEditPrices({{ $exp->id }})"
                    class="pr-btn-ghost text-xs"
                >
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span x-text="editingPrices[{{ $exp->id }}] ? 'Cancelar' : 'Editar'"></span>
                </button>
            </div>

            {{-- Display mode --}}
            <div x-show="!editingPrices[{{ $exp->id }}]" class="grid grid-cols-2 gap-4">
                <div class="rounded-2xl p-4" style="background:#f9fafb">
                    <p class="text-xs text-gray-500 mb-1">Preço semana</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($exp->base_price, 2, ',', '') }}<span class="text-sm font-medium text-gray-400 ml-1">€/noite</span></p>
                </div>
                <div class="rounded-2xl p-4" style="background:#f9fafb">
                    <p class="text-xs text-gray-500 mb-1">Preço fim de semana</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($exp->weekend_price, 2, ',', '') }}<span class="text-sm font-medium text-gray-400 ml-1">€/noite</span></p>
                </div>
            </div>

            {{-- Edit mode --}}
            <div x-show="editingPrices[{{ $exp->id }}]" x-cloak>
                <form method="POST" action="{{ route('admin.precario.prices', $exp) }}" class="grid grid-cols-2 gap-4">
                    @csrf @method('PATCH')
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Preço semana (€/noite)</label>
                        <input type="number" name="base_price" step="0.01" min="0" max="9999"
                            value="{{ $exp->base_price }}"
                            class="pr-input" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Preço fim de semana (€/noite)</label>
                        <input type="number" name="weekend_price" step="0.01" min="0" max="9999"
                            value="{{ $exp->weekend_price }}"
                            class="pr-input" required>
                    </div>
                    <div class="col-span-2 flex justify-end gap-2 pt-2">
                        <button type="button" @click="toggleEditPrices({{ $exp->id }})" class="pr-btn-ghost">Cancelar</button>
                        <button type="submit" class="pr-btn-gold">Guardar preços</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- SEASON RULES --}}
        <div class="pr-card overflow-hidden pr-anim" style="animation-delay:.05s">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h2 class="text-base font-bold text-gray-900">Épocas e preços especiais</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Intervalos de datas com preço personalizado por época</p>
                </div>
                <span class="text-xs font-semibold text-gray-400 bg-gray-100 rounded-full px-3 py-1">
                    {{ $exp->pricingRules->count() }} época{{ $exp->pricingRules->count() !== 1 ? 's' : '' }}
                </span>
            </div>

            @if($exp->pricingRules->isEmpty())
            <div class="px-6 py-10 text-center">
                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-700">Sem épocas definidas</p>
                <p class="text-xs text-gray-400 mt-1">Clique em "Nova época" para adicionar regras de preço</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="pr-table w-full">
                    <thead>
                        <tr>
                            <th>Época</th>
                            <th>Data início</th>
                            <th>Data fim</th>
                            <th>Preço/noite</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($exp->pricingRules as $rule)
                        <tr>
                            <td>
                                <span class="season-badge {{ $seasonCls[$rule->season] }}">
                                    <svg width="7" height="7" viewBox="0 0 8 8"><circle cx="4" cy="4" r="4" fill="{{ $seasonDot[$rule->season] }}"/></svg>
                                    {{ $seasonLabels[$rule->season] }}
                                </span>
                            </td>
                            <td class="text-gray-700">{{ $rule->start_date->format('d/m/Y') }}</td>
                            <td class="text-gray-700">{{ $rule->end_date->format('d/m/Y') }}</td>
                            <td>
                                <span class="font-bold text-gray-900">{{ number_format($rule->price_per_night, 2, ',', '') }}€</span>
                                <span class="text-xs text-gray-400">/noite</span>
                            </td>
                            <td>
                                <div class="flex items-center gap-2 justify-end">
                                    <button
                                        @click="openEdit({{ $rule->id }}, {{ $exp->id }}, '{{ $rule->season }}', '{{ $rule->start_date->format('Y-m-d') }}', '{{ $rule->end_date->format('Y-m-d') }}', {{ $rule->price_per_night }})"
                                        class="pr-btn-ghost py-1 px-3 text-xs"
                                        title="Editar">
                                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Editar
                                    </button>
                                    <button
                                        @click="openDelete({{ $rule->id }}, '{{ $seasonLabels[$rule->season] }}', '{{ $rule->start_date->format('d/m/Y') }}', '{{ $rule->end_date->format('d/m/Y') }}')"
                                        class="pr-btn-ghost py-1 px-3 text-xs hover:border-red-300 hover:text-red-500"
                                        title="Eliminar">
                                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

    </div>
    @endforeach


    {{-- ───── ADD MODAL ───── --}}
    <div x-show="addModal" class="pr-modal-overlay" x-cloak @click.self="addModal = false"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="pr-modal" @click.stop>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Nova época de preço</h3>
                <button @click="addModal = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.precario.rules.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="experience_id" :value="activeExp">

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Época</label>
                    <select name="season" class="pr-input pr-select" required>
                        <option value="">Selecionar época...</option>
                        <option value="high">Alta</option>
                        <option value="medium">Média</option>
                        <option value="low">Baixa</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Data início</label>
                        <input type="date" name="start_date" class="pr-input" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Data fim</label>
                        <input type="date" name="end_date" class="pr-input" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Preço por noite (€)</label>
                    <input type="number" name="price_per_night" step="0.01" min="0" max="9999"
                        placeholder="ex: 150.00" class="pr-input" required>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="addModal = false" class="pr-btn-ghost">Cancelar</button>
                    <button type="submit" class="pr-btn-gold">Guardar época</button>
                </div>
            </form>
        </div>
    </div>


    {{-- ───── EDIT MODAL ───── --}}
    <div x-show="editModal.open" class="pr-modal-overlay" x-cloak @click.self="editModal.open = false"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="pr-modal" @click.stop>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Editar época de preço</h3>
                <button @click="editModal.open = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form method="POST" :action="'/admin/precario/rules/' + editModal.id" class="space-y-4">
                @csrf @method('PATCH')

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Época</label>
                    <select name="season" class="pr-input pr-select" :value="editModal.season" x-model="editModal.season" required>
                        <option value="high">Alta</option>
                        <option value="medium">Média</option>
                        <option value="low">Baixa</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Data início</label>
                        <input type="date" name="start_date" x-model="editModal.start_date" class="pr-input" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Data fim</label>
                        <input type="date" name="end_date" x-model="editModal.end_date" class="pr-input" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Preço por noite (€)</label>
                    <input type="number" name="price_per_night" step="0.01" min="0" max="9999"
                        x-model="editModal.price" class="pr-input" required>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="editModal.open = false" class="pr-btn-ghost">Cancelar</button>
                    <button type="submit" class="pr-btn-gold">Guardar alterações</button>
                </div>
            </form>
        </div>
    </div>


    {{-- ───── DELETE MODAL ───── --}}
    <div x-show="deleteModal.open" class="pr-modal-overlay" x-cloak @click.self="deleteModal.open = false"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="pr-modal" @click.stop>
            <div class="flex flex-col items-center text-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background:#fef2f2">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Eliminar época</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Época <strong x-text="deleteModal.label"></strong>
                        de <strong x-text="deleteModal.start"></strong> a <strong x-text="deleteModal.end"></strong>
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Esta ação não pode ser desfeita.</p>
                </div>
            </div>
            <form method="POST" :action="'/admin/precario/rules/' + deleteModal.id" class="flex gap-2 justify-center">
                @csrf @method('DELETE')
                <button type="button" @click="deleteModal.open = false" class="pr-btn-ghost flex-1">Cancelar</button>
                <button type="submit" class="pr-btn-gold flex-1" style="background:#dc2626">Eliminar</button>
            </form>
        </div>
    </div>

</div>

<script>
function precarioApp() {
    return {
        activeExp: {{ $experiences->first()->id ?? 'null' }},
        editingPrices: {},
        addModal: false,
        editModal: { open: false, id: null, season: '', start_date: '', end_date: '', price: 0 },
        deleteModal: { open: false, id: null, label: '', start: '', end: '' },

        init() {
            @foreach($experiences as $exp)
            this.editingPrices[{{ $exp->id }}] = false;
            @endforeach
        },

        toggleEditPrices(expId) {
            this.editingPrices[expId] = !this.editingPrices[expId];
        },

        openAdd() {
            this.addModal = true;
        },

        openEdit(id, expId, season, start, end, price) {
            this.editModal = { open: true, id, season, start_date: start, end_date: end, price };
        },

        openDelete(id, label, start, end) {
            this.deleteModal = { open: true, id, label, start, end };
        },
    };
}
</script>
@endsection
