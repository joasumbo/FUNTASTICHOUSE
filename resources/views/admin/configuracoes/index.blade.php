@extends('layouts.admin')

@section('title', 'Configurações')

@section('content')
@php
    $tab = session('active_tab', 'geral');
    $s   = $settings;

    $inp = 'w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition';
    $ta  = $inp . ' resize-none';
    $lbl = 'block text-xs font-semibold text-gray-600 mb-1.5';
    $btn = 'px-6 py-3 rounded-2xl text-sm font-semibold text-white transition hover:opacity-90';
@endphp

<div class="space-y-5 pt-2" x-data="{ tab: '{{ $tab }}' }">

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
            <h1 class="text-[22px] font-bold text-gray-900">Configurações</h1>
            <p class="text-sm text-gray-500">Identidade, contactos, redes sociais e SEO do site</p>
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

    @if($errors->any())
    <div class="bg-red-50 border border-red-100 rounded-2xl px-4 py-3">
        <ul class="space-y-1">
            @foreach($errors->all() as $err)
            <li class="text-sm text-red-600 flex items-center gap-2">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                {{ $err }}
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-12 gap-5">

        {{-- LEFT: Tab nav --}}
        <div class="col-span-12 lg:col-span-3 space-y-3">
            <div class="bg-white rounded-3xl p-2" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                @php
                    $tabs = [
                        ['key' => 'geral',     'label' => 'Geral',          'd' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                        ['key' => 'contactos', 'label' => 'Contactos',      'd' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z'],
                        ['key' => 'social',    'label' => 'Redes Sociais',  'd' => 'M7 20l4-16m2 16l4-16M6 9h14M4 15h14'],
                        ['key' => 'seo',       'label' => 'SEO',            'd' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
                    ];
                @endphp
                @foreach($tabs as $t)
                <button
                    @click="tab = '{{ $t['key'] }}'"
                    :class="tab === '{{ $t['key'] }}' ? 'text-white' : 'text-gray-600 hover:bg-gray-50'"
                    :style="tab === '{{ $t['key'] }}' ? 'background:#c99f5b' : ''"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-medium transition text-left"
                >
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $t['d'] }}"/>
                    </svg>
                    {{ $t['label'] }}
                </button>
                @endforeach
            </div>

            {{-- Current logo preview --}}
            @if(!empty($s['logo']))
            <div class="bg-white rounded-3xl p-4" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-3">Logotipo actual</p>
                <img src="{{ asset($s['logo']) }}" alt="Logotipo" class="max-h-16 object-contain">
            </div>
            @endif
        </div>

        {{-- RIGHT: Panels --}}
        <div class="col-span-12 lg:col-span-9">

            {{-- ── GERAL ── --}}
            <div x-show="tab === 'geral'" class="bg-white rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <h3 class="text-base font-semibold text-gray-900 mb-1">Identidade do Site</h3>
                <p class="text-xs text-gray-400 mb-6">Nome, logotipo e ícones do browser</p>

                <form method="POST" action="{{ route('admin.configuracoes.geral') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-5">

                        <div>
                            <label class="{{ $lbl }}">Nome do site</label>
                            <input type="text" name="site_name" value="{{ old('site_name', $s['site_name'] ?? 'Funtastic House') }}"
                                   class="{{ $inp }}" placeholder="Ex: Funtastic House" required>
                            @error('site_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Logo --}}
                        <div>
                            <label class="{{ $lbl }}">Logotipo</label>
                            <div class="flex items-center gap-4">
                                @if(!empty($s['logo']))
                                <div class="w-16 h-16 rounded-2xl border border-gray-100 bg-gray-50 flex items-center justify-center overflow-hidden flex-shrink-0">
                                    <img src="{{ asset($s['logo']) }}" alt="Logo" class="max-w-full max-h-full object-contain" id="logoPreview">
                                </div>
                                @else
                                <div class="w-16 h-16 rounded-2xl border border-dashed border-gray-200 bg-gray-50 flex items-center justify-center flex-shrink-0">
                                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#d1d5db" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                @endif
                                <div class="flex-1">
                                    <input type="file" name="logo" accept="image/*" class="w-full text-sm text-gray-500 fh-file-input" id="logoInput" onchange="previewImg(this,'logoPreview')">
                                    <p class="text-[10px] text-gray-400 mt-1">JPG, PNG, SVG ou WebP · máx. 2 MB</p>
                                </div>
                            </div>
                            @error('logo')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- Favicon site --}}
                            <div>
                                <label class="{{ $lbl }}">Favicon do site <span class="font-normal text-gray-400">(browser tab)</span></label>
                                <div class="flex items-center gap-3">
                                    @if(!empty($s['favicon']))
                                    <img src="{{ asset($s['favicon']) }}" alt="Favicon" class="w-8 h-8 flex-shrink-0 rounded" id="faviconPreview">
                                    @else
                                    <div class="w-8 h-8 rounded border border-dashed border-gray-200 bg-gray-50 flex-shrink-0 flex items-center justify-center" id="faviconPreview">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#d1d5db" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    @endif
                                    <div class="flex-1">
                                        <input type="file" name="favicon" accept=".ico,.png" class="w-full text-sm text-gray-500 fh-file-input">
                                        <p class="text-[10px] text-gray-400 mt-1">.ico ou .png · máx. 512 KB</p>
                                    </div>
                                </div>
                                @error('favicon')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Favicon admin --}}
                            <div>
                                <label class="{{ $lbl }}">Favicon do painel admin</label>
                                <div class="flex items-center gap-3">
                                    @if(!empty($s['admin_favicon']))
                                    <img src="{{ asset($s['admin_favicon']) }}" alt="Admin Favicon" class="w-8 h-8 flex-shrink-0 rounded">
                                    @else
                                    <div class="w-8 h-8 rounded border border-dashed border-gray-200 bg-gray-50 flex-shrink-0 flex items-center justify-center">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#d1d5db" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    @endif
                                    <div class="flex-1">
                                        <input type="file" name="admin_favicon" accept=".ico,.png" class="w-full text-sm text-gray-500 fh-file-input">
                                        <p class="text-[10px] text-gray-400 mt-1">.ico ou .png · máx. 512 KB</p>
                                    </div>
                                </div>
                                @error('admin_favicon')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="pt-1">
                            <button type="submit" class="{{ $btn }}" style="background:#c99f5b;box-shadow:0 2px 8px rgba(201,159,91,0.35)">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ── CONTACTOS ── --}}
            <div x-show="tab === 'contactos'" x-cloak class="bg-white rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <h3 class="text-base font-semibold text-gray-900 mb-1">Contactos</h3>
                <p class="text-xs text-gray-400 mb-6">Informações de contacto e localização</p>

                <form method="POST" action="{{ route('admin.configuracoes.contactos') }}">
                    @csrf
                    <div class="space-y-5">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="{{ $lbl }}">Telefone</label>
                                <input type="text" name="phone" value="{{ old('phone', $s['phone'] ?? '') }}"
                                       class="{{ $inp }}" placeholder="+351 900 000 000">
                                @error('phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="{{ $lbl }}">Email</label>
                                <input type="email" name="email" value="{{ old('email', $s['email'] ?? '') }}"
                                       class="{{ $inp }}" placeholder="hello@funtastichouse.pt">
                                @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label class="{{ $lbl }}">WhatsApp <span class="font-normal text-gray-400">(só números, com indicativo)</span></label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $s['whatsapp'] ?? '') }}"
                                   class="{{ $inp }}" placeholder="351900000000">
                            @error('whatsapp')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="{{ $lbl }}">Morada curta <span class="font-normal text-gray-400">(usada no header/footer)</span></label>
                            <input type="text" name="address" value="{{ old('address', $s['address'] ?? '') }}"
                                   class="{{ $inp }}" placeholder="Sintra / Ericeira / Mafra, Portugal">
                            @error('address')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="{{ $lbl }}">Morada completa</label>
                            <textarea name="address_full" rows="3" class="{{ $ta }}"
                                      placeholder="Rua, número, código postal, localidade...">{{ old('address_full', $s['address_full'] ?? '') }}</textarea>
                            @error('address_full')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="{{ $lbl }}">URL de embed do Google Maps</label>
                            <textarea name="maps_embed_url" rows="3" class="{{ $ta }}"
                                      placeholder="https://www.google.com/maps/embed?pb=...">{{ old('maps_embed_url', $s['maps_embed_url'] ?? '') }}</textarea>
                            <p class="text-[10px] text-gray-400 mt-1">Vai ao Google Maps → Partilhar → Incorporar mapa → copia o src do iframe</p>
                            @error('maps_embed_url')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="pt-1">
                            <button type="submit" class="{{ $btn }}" style="background:#c99f5b;box-shadow:0 2px 8px rgba(201,159,91,0.35)">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ── REDES SOCIAIS ── --}}
            <div x-show="tab === 'social'" x-cloak class="bg-white rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <h3 class="text-base font-semibold text-gray-900 mb-1">Redes Sociais</h3>
                <p class="text-xs text-gray-400 mb-6">Links para os perfis nas redes sociais</p>

                <form method="POST" action="{{ route('admin.configuracoes.social') }}">
                    @csrf
                    <div class="space-y-5">

                        <div>
                            <label class="{{ $lbl }}">
                                <span class="flex items-center gap-2">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                                    </svg>
                                    Instagram
                                </span>
                            </label>
                            <input type="url" name="instagram_url" value="{{ old('instagram_url', $s['instagram_url'] ?? '') }}"
                                   class="{{ $inp }}" placeholder="https://instagram.com/funtastichouse">
                            @error('instagram_url')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="{{ $lbl }}">
                                <span class="flex items-center gap-2">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                                    </svg>
                                    Facebook
                                </span>
                            </label>
                            <input type="url" name="facebook_url" value="{{ old('facebook_url', $s['facebook_url'] ?? '') }}"
                                   class="{{ $inp }}" placeholder="https://facebook.com/funtastichouse">
                            @error('facebook_url')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="pt-1">
                            <button type="submit" class="{{ $btn }}" style="background:#c99f5b;box-shadow:0 2px 8px rgba(201,159,91,0.35)">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ── SEO ── --}}
            <div x-show="tab === 'seo'" x-cloak class="bg-white rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <h3 class="text-base font-semibold text-gray-900 mb-1">SEO & Open Graph</h3>
                <p class="text-xs text-gray-400 mb-6">Títulos, descrições e imagem de partilha</p>

                <form method="POST" action="{{ route('admin.configuracoes.seo') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-5">

                        {{-- Meta title --}}
                        <div>
                            <p class="{{ $lbl }}">Título da página (meta title)</p>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-[11px] font-medium text-gray-500 mb-1 flex items-center gap-1.5">
                                        <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-1.5 py-0.5 rounded">PT</span>
                                        Português
                                    </label>
                                    <input type="text" name="meta_title_pt" value="{{ old('meta_title_pt', $s['meta_title_pt'] ?? '') }}"
                                           class="{{ $inp }}" placeholder="Funtastic House — Alojamento Temático perto de Sintra" maxlength="160">
                                    @error('meta_title_pt')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="text-[11px] font-medium text-gray-500 mb-1 flex items-center gap-1.5">
                                        <span class="bg-blue-100 text-blue-600 text-[10px] font-bold px-1.5 py-0.5 rounded">EN</span>
                                        Inglês
                                    </label>
                                    <input type="text" name="meta_title_en" value="{{ old('meta_title_en', $s['meta_title_en'] ?? '') }}"
                                           class="{{ $inp }}" placeholder="Funtastic House — Themed Accommodation near Sintra" maxlength="160">
                                    @error('meta_title_en')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">Recomendado: 50–60 caracteres</p>
                        </div>

                        {{-- Meta description --}}
                        <div>
                            <p class="{{ $lbl }}">Descrição (meta description)</p>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-[11px] font-medium text-gray-500 mb-1 flex items-center gap-1.5">
                                        <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-1.5 py-0.5 rounded">PT</span>
                                        Português
                                    </label>
                                    <textarea name="meta_desc_pt" rows="3" class="{{ $ta }}"
                                              placeholder="Alojamento local temático único perto de Sintra..." maxlength="320">{{ old('meta_desc_pt', $s['meta_desc_pt'] ?? '') }}</textarea>
                                    @error('meta_desc_pt')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="text-[11px] font-medium text-gray-500 mb-1 flex items-center gap-1.5">
                                        <span class="bg-blue-100 text-blue-600 text-[10px] font-bold px-1.5 py-0.5 rounded">EN</span>
                                        Inglês
                                    </label>
                                    <textarea name="meta_desc_en" rows="3" class="{{ $ta }}"
                                              placeholder="Unique themed local accommodation near Sintra..." maxlength="320">{{ old('meta_desc_en', $s['meta_desc_en'] ?? '') }}</textarea>
                                    @error('meta_desc_en')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">Recomendado: 150–160 caracteres</p>
                        </div>

                        {{-- OG image --}}
                        <div>
                            <label class="{{ $lbl }}">Imagem Open Graph <span class="font-normal text-gray-400">(partilha nas redes sociais)</span></label>
                            <div class="flex items-start gap-4">
                                @if(!empty($s['og_image']))
                                <div class="w-32 h-20 rounded-xl border border-gray-100 overflow-hidden bg-gray-50 flex-shrink-0">
                                    <img src="{{ asset($s['og_image']) }}" alt="OG Image" class="w-full h-full object-cover" id="ogPreview">
                                </div>
                                @else
                                <div class="w-32 h-20 rounded-xl border border-dashed border-gray-200 bg-gray-50 flex items-center justify-center flex-shrink-0">
                                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#d1d5db" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                @endif
                                <div class="flex-1">
                                    <input type="file" name="og_image" accept="image/*" class="w-full text-sm text-gray-500 fh-file-input" onchange="previewImg(this,'ogPreview')">
                                    <p class="text-[10px] text-gray-400 mt-1">JPG ou PNG · 1200×630 px recomendado · máx. 2 MB</p>
                                </div>
                            </div>
                            @error('og_image')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="pt-1">
                            <button type="submit" class="{{ $btn }}" style="background:#c99f5b;box-shadow:0 2px 8px rgba(201,159,91,0.35)">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>{{-- end right --}}
    </div>{{-- end grid --}}
</div>

@push('scripts')
<script>
function previewImg(input, previewId) {
    if (!input.files || !input.files[0]) return;
    const url  = URL.createObjectURL(input.files[0]);
    let prev   = document.getElementById(previewId);
    if (!prev) {
        prev = document.createElement('img');
        prev.id = previewId;
        prev.className = 'w-full h-full object-cover';
        input.closest('.flex').querySelector('div').appendChild(prev);
    }
    prev.src = url;
    prev.classList.remove('hidden');
}
</script>
<style>
.fh-file-input::file-selector-button {
    background: #c99f5b;
    color: #fff;
    border: none;
    padding: 6px 14px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    transition: opacity .2s;
    margin-right: 10px;
}
.fh-file-input::file-selector-button:hover { opacity: .85; }
</style>
@endpush
@endsection
