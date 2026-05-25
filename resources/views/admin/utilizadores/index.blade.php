@extends('layouts.admin')
@section('title', 'Utilizadores')

@section('content')
<div class="max-w-5xl mx-auto pt-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Utilizadores</h1>
            <p class="text-sm text-gray-500 mt-0.5">Gerir contas e permissões de acesso ao painel.</p>
        </div>
        <a href="{{ route('admin.utilizadores.create') }}"
           class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-semibold text-white transition hover:opacity-90"
           style="background:#c99f5b; box-shadow:0 2px 8px rgba(201,159,91,0.35)">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Novo utilizador
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-100 text-green-700 text-sm rounded-2xl px-4 py-3 mb-5">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-100 text-red-600 text-sm rounded-2xl px-4 py-3 mb-5">
            {{ session('error') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-3xl overflow-hidden" style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 10px 40px rgba(0,0,0,0.04)">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-6 py-4">Utilizador</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-4 py-4 hidden sm:table-cell">Email</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-4 py-4">Papel</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-4 py-4 hidden lg:table-cell">Permissões</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($user->photo)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($user->photo) }}"
                                     alt="{{ $user->name }}"
                                     class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                            @else
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold text-white flex-shrink-0"
                                     style="background:#c99f5b">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                        <span class="ml-1 text-[10px] font-medium text-gray-400">(Você)</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400">@{{ $user->username }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-600 hidden sm:table-cell">{{ $user->email }}</td>
                    <td class="px-4 py-4">
                        @if($user->role === 'superadmin')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold text-white" style="background:#c99f5b">
                                Superadmin
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold text-gray-600 bg-gray-100">
                                Staff
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-4 hidden lg:table-cell">
                        @if($user->role === 'superadmin')
                            <span class="text-xs text-gray-400 italic">Todas as permissões</span>
                        @else
                            @php
                                $granted = collect($user->permissions ?? [])->filter()->keys()
                                    ->map(fn($k) => \App\Http\Controllers\Admin\UtilizadorController::SECTIONS[$k] ?? $k);
                            @endphp
                            @if($granted->isEmpty())
                                <span class="text-xs text-gray-400">Sem permissões</span>
                            @else
                                <span class="text-xs text-gray-600">{{ $granted->implode(', ') }}</span>
                            @endif
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 justify-end">
                            <a href="{{ route('admin.utilizadores.edit', $user) }}"
                               class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium text-gray-600 hover:bg-gray-100 transition">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Editar
                            </a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.utilizadores.destroy', $user) }}"
                                  onsubmit="return confirm('Eliminar {{ addslashes($user->name) }}? Esta ação é irreversível.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium text-red-500 hover:bg-red-50 transition">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Eliminar
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400">
                        Nenhum utilizador encontrado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
