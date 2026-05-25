@extends('layouts.admin')
@section('title', 'Utilizadores')

@section('content')
<div class="space-y-5 pt-2">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-[22px] font-bold text-gray-900">Utilizadores</h1>
            <p class="text-sm text-gray-500">Gerir contas e permissões de acesso ao painel.</p>
        </div>
        <a href="{{ route('admin.utilizadores.create') }}"
           class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-semibold text-white transition hover:opacity-90"
           style="background:#c99f5b; box-shadow:0 2px 8px rgba(201,159,91,0.35)">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Novo utilizador
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-100 rounded-2xl px-4 py-3 flex items-center gap-3">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-100 rounded-2xl px-4 py-3 flex items-center gap-3">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-red-600 font-medium">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-3xl overflow-hidden" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Utilizador</th>
                        <th class="text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-4 py-4 hidden sm:table-cell">Email</th>
                        <th class="text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-4 py-4">Papel</th>
                        <th class="text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-4 py-4 hidden lg:table-cell">Permissões</th>
                        <th class="text-right text-[11px] font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Acções</th>
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
                                    <p class="text-xs text-gray-400">{{ $user->username }}</p>
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
                                    $granted = collect($user->permissions ?? [])
                                        ->filter()
                                        ->keys()
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
                                   class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-white transition hover:opacity-80"
                                   style="background:#c99f5b">
                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
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
                                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium text-red-500 hover:bg-red-50 transition border border-transparent hover:border-red-100">
                                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
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
                        <td colspan="5">
                            <div class="flex flex-col items-center justify-center py-16 text-center">
                                <div class="w-14 h-14 rounded-full flex items-center justify-center mb-4" style="background:#fdf8f0">
                                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#c99f5b" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-900">Nenhum utilizador encontrado</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
