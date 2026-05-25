@extends('layouts.admin')
@section('title', 'Editar Utilizador')

@section('content')
<div class="space-y-5 pt-2" x-data="{ role: '{{ old('role', $utilizador->role) }}' }">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.utilizadores') }}"
           class="w-9 h-9 rounded-full bg-white flex items-center justify-center hover:shadow-sm transition"
           style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#374151" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-[22px] font-bold text-gray-900">{{ $utilizador->name }}</h1>
            <p class="text-sm text-gray-500">{{ $utilizador->username }} · {{ $utilizador->email }}</p>
        </div>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-100 rounded-2xl px-4 py-3">
        @foreach($errors->all() as $err)
        <p class="text-sm text-red-600">{{ $err }}</p>
        @endforeach
    </div>
    @endif
    @if(session('success'))
    <div class="bg-green-50 border border-green-100 rounded-2xl px-4 py-3 flex items-center gap-3">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.utilizadores.update', $utilizador) }}" class="space-y-5">
        @csrf
        @method('PATCH')

        {{-- Dados básicos --}}
        <div class="bg-white rounded-3xl p-6 space-y-4" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Dados da conta</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nome completo</label>
                    <input type="text" name="name" value="{{ old('name', $utilizador->name) }}" required
                           class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nome de utilizador</label>
                    <input type="text" name="username" value="{{ old('username', $utilizador->username) }}" required
                           class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', $utilizador->email) }}" required
                       class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Nova senha <span class="font-normal text-gray-400">(deixar em branco para manter)</span>
                    </label>
                    <input type="password" name="password" autocomplete="new-password"
                           placeholder="Mínimo 8 caracteres"
                           class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Confirmar nova senha</label>
                    <input type="password" name="password_confirmation" autocomplete="new-password"
                           class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition">
                </div>
            </div>
        </div>

        {{-- Papel --}}
        <div class="bg-white rounded-3xl p-6 space-y-4" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Papel e permissões</p>

            @if($utilizador->id === auth()->id())
                <input type="hidden" name="role" value="superadmin">
                <div class="px-4 py-3 rounded-2xl bg-amber-50/40 border border-[#c99f5b]/30 text-sm text-gray-700">
                    <strong>Superadmin</strong> — Não é possível alterar o seu próprio papel.
                </div>
            @else
                <div class="flex gap-3">
                    <label class="flex items-center gap-2.5 cursor-pointer px-4 py-3 rounded-2xl border transition"
                           :class="role === 'staff' ? 'border-[#c99f5b] bg-amber-50/40' : 'border-gray-200 hover:border-gray-300'">
                        <input type="radio" name="role" value="staff" x-model="role"
                               class="w-4 h-4" style="accent-color:#c99f5b">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Staff</p>
                            <p class="text-xs text-gray-400">Acede às secções autorizadas</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-2.5 cursor-pointer px-4 py-3 rounded-2xl border transition"
                           :class="role === 'superadmin' ? 'border-[#c99f5b] bg-amber-50/40' : 'border-gray-200 hover:border-gray-300'">
                        <input type="radio" name="role" value="superadmin" x-model="role"
                               class="w-4 h-4" style="accent-color:#c99f5b">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Superadmin</p>
                            <p class="text-xs text-gray-400">Acesso total ao painel</p>
                        </div>
                    </label>
                </div>
            @endif

            {{-- Permissões (só para staff) --}}
            <div x-show="role === 'staff'" x-cloak
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <p class="text-xs font-semibold text-gray-500 mb-3">Secções permitidas</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                    @foreach($sections as $key => $label)
                    @php $checked = old('perm_' . $key, $utilizador->permissions[$key] ?? false); @endphp
                    <label class="flex items-center gap-3 px-4 py-3 rounded-2xl border border-gray-100 hover:border-[#c99f5b]/40 hover:bg-amber-50/20 cursor-pointer transition">
                        <input type="checkbox" name="perm_{{ $key }}" value="1"
                               {{ $checked ? 'checked' : '' }}
                               class="w-4 h-4 rounded" style="accent-color:#c99f5b">
                        <span class="text-sm text-gray-700">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="px-8 py-3 rounded-2xl text-sm font-semibold text-white transition hover:opacity-90"
                    style="background:#c99f5b">
                Guardar alterações
            </button>
            <a href="{{ route('admin.utilizadores') }}"
               class="px-5 py-3 rounded-2xl text-sm font-medium text-gray-600 hover:bg-white transition"
               style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                Cancelar
            </a>
        </div>
    </form>

</div>
@endsection
