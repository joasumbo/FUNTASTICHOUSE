@extends('layouts.admin')

@section('title', 'Páginas')

@section('content')
<div class="space-y-5 pt-2">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.dashboard') }}"
           class="w-9 h-9 rounded-full bg-white flex items-center justify-center hover:shadow-sm transition"
           style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#374151" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-[22px] font-bold text-gray-900">Páginas</h1>
            <p class="text-sm text-gray-500">Política de Privacidade, Termos & Condições</p>
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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach($pages as $pg)
        <div class="bg-white rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-gray-900">{{ $pg->title_pt }}</h3>
                    <p class="text-sm text-gray-400 mt-0.5">{{ $pg->title_en }}</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $pg->active ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                    {{ $pg->active ? 'Ativo' : 'Inativo' }}
                </span>
            </div>
            <p class="text-sm text-gray-500 mb-4 line-clamp-3">{{ Str::limit(strip_tags($pg->content_pt), 150) }}</p>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.paginas.edit', $pg) }}"
                   class="flex-1 text-center py-2.5 rounded-2xl text-sm font-semibold text-white transition hover:opacity-90"
                   style="background:#c99f5b">
                    Editar
                </a>
                <a href="{{ $pg->slug === 'politica-privacidade' ? route('paginas.politica') : route('paginas.termos') }}"
                   target="_blank"
                   class="w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center hover:bg-gray-100 transition">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#6b7280" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection
