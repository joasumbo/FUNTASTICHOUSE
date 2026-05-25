@extends('layouts.admin')

@section('title', 'Galeria')

@push('head')
<style>
    /* ── Upload zone ── */
    .gl-upload-zone {
        border: 2px dashed #e5e7eb; border-radius: 16px;
        padding: 32px; text-align: center; transition: all .2s; cursor: pointer;
        background: #fafafa;
    }
    .gl-upload-zone.drag-over,
    .gl-upload-zone:hover { border-color: #c99f5b; background: #fefdf9; }

    /* ── Cards ── */
    .gl-card {
        border-radius: 14px; overflow: hidden; background: #fff;
        box-shadow: 0 1px 4px rgba(0,0,0,.07); transition: box-shadow .2s, transform .15s;
        position: relative;
    }
    .gl-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.1); transform: translateY(-1px); }

    .gl-thumb { width: 100%; height: 160px; object-fit: cover; display: block; }

    .gl-inactive-overlay {
        position: absolute; inset: 0; background: rgba(0,0,0,.45);
        display: flex; align-items: center; justify-content: center;
        pointer-events: none;
    }

    .gl-cat-badge {
        position: absolute; bottom: 52px; left: 8px;
        background: rgba(0,0,0,.65); color: #fff; font-size: 10px; font-weight: 600;
        padding: 2px 8px; border-radius: 99px; text-transform: capitalize;
    }

    .gl-order-badge {
        position: absolute; top: 8px; left: 8px;
        background: rgba(0,0,0,.55); color: #fff; font-size: 10px; font-weight: 700;
        width: 22px; height: 22px; border-radius: 50%; display: flex;
        align-items: center; justify-content: center;
    }

    .gl-card-footer {
        display: flex; align-items: center; justify-content: space-between;
        padding: 8px 10px; background: #fff; border-top: 1px solid #f3f4f6;
    }

    /* ── Inputs ── */
    .gl-input {
        width: 100%; padding: 8px 12px; border-radius: 10px; font-size: 13px;
        border: 1.5px solid #e5e7eb; outline: none; transition: border-color .2s; background: #fff;
    }
    .gl-input:focus { border-color: #c99f5b; }

    /* ── Buttons ── */
    .gl-btn {
        display: inline-flex; align-items: center; gap: 5px;
        background: #111827; color: #fff; border: none; border-radius: 10px;
        padding: 8px 16px; font-size: 13px; font-weight: 600; cursor: pointer; transition: background .2s;
    }
    .gl-btn:hover { background: #1f2937; }
    .gl-btn-ghost {
        display: inline-flex; align-items: center; gap: 4px;
        background: transparent; border: 1.5px solid #e5e7eb; color: #6b7280;
        border-radius: 9px; padding: 6px 12px; font-size: 12px; font-weight: 500;
        cursor: pointer; transition: all .15s;
    }
    .gl-btn-ghost:hover { border-color: #9ca3af; color: #374151; }
    .gl-btn-icon {
        width: 28px; height: 28px; border-radius: 7px; border: none;
        display: inline-flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all .15s; background: #f3f4f6; color: #6b7280;
    }
    .gl-btn-icon:hover { background: #e5e7eb; color: #111827; }
    .gl-btn-icon.danger:hover { background: #fef2f2; color: #dc2626; }

    /* ── Toggle ── */
    .gl-toggle { position: relative; display: inline-block; width: 36px; height: 20px; cursor: pointer; }
    .gl-toggle input { opacity: 0; width: 0; height: 0; }
    .gl-slider { position: absolute; inset: 0; background: #e5e7eb; border-radius: 99px; transition: background .2s; }
    .gl-slider::before { content: ''; position: absolute; width: 14px; height: 14px; background: #fff;
        border-radius: 50%; left: 3px; top: 3px; transition: transform .2s; box-shadow: 0 1px 2px rgba(0,0,0,.2); }
    input:checked + .gl-slider { background: #c99f5b; }
    input:checked + .gl-slider::before { transform: translateX(16px); }

    /* ── Modal ── */
    .gl-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 50;
        display: flex; align-items: center; justify-content: center; padding: 16px; }
    .gl-modal { background: #fff; border-radius: 20px; width: 100%; max-width: 460px;
        box-shadow: 0 20px 60px rgba(0,0,0,.16); padding: 24px; }

    /* ── Pill filter ── */
    .gl-pill { border-radius: 99px; padding: 5px 14px; font-size: 12px; font-weight: 600;
               cursor: pointer; transition: all .2s; text-decoration: none; }
    .gl-pill.active { background: #111827; color: #fff; }
    .gl-pill:not(.active) { background: #fff; color: #6b7280; box-shadow: 0 1px 3px rgba(0,0,0,.06); }
    .gl-pill:not(.active):hover { color: #374151; }

    /* ── Preview chips ── */
    .gl-preview-strip { display: flex; flex-wrap: wrap; gap: 8px; }
    .gl-preview-chip { position: relative; width: 64px; height: 64px; border-radius: 8px; overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,.1); }
    .gl-preview-chip img { width: 100%; height: 100%; object-fit: cover; }
    .gl-preview-chip button { position: absolute; top: 2px; right: 2px; width: 16px; height: 16px;
        background: rgba(0,0,0,.6); color: #fff; border: none; border-radius: 50%; font-size: 9px;
        cursor: pointer; display: flex; align-items: center; justify-content: center; }

    @keyframes glFadeUp { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
    .gl-anim { animation: glFadeUp .28s cubic-bezier(.16,1,.3,1) both; }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
@php
    $predefinedCats = ['imersiva', 'spa', 'exterior', 'jardim', 'piscina', 'interior'];
@endphp

<div x-data="galeriaApp()" x-init="init()" class="space-y-5">

    {{-- ─── HEADER ─── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
        <div>
            <h1 class="text-[26px] font-bold text-gray-900">Galeria</h1>
            <p class="text-sm text-gray-500 mt-0.5">Faça upload, organize e controle a visibilidade das imagens.</p>
        </div>
        <button @click="uploadOpen = true" class="gl-btn self-start sm:self-auto">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Adicionar imagens
        </button>
    </div>

    {{-- ─── FLASH ─── --}}
    @if(session('success'))
    <div class="bg-white rounded-2xl px-5 py-3 flex items-center gap-3 gl-anim" style="border-left:4px solid #16a34a;box-shadow:0 1px 3px rgba(0,0,0,.06)">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        <span class="text-sm font-medium text-gray-700">{{ session('success') }}</span>
    </div>
    @endif
    @if($errors->any())
    <div class="bg-white rounded-2xl px-5 py-3 flex items-start gap-3 gl-anim" style="border-left:4px solid #dc2626;box-shadow:0 1px 3px rgba(0,0,0,.06)">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2.5" class="mt-0.5 flex-shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <ul class="text-sm text-gray-700 space-y-0.5">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- ─── STATS ─── --}}
    <div class="grid grid-cols-3 gap-3">
        @foreach([
            ['Total', $counts['all'],      '#6b7280'],
            ['Activas',   $counts['active'],   '#16a34a'],
            ['Inactivas', $counts['inactive'], '#dc2626'],
        ] as [$label, $val, $color])
        <div class="bg-white rounded-2xl px-4 py-3 gl-anim" style="box-shadow:0 1px 3px rgba(0,0,0,.06)">
            <p class="text-xs text-gray-500 font-medium">{{ $label }}</p>
            <p class="text-2xl font-bold mt-1" style="color:{{ $color }}">{{ $val }}</p>
        </div>
        @endforeach
    </div>

    {{-- ─── FILTERS ─── --}}
    <div class="flex flex-wrap gap-2 items-center">
        <a href="{{ route('admin.galeria') }}"
           class="gl-pill {{ !request()->hasAny(['experience_id','category']) ? 'active' : '' }}">
            Todas ({{ $counts['all'] }})
        </a>
        @foreach($experiences as $exp)
        <a href="{{ route('admin.galeria', ['experience_id' => $exp->id]) }}"
           class="gl-pill {{ request('experience_id') == $exp->id ? 'active' : '' }}">
            {{ $exp->name_pt }}
        </a>
        @endforeach
        @foreach($categories as $cat)
        <a href="{{ route('admin.galeria', ['category' => $cat]) }}"
           class="gl-pill {{ request('category') === $cat ? 'active' : '' }}">
            {{ ucfirst($cat) }}
        </a>
        @endforeach
    </div>

    {{-- ─── GRID ─── --}}
    @if($images->isEmpty())
    <div class="bg-white rounded-2xl px-8 py-16 text-center gl-anim" style="box-shadow:0 1px 3px rgba(0,0,0,.06)">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:#f9fafb">
            <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#d1d5db" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="text-base font-semibold text-gray-700">Sem imagens</p>
        <p class="text-sm text-gray-400 mt-1 mb-5">Faça upload das primeiras imagens da galeria.</p>
        <button @click="uploadOpen = true" class="gl-btn mx-auto">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Adicionar imagens
        </button>
    </div>
    @else
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
        @foreach($images as $i => $img)
        <div class="gl-card gl-anim" style="animation-delay:{{ min($i * 30, 300) }}ms">

            {{-- Thumbnail --}}
            <div class="relative">
                <img src="{{ asset($img->filename) }}" alt="{{ $img->alt_pt ?? $img->filename }}"
                     class="gl-thumb" loading="lazy"
                     onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22160%22 height=%22160%22><rect fill=%22%23f3f4f6%22 width=%22160%22 height=%22160%22/><text y=%2285%22 x=%2280%22 text-anchor=%22middle%22 fill=%22%239ca3af%22 font-size=%2212%22>sem imagem</text></svg>'">

                @if(!$img->active)
                <div class="gl-inactive-overlay">
                    <span class="text-white text-[10px] font-bold bg-black/50 px-2 py-1 rounded-full">Inactiva</span>
                </div>
                @endif

                <span class="gl-order-badge">{{ $img->order }}</span>
                <span class="gl-cat-badge">{{ $img->category }}</span>
            </div>

            {{-- Footer --}}
            <div class="gl-card-footer">
                {{-- Toggle --}}
                <form method="POST" action="{{ route('admin.galeria.toggle', $img) }}">
                    @csrf @method('PATCH')
                    <label class="gl-toggle" title="{{ $img->active ? 'Desactivar' : 'Activar' }}">
                        <input type="checkbox" {{ $img->active ? 'checked' : '' }} onchange="this.form.submit()">
                        <span class="gl-slider"></span>
                    </label>
                </form>

                {{-- Actions --}}
                <div class="flex items-center gap-1">
                    <button
                        @click="openEdit({{ $img->id }}, '{{ addslashes($img->category) }}', '{{ $img->experience_id ?? '' }}', '{{ addslashes($img->alt_pt ?? '') }}', '{{ addslashes($img->alt_en ?? '') }}', {{ $img->order }}, '{{ asset($img->filename) }}')"
                        class="gl-btn-icon" title="Editar">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button
                        @click="openDelete({{ $img->id }}, '{{ asset($img->filename) }}')"
                        class="gl-btn-icon danger" title="Eliminar">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif


    {{-- ══════════════════════════════════ --}}
    {{-- ─── UPLOAD MODAL ─── --}}
    {{-- ══════════════════════════════════ --}}
    <div x-show="uploadOpen" class="gl-overlay" x-cloak @click.self="uploadOpen = false"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="gl-modal"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             @click.stop>

            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Adicionar imagens</h3>
                    <p class="text-xs text-gray-400 mt-0.5">JPG, PNG, WebP · máx. 10 MB por ficheiro</p>
                </div>
                <button @click="uploadOpen = false; clearFiles()" class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center transition text-gray-400">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.galeria.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                {{-- Drop zone --}}
                <div class="gl-upload-zone"
                     :class="{ 'drag-over': dragging }"
                     @dragover.prevent="dragging = true"
                     @dragleave.prevent="dragging = false"
                     @drop.prevent="onDrop($event)"
                     @click="$refs.fileInput.click()">
                    <input type="file" name="images[]" multiple accept="image/*" x-ref="fileInput"
                           class="hidden" @change="onFileChange($event)">

                    <template x-if="previews.length === 0">
                        <div>
                            <svg class="mx-auto mb-2" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#c99f5b" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            <p class="text-sm font-semibold text-gray-700">Arraste imagens ou clique para seleccionar</p>
                            <p class="text-xs text-gray-400 mt-1">Pode seleccionar várias imagens de uma vez</p>
                        </div>
                    </template>

                    <template x-if="previews.length > 0">
                        <div @click.stop>
                            <div class="gl-preview-strip justify-center">
                                <template x-for="(p, i) in previews" :key="i">
                                    <div class="gl-preview-chip">
                                        <img :src="p" alt="">
                                        <button type="button" @click="removePreview(i)">✕</button>
                                    </div>
                                </template>
                            </div>
                            <p class="text-xs text-gray-500 mt-3" x-text="previews.length + ' ficheiro(s) seleccionado(s)'"></p>
                            <button type="button" @click="$refs.fileInput.click()" class="text-xs text-[#c99f5b] mt-1 hover:underline">Adicionar mais</button>
                        </div>
                    </template>
                </div>

                {{-- Category + Experience --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Categoria</label>
                        <input type="text" name="category" list="catList" class="gl-input"
                               placeholder="imersiva, spa, exterior..." required>
                        <datalist id="catList">
                            @foreach($predefinedCats as $c)
                            <option value="{{ $c }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Experiência</label>
                        <select name="experience_id" class="gl-input">
                            <option value="">Nenhuma</option>
                            @foreach($experiences as $exp)
                            <option value="{{ $exp->id }}">{{ $exp->name_pt }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-1 border-t border-gray-100">
                    <button type="button" @click="uploadOpen = false; clearFiles()" class="gl-btn-ghost">Cancelar</button>
                    <button type="submit" class="gl-btn" :disabled="previews.length === 0"
                            :class="previews.length === 0 ? 'opacity-40 cursor-not-allowed' : ''">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <span x-text="'Enviar ' + (previews.length > 0 ? previews.length + ' imagem(ns)' : 'imagens')"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- ─── EDIT MODAL ─── --}}
    <div x-show="editModal.open" class="gl-overlay" x-cloak @click.self="editModal.open = false"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        <div class="gl-modal" @click.stop>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-gray-900">Editar imagem</h3>
                <button @click="editModal.open = false" class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center transition text-gray-400">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Thumbnail preview --}}
            <div class="rounded-xl overflow-hidden mb-4" style="height:120px">
                <img :src="editModal.thumb" alt="" style="width:100%;height:100%;object-fit:cover">
            </div>

            <form method="POST" :action="'/admin/galeria/' + editModal.id" class="space-y-3">
                @csrf @method('PATCH')

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Categoria</label>
                        <input type="text" name="category" list="catListEdit" class="gl-input"
                               x-model="editModal.category" required>
                        <datalist id="catListEdit">
                            @foreach($predefinedCats as $c)
                            <option value="{{ $c }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Experiência</label>
                        <select name="experience_id" class="gl-input" x-model="editModal.experience_id">
                            <option value="">Nenhuma</option>
                            @foreach($experiences as $exp)
                            <option value="{{ $exp->id }}">{{ $exp->name_pt }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Texto alternativo (PT)</label>
                    <input type="text" name="alt_pt" class="gl-input" x-model="editModal.alt_pt" placeholder="Descrição da imagem em português">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Texto alternativo (EN)</label>
                    <input type="text" name="alt_en" class="gl-input" x-model="editModal.alt_en" placeholder="Image description in English">
                </div>
                <div style="max-width:120px">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Ordem</label>
                    <input type="number" name="order" class="gl-input" x-model="editModal.order" min="0" max="9999">
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                    <button type="button" @click="editModal.open = false" class="gl-btn-ghost">Cancelar</button>
                    <button type="submit" class="gl-btn">Guardar</button>
                </div>
            </form>
        </div>
    </div>


    {{-- ─── DELETE MODAL ─── --}}
    <div x-show="deleteModal.open" class="gl-overlay" x-cloak @click.self="deleteModal.open = false"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        <div class="gl-modal" style="max-width:380px" @click.stop>
            <div class="flex flex-col items-center text-center gap-3 mb-5">
                <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background:#fef2f2">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div class="rounded-xl overflow-hidden" style="width:80px;height:60px">
                    <img :src="deleteModal.thumb" alt="" style="width:100%;height:100%;object-fit:cover">
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Eliminar imagem?</h3>
                    <p class="text-xs text-gray-400 mt-1">O ficheiro será apagado permanentemente.</p>
                </div>
            </div>
            <form method="POST" :action="'/admin/galeria/' + deleteModal.id" class="flex gap-2">
                @csrf @method('DELETE')
                <button type="button" @click="deleteModal.open = false" class="gl-btn-ghost flex-1">Cancelar</button>
                <button type="submit" class="gl-btn flex-1" style="background:#dc2626">Eliminar</button>
            </form>
        </div>
    </div>

</div>

<script>
function galeriaApp() {
    return {
        uploadOpen: false,
        dragging: false,
        previews: [],
        files: [],

        editModal: { open: false, id: null, category: '', experience_id: '', alt_pt: '', alt_en: '', order: 0, thumb: '' },
        deleteModal: { open: false, id: null, thumb: '' },

        init() {},

        onFileChange(e) {
            this.addFiles(e.target.files);
        },
        onDrop(e) {
            this.dragging = false;
            this.addFiles(e.dataTransfer.files);
        },
        addFiles(fileList) {
            Array.from(fileList).forEach(f => {
                if (!f.type.startsWith('image/')) return;
                this.files.push(f);
                const reader = new FileReader();
                reader.onload = ev => this.previews.push(ev.target.result);
                reader.readAsDataURL(f);
            });
        },
        removePreview(i) {
            this.previews.splice(i, 1);
            this.files.splice(i, 1);
        },
        clearFiles() {
            this.previews = [];
            this.files = [];
        },

        openEdit(id, category, expId, altPt, altEn, order, thumb) {
            this.editModal = { open: true, id, category, experience_id: String(expId), alt_pt: altPt, alt_en: altEn, order, thumb };
        },
        openDelete(id, thumb) {
            this.deleteModal = { open: true, id, thumb };
        },
    };
}
</script>
@endsection
