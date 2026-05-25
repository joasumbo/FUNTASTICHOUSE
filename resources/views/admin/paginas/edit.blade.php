@extends('layouts.admin')

@section('title', 'Editar Página')

@section('content')
<div class="space-y-5 pt-2" x-data="{ tab: 'pt' }">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.paginas') }}"
           class="w-9 h-9 rounded-full bg-white flex items-center justify-center hover:shadow-sm transition"
           style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#374151" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-[22px] font-bold text-gray-900">{{ $page->title_pt }}</h1>
            <p class="text-sm text-gray-500">Editar conteúdo PT e EN</p>
        </div>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-100 rounded-2xl px-4 py-3">
        @foreach($errors->all() as $err)
        <p class="text-sm text-red-600">{{ $err }}</p>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('admin.paginas.update', $page) }}">
        @csrf
        @method('PATCH')

        {{-- Lang tabs --}}
        <div class="flex gap-2 mb-5">
            <button type="button" @click="tab = 'pt'"
                :class="tab === 'pt' ? 'text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                :style="tab === 'pt' ? 'background:#c99f5b' : ''"
                class="px-5 py-2 rounded-full text-sm font-semibold transition"
                style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                🇵🇹 Português
            </button>
            <button type="button" @click="tab = 'en'"
                :class="tab === 'en' ? 'text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                :style="tab === 'en' ? 'background:#c99f5b' : ''"
                class="px-5 py-2 rounded-full text-sm font-semibold transition"
                style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                🇬🇧 English
            </button>
        </div>

        {{-- PT fields --}}
        <div x-show="tab === 'pt'" class="bg-white rounded-3xl p-6 space-y-4" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Título (PT)</label>
                <input type="text" name="title_pt" value="{{ old('title_pt', $page->title_pt) }}"
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Conteúdo (PT)</label>
                <textarea name="content_pt" rows="18"
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition resize-none font-mono">{{ old('content_pt', $page->content_pt) }}</textarea>
                <p class="text-xs text-gray-400 mt-1.5">Use **texto** para negrito. Parágrafos separados por linha vazia.</p>
            </div>
        </div>

        {{-- EN fields --}}
        <div x-show="tab === 'en'" class="bg-white rounded-3xl p-6 space-y-4" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Title (EN)</label>
                <input type="text" name="title_en" value="{{ old('title_en', $page->title_en) }}"
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Content (EN)</label>
                <textarea name="content_en" rows="18"
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition resize-none font-mono">{{ old('content_en', $page->content_en) }}</textarea>
                <p class="text-xs text-gray-400 mt-1.5">Use **text** for bold. Paragraphs separated by blank line.</p>
            </div>
        </div>

        <div class="flex items-center gap-3 mt-5">
            <button type="submit"
                class="px-8 py-3 rounded-2xl text-sm font-semibold text-white transition hover:opacity-90"
                style="background:#c99f5b">
                Guardar alterações
            </button>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" value="1" {{ $page->active ? 'checked' : '' }}
                    class="w-4 h-4 rounded accent-[#c99f5b]">
                <span class="text-sm text-gray-600">Página ativa</span>
            </label>
        </div>
    </form>

</div>
@endsection
