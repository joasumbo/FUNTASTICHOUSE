@extends('layouts.admin')

@section('title', 'Meu Perfil')

@section('content')
@php
    $user    = auth()->user();
    $tab     = session('tab', 'perfil');
    $photoUrl = $user->photo
        ? \Illuminate\Support\Facades\Storage::url($user->photo)
        : null;
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
            <h1 class="text-[22px] font-bold text-gray-900">Meu Perfil</h1>
            <p class="text-sm text-gray-500">Gere os teus dados e credenciais de acesso</p>
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

    <div class="grid grid-cols-12 gap-5">

        {{-- LEFT: Avatar card --}}
        <div class="col-span-12 lg:col-span-4 space-y-5">
            <div class="bg-white rounded-3xl p-6 flex flex-col items-center text-center" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">

                {{-- Photo upload --}}
                <form method="POST" action="{{ route('admin.perfil.update') }}" enctype="multipart/form-data" id="photoForm">
                    @csrf
                    {{-- keep name/email in sync so partial submit doesn't wipe them --}}
                    <input type="hidden" name="name"  value="{{ $user->name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">

                    <div class="relative mb-4" style="cursor:pointer" onclick="document.getElementById('photoInput').click()">
                        <div class="w-24 h-24 rounded-full overflow-hidden flex items-center justify-center text-3xl font-bold text-white" style="background:#c99f5b">
                            @if($photoUrl)
                                <img src="{{ $photoUrl }}" alt="Foto" class="w-full h-full object-cover" id="photoPreview">
                            @else
                                <span id="photoInitial">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                <img src="" alt="" class="w-full h-full object-cover hidden" id="photoPreview">
                            @endif
                        </div>
                        <div class="absolute bottom-0 right-0 w-7 h-7 rounded-full flex items-center justify-center text-white"
                             style="background:#c99f5b;box-shadow:0 2px 6px rgba(201,159,91,0.4)">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>

                    <input type="file" id="photoInput" name="photo" accept="image/*" class="hidden"
                           onchange="previewPhoto(this); document.getElementById('photoForm').submit()">
                </form>

                <h2 class="text-base font-bold text-gray-900 mt-1">{{ $user->name }}</h2>
                <p class="text-sm text-gray-400">{{ $user->email }}</p>
                <span class="mt-2 text-xs font-semibold px-3 py-1 rounded-full bg-amber-50 text-amber-600 border border-amber-100">Super Admin</span>

                <div class="w-full mt-5 pt-5 border-t border-gray-50 space-y-2 text-left">
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>{{ $user->username }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Membro desde {{ $user->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Tab nav --}}
            <div class="bg-white rounded-3xl p-2" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <button @click="tab='perfil'" :class="tab==='perfil' ? 'text-white' : 'text-gray-600 hover:bg-gray-50'"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-medium transition"
                        :style="tab==='perfil' ? 'background:#c99f5b' : ''">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Dados Pessoais
                </button>
                <button @click="tab='password'" :class="tab==='password' ? 'text-white' : 'text-gray-600 hover:bg-gray-50'"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-medium transition"
                        :style="tab==='password' ? 'background:#c99f5b' : ''">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Alterar Senha
                </button>
            </div>
        </div>

        {{-- RIGHT: Forms --}}
        <div class="col-span-12 lg:col-span-8">

            {{-- PERFIL TAB --}}
            <div x-show="tab==='perfil'" class="bg-white rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <h3 class="text-base font-semibold text-gray-900 mb-5">Dados Pessoais</h3>

                <form method="POST" action="{{ route('admin.perfil.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nome completo</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                       class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition"
                                       required>
                                @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nome de utilizador</label>
                                <input type="text" value="{{ $user->username }}" disabled
                                       class="w-full rounded-2xl border border-gray-100 bg-gray-50 px-4 py-3 text-sm text-gray-400 cursor-not-allowed">
                                <p class="text-[10px] text-gray-400 mt-1">Não pode ser alterado</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition"
                                   required>
                            @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Foto de perfil</label>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full overflow-hidden flex items-center justify-center font-bold text-white flex-shrink-0" style="background:#c99f5b">
                                    @if($photoUrl)
                                        <img src="{{ $photoUrl }}" alt="" class="w-full h-full object-cover" id="formPhotoPreview">
                                    @else
                                        <span id="formPhotoInitial">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        <img src="" alt="" class="w-full h-full object-cover hidden" id="formPhotoPreview">
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="photo" accept="image/*"
                                           class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:text-white file:cursor-pointer transition"
                                           style="--file-bg:#c99f5b"
                                           onchange="previewFormPhoto(this)"
                                           id="formPhotoInput">
                                    <p class="text-[10px] text-gray-400 mt-1">JPG, PNG ou WebP · máx. 2MB</p>
                                </div>
                            </div>
                            @error('photo')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                    class="px-6 py-3 rounded-2xl text-sm font-semibold text-white transition hover:opacity-90"
                                    style="background:#c99f5b;box-shadow:0 2px 8px rgba(201,159,91,0.35)">
                                Guardar alterações
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- PASSWORD TAB --}}
            <div x-show="tab==='password'" class="bg-white rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <h3 class="text-base font-semibold text-gray-900 mb-5">Alterar Senha</h3>

                <form method="POST" action="{{ route('admin.perfil.password') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Senha actual</label>
                            <input type="password" name="current_password"
                                   class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition"
                                   placeholder="••••••••" required>
                            @error('current_password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nova senha</label>
                            <input type="password" name="password"
                                   class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition"
                                   placeholder="mínimo 8 caracteres" required>
                            @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Confirmar nova senha</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition"
                                   placeholder="repete a nova senha" required>
                        </div>
                        <div class="pt-2">
                            <button type="submit"
                                    class="px-6 py-3 rounded-2xl text-sm font-semibold text-white transition hover:opacity-90"
                                    style="background:#c99f5b;box-shadow:0 2px 8px rgba(201,159,91,0.35)">
                                Alterar senha
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
function previewPhoto(input) {
    if (!input.files || !input.files[0]) return;
    const url = URL.createObjectURL(input.files[0]);
    const preview = document.getElementById('photoPreview');
    const initial = document.getElementById('photoInitial');
    preview.src = url;
    preview.classList.remove('hidden');
    if (initial) initial.classList.add('hidden');
}
function previewFormPhoto(input) {
    if (!input.files || !input.files[0]) return;
    const url = URL.createObjectURL(input.files[0]);
    const preview = document.getElementById('formPhotoPreview');
    const initial = document.getElementById('formPhotoInitial');
    preview.src = url;
    preview.classList.remove('hidden');
    if (initial) initial.classList.add('hidden');
}
</script>
<style>
#formPhotoInput::file-selector-button {
    background: #c99f5b;
    color: #fff;
    border: none;
    padding: 6px 14px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    transition: opacity .2s;
}
#formPhotoInput::file-selector-button:hover { opacity: .85; }
</style>
@endpush
@endsection
