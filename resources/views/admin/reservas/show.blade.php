@extends('layouts.admin')

@section('title', 'Reserva #' . $reservation->id)

@section('content')
@php
    $statusMap = [
        'pending'   => ['label' => 'Pendente',   'cls' => 'bg-amber-50 text-amber-600 border border-amber-100'],
        'confirmed' => ['label' => 'Confirmado', 'cls' => 'bg-green-50 text-green-600 border border-green-100'],
        'cancelled' => ['label' => 'Cancelado',  'cls' => 'bg-red-50 text-red-600 border border-red-100'],
    ];
    $st = $statusMap[$reservation->status] ?? ['label' => ucfirst($reservation->status), 'cls' => 'bg-gray-100 text-gray-600'];
    $nights = \Carbon\Carbon::parse($reservation->check_in)->diffInDays(\Carbon\Carbon::parse($reservation->check_out));
@endphp

<div class="space-y-5 pt-2">

    {{-- Back + title --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.dashboard') }}"
           class="w-9 h-9 rounded-full bg-white flex items-center justify-center hover:shadow-sm transition"
           style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#374151" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-[22px] font-bold text-gray-900">Reserva <span style="color:#c99f5b">#{{ $reservation->id }}</span></h1>
            <p class="text-sm text-gray-500">Criada em {{ $reservation->created_at->format('d M Y \à\s H:i') }}</p>
        </div>
        <div class="ml-auto">
            <span class="text-sm font-semibold px-4 py-1.5 rounded-full {{ $st['cls'] }}">{{ $st['label'] }}</span>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-5">

        {{-- LEFT: Guest info + notes --}}
        <div class="col-span-12 lg:col-span-7 space-y-5">

            {{-- Guest --}}
            <div class="bg-white rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Dados do Hóspede</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[11px] text-gray-400 mb-0.5 uppercase tracking-wide">Nome</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $reservation->name }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-gray-400 mb-0.5 uppercase tracking-wide">Hóspedes</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $reservation->guests }} pessoa{{ $reservation->guests > 1 ? 's' : '' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-gray-400 mb-0.5 uppercase tracking-wide">Email</p>
                        <a href="mailto:{{ $reservation->email }}" class="text-sm font-medium hover:underline" style="color:#c99f5b">{{ $reservation->email }}</a>
                    </div>
                    <div>
                        <p class="text-[11px] text-gray-400 mb-0.5 uppercase tracking-wide">Telefone</p>
                        <a href="tel:{{ $reservation->phone }}" class="text-sm font-medium text-gray-900 hover:underline">{{ $reservation->phone }}</a>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            @if($reservation->message)
            <div class="bg-white rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Mensagem / Observações</h3>
                <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $reservation->message }}</p>
            </div>
            @endif

            {{-- Contact actions --}}
            <div class="bg-white rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Contactar Hóspede</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="mailto:{{ $reservation->email }}"
                       class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-medium text-white transition hover:opacity-80"
                       style="background:#c99f5b">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Enviar Email
                    </a>
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $reservation->phone) }}"
                       target="_blank"
                       class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-medium bg-green-500 text-white transition hover:opacity-80">
                        <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        WhatsApp
                    </a>
                    <a href="tel:{{ $reservation->phone }}"
                       class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-medium bg-white border border-gray-200 text-gray-700 transition hover:bg-gray-50">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 8V5z"/>
                        </svg>
                        Ligar
                    </a>
                </div>
            </div>

        </div>

        {{-- RIGHT: Reservation details + status --}}
        <div class="col-span-12 lg:col-span-5 space-y-5">

            {{-- Booking summary --}}
            <div class="bg-white rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Resumo da Reserva</h3>

                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <span class="text-xs text-gray-400">Experiência</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $reservation->experience?->name_pt ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <span class="text-xs text-gray-400">Check-in</span>
                        <span class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($reservation->check_in)->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <span class="text-xs text-gray-400">Check-out</span>
                        <span class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($reservation->check_out)->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <span class="text-xs text-gray-400">Noites</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $nights }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-xs text-gray-400">Hóspedes</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $reservation->guests }}</span>
                    </div>
                </div>

                @if($reservation->experience)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    @php
                        $exp    = $reservation->experience;
                        $ci     = \Carbon\Carbon::parse($reservation->check_in);
                        $co     = \Carbon\Carbon::parse($reservation->check_out);
                        $base   = 0; $wknd = 0;
                        $cur    = $ci->copy();
                        while ($cur < $co) {
                            $dow = $cur->dayOfWeek;
                            if ($dow === 0 || $dow === 6) $wknd++; else $base++;
                            $cur->addDay();
                        }
                        $est = ($base * $exp->base_price) + ($wknd * $exp->weekend_price);
                    @endphp
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-400">Estimativa</span>
                        <span class="text-base font-bold" style="color:#c99f5b">{{ number_format($est, 0, ',', '.') }}€</span>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">{{ $base }} noite{{ $base !== 1 ? 's' : '' }} semana + {{ $wknd }} fim de semana</p>
                </div>
                @endif
            </div>

            {{-- Status actions --}}
            <div class="bg-white rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <h3 class="text-sm font-semibold text-gray-900 mb-1">Estado actual</h3>
                <p class="text-xs text-gray-400 mb-4">A gestão completa de estados estará disponível em breve (TASK-08).</p>

                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 text-sm font-semibold px-4 py-2 rounded-full {{ $st['cls'] }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $reservation->status === 'confirmed' ? 'bg-green-500' : ($reservation->status === 'cancelled' ? 'bg-red-500' : 'bg-amber-500') }}"></span>
                        {{ $st['label'] }}
                    </span>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
