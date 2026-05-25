@extends('layouts.admin')
@section('title', 'Novo Utilizador')

@section('content')
<div class="max-w-2xl mx-auto pt-6">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.utilizadores') }}"
           class="w-9 h-9 rounded-full bg-white flex items-center justify-center hover:shadow-md transition"
           style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#374151" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Novo Utilizador</h1>
            <p class="text-sm text-gray-500 mt-0.5">Criar uma nova conta de acesso ao painel.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-100 rounded-2xl px-4 py-3 mb-5">
            <ul class="text-sm text-red-600 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl p-8" style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 10px 40px rgba(0,0,0,0.04)"
         x-data="{ role: 'staff' }">
        <form method="POST" action="{{ route('admin.utilizadores.store') }}" class="space-y-5">
            @csrf

            {{-- Name + Username --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nome completo</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nome de utilizador</label>
                    <input type="text" name="username" value="{{ old('username') }}" required
                           class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition"
                           placeholder="ex: joao.silva">
                </div>
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition">
            </div>

            {{-- Password --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Senha</label>
                    <input type="password" name="password" required autocomplete="new-password"
                           class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition"
                           placeholder="Mínimo 8 caracteres">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Confirmar senha</label>
                    <input type="password" name="password_confirmation" required autocomplete="new-password"
                           class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition">
                </div>
            </div>

            {{-- Role --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2">Papel</label>
                <div class="flex gap-3">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="radio" name="role" value="staff"
                               x-model="role"
                               {{ old('role', 'staff') === 'staff' ? 'checked' : '' }}
                               class="w-4 h-4" style="accent-color:#c99f5b">
                        <span class="text-sm text-gray-700 font-medium">Staff</span>
                    </label>
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="radio" name="role" value="superadmin"
                               x-model="role"
                               {{ old('role') === 'superadmin' ? 'checked' : '' }}
                               class="w-4 h-4" style="accent-color:#c99f5b">
                        <span class="text-sm text-gray-700 font-medium">Superadmin</span>
                    </label>
                </div>
                <p class="text-xs text-gray-400 mt-1.5">Superadmin tem acesso total. Staff acede apenas às secções autorizadas abaixo.</p>
            </div>

            {{-- Permissions (only for staff) --}}
            <div x-show="role === 'staff'" x-cloak
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="border border-gray-100 rounded-2xl p-5">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Permissões de acesso</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($sections as $key => $label)
                        <label class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 cursor-pointer transition">
                            <input type="checkbox" name="perm_{{ $key }}" value="1"
                                   {{ old('perm_' . $key) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded" style="accent-color:#c99f5b">
                            <span class="text-sm text-gray-700">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.utilizadores') }}"
                   class="px-5 py-2.5 rounded-2xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-2xl text-sm font-semibold text-white transition hover:opacity-90"
                        style="background:#c99f5b; box-shadow:0 2px 8px rgba(201,159,91,0.35)">
                    Criar utilizador
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
