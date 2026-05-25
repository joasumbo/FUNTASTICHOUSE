@extends('layouts.admin')

@section('title', 'Pontos de Interesse')

@push('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.css">
<style>
.poi-mini-map { height: 210px; border-radius: 0.75rem; border: 1px solid #e5e7eb; }
</style>
@endpush

@section('content')
<div x-data="poisApp()">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 rounded-xl bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Header --}}
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-[26px] font-bold text-gray-900">Pontos de Interesse</h1>
            <p class="text-sm text-gray-500 mt-0.5">Gerir locais, atrações e atividades próximas da casa.</p>
        </div>
        <button @click="addModal.open = true"
                class="inline-flex items-center gap-2 bg-gray-900 text-white text-sm font-medium px-4 py-2.5 rounded-full hover:bg-gray-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Adicionar POI
        </button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Total</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $counts['all'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Activos</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $counts['active'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Inactivos</p>
            <p class="text-3xl font-bold text-red-500 mt-1">{{ $counts['inactive'] }}</p>
        </div>
    </div>

    {{-- Category Filter Tabs --}}
    <div class="flex gap-2 mb-4 flex-wrap">
        <button @click="activeTab = 'all'"
                :class="activeTab === 'all' ? 'bg-gray-900 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:border-gray-400'"
                class="text-sm font-medium px-4 py-1.5 rounded-full transition">
            Todos ({{ $counts['all'] }})
        </button>
        @foreach($categories as $cat)
        <button @click="activeTab = {{ $cat->id }}"
                :class="activeTab === {{ $cat->id }} ? 'bg-gray-900 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:border-gray-400'"
                class="text-sm font-medium px-4 py-1.5 rounded-full transition">
            {{ $cat->name_pt }} ({{ $cat->pois_count }})
        </button>
        @endforeach
    </div>

    {{-- POI Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/60">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nome</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden md:table-cell">Categoria</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden lg:table-cell">Coordenadas</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden sm:table-cell">Distância</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Estado</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pois as $poi)
                <tr x-show="activeTab === 'all' || activeTab === {{ $poi->poi_category_id }}"
                    class="hover:bg-gray-50/50 transition">
                    <td class="px-5 py-3.5">
                        <p class="font-medium text-gray-900">{{ $poi->name_pt }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $poi->name_en }}</p>
                    </td>
                    <td class="px-4 py-3.5 hidden md:table-cell">
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full">
                            <i class="fa-solid {{ $poi->category->icon ?? 'fa-map-pin' }} text-[10px]"></i>
                            {{ $poi->category->name_pt }}
                        </span>
                    </td>
                    <td class="px-4 py-3.5 hidden lg:table-cell font-mono text-xs text-gray-500">
                        {{ $poi->lat }}, {{ $poi->lng }}
                    </td>
                    <td class="px-4 py-3.5 hidden sm:table-cell text-sm text-gray-700">
                        @if($poi->distance_km)
                            {{ number_format($poi->distance_km, 1) }} km
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3.5">
                        <form method="POST" action="{{ route('admin.pois.toggle', $poi) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="relative inline-flex h-5 w-9 items-center rounded-full transition {{ $poi->active ? 'bg-green-500' : 'bg-gray-200' }}"
                                    title="{{ $poi->active ? 'Desactivar' : 'Activar' }}">
                                <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition {{ $poi->active ? 'translate-x-[18px]' : 'translate-x-[2px]' }}"></span>
                            </button>
                        </form>
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-1 justify-end">
                            <button @click="openEdit({{ $poi->toJson() }})"
                                    class="p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition" title="Editar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button @click="openDelete({{ $poi->toJson() }})"
                                    class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Eliminar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-700">Sem pontos de interesse</p>
                            <p class="text-xs text-gray-400">Adicione o primeiro POI.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Category Management --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-6">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <div>
                <h2 class="text-base font-semibold text-gray-900">Categorias</h2>
                <p class="text-xs text-gray-400 mt-0.5">{{ $categories->count() }} categorias registadas</p>
            </div>
            <button @click="catAddModal.open = true"
                    class="text-sm font-medium bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full hover:bg-gray-200 transition">
                + Nova categoria
            </button>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($categories as $cat)
            <div class="flex items-center justify-between px-5 py-3.5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-gray-50 border border-gray-100 rounded-xl flex items-center justify-center">
                        <i class="fa-solid {{ $cat->icon ?? 'fa-map-pin' }} text-gray-500 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $cat->name_pt }}</p>
                        <p class="text-xs text-gray-400">{{ $cat->name_en }} · {{ $cat->pois_count }} POI{{ $cat->pois_count !== 1 ? 's' : '' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    <button @click="openEditCat({{ $cat->toJson() }})"
                            class="p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition" title="Editar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    @if($cat->pois_count === 0)
                    <button @click="openDeleteCat({{ $cat->toJson() }})"
                            class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Eliminar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                    @else
                    <span class="p-1.5 text-gray-200 cursor-not-allowed" title="Tem POIs associados — mova-os antes de eliminar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>


    {{-- ===== MODALS ===== --}}

    {{-- Add POI Modal --}}
    <div x-show="addModal.open" x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40"
         @click.self="addModal.open = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-900">Adicionar Ponto de Interesse</h3>
                <button @click="addModal.open = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.pois.store') }}" class="px-6 py-5 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nome PT *</label>
                        <input type="text" name="name_pt" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nome EN *</label>
                        <input type="text" name="name_en" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Categoria *</label>
                    <select name="poi_category_id" required
                            class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                        <option value="">Selecionar...</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name_pt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Descrição PT</label>
                        <textarea name="description_pt" rows="3"
                                  class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20 resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Descrição EN</label>
                        <textarea name="description_en" rows="3"
                                  class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20 resize-none"></textarea>
                    </div>
                </div>
                {{-- Localização com mapa interativo --}}
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Localização</label>
                    <p class="text-xs text-gray-400 mb-2">Pesquise a morada ou clique no mapa para posicionar o pin. Pode arrastar o pin para ajustar a posição exacta.</p>
                    <div class="flex gap-2 mb-2">
                        <input type="text" x-model="addModal.searchQuery"
                               @keydown.enter.prevent="geocodeAdd()"
                               placeholder="Ex: Palácio da Pena, Sintra, Portugal"
                               class="flex-1 text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                        <button type="button" @click="geocodeAdd()" :disabled="addModal.geocoding"
                                class="shrink-0 text-sm bg-gray-900 text-white px-3 py-2 rounded-xl hover:bg-gray-700 transition disabled:opacity-50">
                            <span x-show="!addModal.geocoding">Localizar</span>
                            <span x-show="addModal.geocoding">...</span>
                        </button>
                    </div>
                    <div id="add-poi-map" class="poi-mini-map mb-1"></div>
                    <p class="text-xs text-gray-400">Clique no mapa para posicionar · Arraste o pin para ajustar · As coordenadas são preenchidas automaticamente</p>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Latitude *</label>
                        <input type="number" name="lat" step="0.0000001" x-model="addModal.lat"
                               @change="updateAddPin()" placeholder="38.9000000" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Longitude *</label>
                        <input type="number" name="lng" step="0.0000001" x-model="addModal.lng"
                               @change="updateAddPin()" placeholder="-9.3800000" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Distância (km)</label>
                        <input type="number" name="distance_km" step="0.1" min="0" x-model="addModal.distance_km"
                               placeholder="5.0"
                               class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="active" value="1" id="add_active" checked class="rounded">
                    <label for="add_active" class="text-sm text-gray-700">Activo</label>
                </div>
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <button type="button" @click="addModal.open = false"
                            class="text-sm text-gray-600 px-4 py-2 rounded-full hover:bg-gray-100 transition">Cancelar</button>
                    <button type="submit"
                            class="text-sm bg-gray-900 text-white px-5 py-2 rounded-full hover:bg-gray-700 transition font-medium">Adicionar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit POI Modal --}}
    <div x-show="editModal.open" x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40"
         @click.self="editModal.open = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-900">Editar Ponto de Interesse</h3>
                <button @click="editModal.open = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" :action="editModal.action" class="px-6 py-5 space-y-4">
                @csrf @method('PATCH')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nome PT *</label>
                        <input type="text" name="name_pt" x-model="editModal.name_pt" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nome EN *</label>
                        <input type="text" name="name_en" x-model="editModal.name_en" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Categoria *</label>
                    <select name="poi_category_id" x-model="editModal.poi_category_id" required
                            class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name_pt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Descrição PT</label>
                        <textarea name="description_pt" rows="3" x-model="editModal.description_pt"
                                  class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20 resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Descrição EN</label>
                        <textarea name="description_en" rows="3" x-model="editModal.description_en"
                                  class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20 resize-none"></textarea>
                    </div>
                </div>
                {{-- Localização com mapa interativo --}}
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Localização</label>
                    <div class="flex gap-2 mb-2">
                        <input type="text" x-model="editModal.searchQuery"
                               @keydown.enter.prevent="geocodeEdit()"
                               placeholder="Ex: Quinta da Regaleira, Sintra"
                               class="flex-1 text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                        <button type="button" @click="geocodeEdit()" :disabled="editModal.geocoding"
                                class="shrink-0 text-sm bg-gray-900 text-white px-3 py-2 rounded-xl hover:bg-gray-700 transition disabled:opacity-50">
                            <span x-show="!editModal.geocoding">Localizar</span>
                            <span x-show="editModal.geocoding">...</span>
                        </button>
                    </div>
                    <div id="edit-poi-map" class="poi-mini-map mb-1"></div>
                    <p class="text-xs text-gray-400">Clique no mapa para reposicionar · Arraste o pin para ajustar</p>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Latitude *</label>
                        <input type="number" name="lat" step="0.0000001" x-model="editModal.lat"
                               @change="updateEditPin()" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Longitude *</label>
                        <input type="number" name="lng" step="0.0000001" x-model="editModal.lng"
                               @change="updateEditPin()" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Distância (km)</label>
                        <input type="number" name="distance_km" step="0.1" min="0" x-model="editModal.distance_km"
                               class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="active" value="1" id="edit_active" x-model="editModal.active" class="rounded">
                    <label for="edit_active" class="text-sm text-gray-700">Activo</label>
                </div>
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <button type="button" @click="editModal.open = false"
                            class="text-sm text-gray-600 px-4 py-2 rounded-full hover:bg-gray-100 transition">Cancelar</button>
                    <button type="submit"
                            class="text-sm bg-gray-900 text-white px-5 py-2 rounded-full hover:bg-gray-700 transition font-medium">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete POI Modal --}}
    <div x-show="deleteModal.open" x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40"
         @click.self="deleteModal.open = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm" @click.stop>
            <div class="px-6 py-6 text-center">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-1">Eliminar POI</h3>
                <p class="text-sm text-gray-500 mb-5">Tem a certeza que quer eliminar <strong x-text="deleteModal.name"></strong>?</p>
                <div class="flex justify-center gap-3">
                    <button @click="deleteModal.open = false"
                            class="text-sm text-gray-600 px-4 py-2 rounded-full hover:bg-gray-100 transition">Cancelar</button>
                    <form method="POST" :action="deleteModal.action" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="text-sm bg-red-600 text-white px-5 py-2 rounded-full hover:bg-red-700 transition font-medium">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Category Modal --}}
    <div x-show="catAddModal.open" x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40"
         @click.self="catAddModal.open = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-900">Nova Categoria</h3>
                <button @click="catAddModal.open = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.pois.categories.store') }}" class="px-6 py-5 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nome PT *</label>
                        <input type="text" name="name_pt" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nome EN *</label>
                        <input type="text" name="name_en" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Ícone FontAwesome</label>
                    <input type="text" name="icon" placeholder="fa-map-pin"
                           class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                    <p class="text-xs text-gray-400 mt-1">Ex: fa-crown, fa-umbrella-beach, fa-utensils, fa-person-biking</p>
                </div>
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <button type="button" @click="catAddModal.open = false"
                            class="text-sm text-gray-600 px-4 py-2 rounded-full hover:bg-gray-100 transition">Cancelar</button>
                    <button type="submit"
                            class="text-sm bg-gray-900 text-white px-5 py-2 rounded-full hover:bg-gray-700 transition font-medium">Criar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Category Modal --}}
    <div x-show="catEditModal.open" x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40"
         @click.self="catEditModal.open = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-900">Editar Categoria</h3>
                <button @click="catEditModal.open = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" :action="catEditModal.action" class="px-6 py-5 space-y-4">
                @csrf @method('PATCH')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nome PT *</label>
                        <input type="text" name="name_pt" x-model="catEditModal.name_pt" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nome EN *</label>
                        <input type="text" name="name_en" x-model="catEditModal.name_en" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Ícone FontAwesome</label>
                    <input type="text" name="icon" x-model="catEditModal.icon" placeholder="fa-map-pin"
                           class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                </div>
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <button type="button" @click="catEditModal.open = false"
                            class="text-sm text-gray-600 px-4 py-2 rounded-full hover:bg-gray-100 transition">Cancelar</button>
                    <button type="submit"
                            class="text-sm bg-gray-900 text-white px-5 py-2 rounded-full hover:bg-gray-700 transition font-medium">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Category Modal --}}
    <div x-show="catDeleteModal.open" x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40"
         @click.self="catDeleteModal.open = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm" @click.stop>
            <div class="px-6 py-6 text-center">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-1">Eliminar Categoria</h3>
                <p class="text-sm text-gray-500 mb-5">Eliminar <strong x-text="catDeleteModal.name"></strong>?</p>
                <div class="flex justify-center gap-3">
                    <button @click="catDeleteModal.open = false"
                            class="text-sm text-gray-600 px-4 py-2 rounded-full hover:bg-gray-100 transition">Cancelar</button>
                    <form method="POST" :action="catDeleteModal.action" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="text-sm bg-red-600 text-white px-5 py-2 rounded-full hover:bg-red-700 transition font-medium">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.js"></script>
<script>
/* Instâncias de mapa fora do Alpine para evitar reatividade em objetos complexos */
var _poiMaps = { add: null, addMarker: null, edit: null, editMarker: null };

function poisApp() {
    return {
        activeTab: 'all',

        addModal: {
            open: false,
            lat: '', lng: '', distance_km: '',
            searchQuery: '', geocoding: false,
        },
        editModal: {
            open: false, action: '',
            name_pt: '', name_en: '', description_pt: '', description_en: '',
            poi_category_id: '', lat: '', lng: '', distance_km: '', active: true,
            searchQuery: '', geocoding: false,
        },
        deleteModal:    { open: false, action: '', name: '' },
        catAddModal:    { open: false },
        catEditModal:   { open: false, action: '', name_pt: '', name_en: '', icon: '' },
        catDeleteModal: { open: false, action: '', name: '' },

        init() {
            this.$watch('addModal.open', (val) => {
                if (val) setTimeout(() => this.initAddMap(), 150);
            });
            this.$watch('editModal.open', (val) => {
                if (val) setTimeout(() => this.initEditMap(), 150);
            });
        },

        /* ── Mapa do modal Adicionar ─────────────────── */
        initAddMap() {
            if (!document.getElementById('add-poi-map')) return;
            if (_poiMaps.add) {
                _poiMaps.add.invalidateSize();
                return;
            }
            _poiMaps.add = L.map('add-poi-map', { scrollWheelZoom: false })
                            .setView([38.93, -9.38], 11);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(_poiMaps.add);
            _poiMaps.add.on('click', (e) => {
                this.placeAddMarker(e.latlng.lat, e.latlng.lng);
            });
        },

        placeAddMarker(lat, lng) {
            if (_poiMaps.addMarker) _poiMaps.add.removeLayer(_poiMaps.addMarker);
            _poiMaps.addMarker = L.marker([lat, lng], { draggable: true }).addTo(_poiMaps.add);
            this.addModal.lat = parseFloat(lat).toFixed(7);
            this.addModal.lng = parseFloat(lng).toFixed(7);
            _poiMaps.addMarker.on('dragend', (e) => {
                var pos = e.target.getLatLng();
                this.addModal.lat = pos.lat.toFixed(7);
                this.addModal.lng = pos.lng.toFixed(7);
            });
        },

        updateAddPin() {
            if (!_poiMaps.add || !this.addModal.lat || !this.addModal.lng) return;
            var lat = parseFloat(this.addModal.lat);
            var lng = parseFloat(this.addModal.lng);
            if (isNaN(lat) || isNaN(lng)) return;
            _poiMaps.add.setView([lat, lng], 14);
            this.placeAddMarker(lat, lng);
        },

        async geocodeAdd() {
            if (!this.addModal.searchQuery.trim()) return;
            this.addModal.geocoding = true;
            try {
                var url = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q='
                    + encodeURIComponent(this.addModal.searchQuery)
                    + '&email=hello@funtastichouse.pt';
                var res  = await fetch(url, { headers: { 'Accept-Language': 'pt-PT,pt;q=0.9,en;q=0.5' } });
                var data = await res.json();
                if (data.length > 0) {
                    var lat = parseFloat(data[0].lat);
                    var lng = parseFloat(data[0].lon);
                    _poiMaps.add.setView([lat, lng], 15);
                    this.placeAddMarker(lat, lng);
                } else {
                    alert('Local não encontrado. Tente com uma morada mais específica.');
                }
            } catch (e) {
                alert('Erro ao pesquisar. Verifique a ligação e tente de novo.');
            } finally {
                this.addModal.geocoding = false;
            }
        },

        /* ── Mapa do modal Editar ────────────────────── */
        initEditMap() {
            if (!document.getElementById('edit-poi-map')) return;
            var lat  = this.editModal.lat  ? parseFloat(this.editModal.lat)  : 38.93;
            var lng  = this.editModal.lng  ? parseFloat(this.editModal.lng)  : -9.38;
            var zoom = this.editModal.lat  ? 14 : 11;

            if (_poiMaps.edit) {
                _poiMaps.edit.invalidateSize();
                _poiMaps.edit.setView([lat, lng], zoom);
                if (this.editModal.lat && this.editModal.lng) this.placeEditMarker(lat, lng);
                return;
            }
            _poiMaps.edit = L.map('edit-poi-map', { scrollWheelZoom: false })
                             .setView([lat, lng], zoom);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(_poiMaps.edit);
            _poiMaps.edit.on('click', (e) => {
                this.placeEditMarker(e.latlng.lat, e.latlng.lng);
            });
            if (this.editModal.lat && this.editModal.lng) this.placeEditMarker(lat, lng);
        },

        placeEditMarker(lat, lng) {
            if (_poiMaps.editMarker) _poiMaps.edit.removeLayer(_poiMaps.editMarker);
            _poiMaps.editMarker = L.marker([lat, lng], { draggable: true }).addTo(_poiMaps.edit);
            this.editModal.lat = parseFloat(lat).toFixed(7);
            this.editModal.lng = parseFloat(lng).toFixed(7);
            _poiMaps.editMarker.on('dragend', (e) => {
                var pos = e.target.getLatLng();
                this.editModal.lat = pos.lat.toFixed(7);
                this.editModal.lng = pos.lng.toFixed(7);
            });
        },

        updateEditPin() {
            if (!_poiMaps.edit || !this.editModal.lat || !this.editModal.lng) return;
            var lat = parseFloat(this.editModal.lat);
            var lng = parseFloat(this.editModal.lng);
            if (isNaN(lat) || isNaN(lng)) return;
            _poiMaps.edit.setView([lat, lng], 14);
            this.placeEditMarker(lat, lng);
        },

        async geocodeEdit() {
            if (!this.editModal.searchQuery.trim()) return;
            this.editModal.geocoding = true;
            try {
                var url = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q='
                    + encodeURIComponent(this.editModal.searchQuery)
                    + '&email=hello@funtastichouse.pt';
                var res  = await fetch(url, { headers: { 'Accept-Language': 'pt-PT,pt;q=0.9,en;q=0.5' } });
                var data = await res.json();
                if (data.length > 0) {
                    var lat = parseFloat(data[0].lat);
                    var lng = parseFloat(data[0].lon);
                    _poiMaps.edit.setView([lat, lng], 15);
                    this.placeEditMarker(lat, lng);
                } else {
                    alert('Local não encontrado. Tente com uma morada mais específica.');
                }
            } catch (e) {
                alert('Erro ao pesquisar. Verifique a ligação e tente de novo.');
            } finally {
                this.editModal.geocoding = false;
            }
        },

        /* ── Abrir modals ────────────────────────────── */
        openEdit(poi) {
            this.editModal.action          = `/admin/pois/${poi.id}`;
            this.editModal.name_pt         = poi.name_pt;
            this.editModal.name_en         = poi.name_en;
            this.editModal.description_pt  = poi.description_pt ?? '';
            this.editModal.description_en  = poi.description_en ?? '';
            this.editModal.poi_category_id = String(poi.poi_category_id);
            this.editModal.lat             = poi.lat ?? '';
            this.editModal.lng             = poi.lng ?? '';
            this.editModal.distance_km     = poi.distance_km ?? '';
            this.editModal.active          = Boolean(poi.active);
            this.editModal.searchQuery     = '';
            this.editModal.open            = true;
        },

        openDelete(poi) {
            this.deleteModal.action = `/admin/pois/${poi.id}`;
            this.deleteModal.name   = poi.name_pt;
            this.deleteModal.open   = true;
        },

        openEditCat(cat) {
            this.catEditModal.action  = `/admin/pois/categories/${cat.id}`;
            this.catEditModal.name_pt = cat.name_pt;
            this.catEditModal.name_en = cat.name_en;
            this.catEditModal.icon    = cat.icon ?? '';
            this.catEditModal.open    = true;
        },

        openDeleteCat(cat) {
            this.catDeleteModal.action = `/admin/pois/categories/${cat.id}`;
            this.catDeleteModal.name   = cat.name_pt;
            this.catDeleteModal.open   = true;
        },
    };
}
</script>
@endpush
