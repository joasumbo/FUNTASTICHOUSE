@extends('layouts.site')

@section('title', (app()->getLocale() === 'pt' ? $experience->name_pt : $experience->name_en) . ' — Funtastic House')

@section('content')

@php
    $name     = app()->getLocale() === 'pt' ? $experience->name_pt     : $experience->name_en;
    $desc     = app()->getLocale() === 'pt' ? $experience->description_pt : $experience->description_en;
    $shortDesc = app()->getLocale() === 'pt' ? $experience->short_description_pt : $experience->short_description_en;
    $isImersiva = $experience->slug === 'imersiva';
    $heroImg  = $isImersiva ? asset('images/rooms/room-1.jpg') : asset('images/spa/spa-hero.jpg');
@endphp

{{-- Page Header --}}
<section class="page-header page-header-text-light py-0 mb-0">
    <div class="hero-wrap" style="height:280px;">
        <div class="hero-mask opacity-8 bg-black"></div>
        <div class="hero-bg" style="background-image:url('{{ $heroImg }}');"></div>
        <div class="hero-content d-flex align-items-end pb-5 h-100">
            <div class="container">
                <h1 class="heading-font-family text-white fw-700 mb-1">{{ $name }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb text-3">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="link-primary">Início</a></li>
                        <li class="breadcrumb-item active">{{ $name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row g-5 align-items-center mb-5 {{ $isImersiva ? '' : 'flex-lg-row-reverse' }}">
            <div class="col-lg-6 wow {{ $isImersiva ? 'fadeInLeft' : 'fadeInRight' }}">
                <p><span class="text-3 text-uppercase fw-600 rounded-pill border border-dark border-opacity-10 px-3 py-1">
                    {{ $isImersiva ? 'Casa Principal' : 'Casa Spa' }}
                </span></p>
                <h2 class="heading-font-family text-13 fw-600 lh-sm mb-3">{{ $name }}</h2>
                <p class="text-5 text-body-secondary">{{ $shortDesc }}</p>
                <p class="text-body-secondary mb-4">{{ $desc }}</p>

                {{-- Features list --}}
                <div class="row g-3 mb-4">
                    @if($isImersiva)
                    <div class="col-6"><div class="d-flex align-items-center gap-2"><i class="fa-solid fa-check text-primary"></i><span class="text-4">{{ $experience->bedrooms }} Quartos Temáticos</span></div></div>
                    <div class="col-6"><div class="d-flex align-items-center gap-2"><i class="fa-solid fa-check text-primary"></i><span class="text-4">Piscina</span></div></div>
                    <div class="col-6"><div class="d-flex align-items-center gap-2"><i class="fa-solid fa-check text-primary"></i><span class="text-4">Churrasqueira</span></div></div>
                    <div class="col-6"><div class="d-flex align-items-center gap-2"><i class="fa-solid fa-check text-primary"></i><span class="text-4">Jardim Privado</span></div></div>
                    <div class="col-6"><div class="d-flex align-items-center gap-2"><i class="fa-solid fa-check text-primary"></i><span class="text-4">Teto Estrelado Bluetooth</span></div></div>
                    <div class="col-6"><div class="d-flex align-items-center gap-2"><i class="fa-solid fa-check text-primary"></i><span class="text-4">Som Ambiente Passarinhos</span></div></div>
                    @else
                    <div class="col-6"><div class="d-flex align-items-center gap-2"><i class="fa-solid fa-check text-primary"></i><span class="text-4">{{ $experience->bedrooms }} Quartos</span></div></div>
                    <div class="col-6"><div class="d-flex align-items-center gap-2"><i class="fa-solid fa-check text-primary"></i><span class="text-4">Jacuzzi Privado</span></div></div>
                    <div class="col-6"><div class="d-flex align-items-center gap-2"><i class="fa-solid fa-check text-primary"></i><span class="text-4">Pátio Exclusivo</span></div></div>
                    <div class="col-6"><div class="d-flex align-items-center gap-2"><i class="fa-solid fa-check text-primary"></i><span class="text-4">Cozinha Equipada</span></div></div>
                    @endif
                    <div class="col-6"><div class="d-flex align-items-center gap-2"><i class="fa-solid fa-check text-primary"></i><span class="text-4">WiFi</span></div></div>
                    <div class="col-6"><div class="d-flex align-items-center gap-2"><i class="fa-solid fa-check text-primary"></i><span class="text-4">Estacionamento</span></div></div>
                </div>

                <div class="d-flex align-items-center gap-4">
                    <div>
                        <p class="text-3 text-body-secondary mb-0">Desde</p>
                        <h3 class="heading-font-family text-11 fw-700 text-primary mb-0">
                            {{ number_format($experience->base_price, 0, ',', '.') }}€
                            <span class="text-5 text-body-secondary fw-400">/ noite</span>
                        </h3>
                    </div>
                    <a class="btn btn-new btn-primary rounded-pill" href="{{ route('reservas') }}">
                        <span class="btn-text"><span>Reservar</span></span>
                        <span class="btn-icon"><i class="fa-solid fa-arrow-right"></i></span>
                    </a>
                </div>
            </div>

            {{-- Images --}}
            <div class="col-lg-6 wow {{ $isImersiva ? 'fadeInRight' : 'fadeInLeft' }}">
                <div class="row g-3">
                    @if($isImersiva)
                    <div class="col-12"><img class="img-fluid rounded-4" src="{{ asset('images/rooms/room-1.jpg') }}" alt="{{ $name }}" style="height:300px;object-fit:cover;width:100%;"></div>
                    <div class="col-6"><img class="img-fluid rounded-4" src="{{ asset('images/rooms/room-2.jpg') }}" alt="" style="height:180px;object-fit:cover;width:100%;"></div>
                    <div class="col-6"><img class="img-fluid rounded-4" src="{{ asset('images/rooms/room-3.jpg') }}" alt="" style="height:180px;object-fit:cover;width:100%;"></div>
                    @else
                    <div class="col-12"><img class="img-fluid rounded-4" src="{{ asset('images/spa/spa-hero.jpg') }}" alt="{{ $name }}" style="height:300px;object-fit:cover;width:100%;"></div>
                    <div class="col-6"><img class="img-fluid rounded-4" src="{{ asset('images/spa/spa.jpg') }}" alt="" style="height:180px;object-fit:cover;width:100%;"></div>
                    <div class="col-6"><img class="img-fluid rounded-4" src="{{ asset('images/spa/spa-about.jpg') }}" alt="" style="height:180px;object-fit:cover;width:100%;"></div>
                    @endif
                </div>
            </div>
        </div>

        @if($isImersiva)
        {{-- Divisões temáticas detail --}}
        <h3 class="heading-font-family text-9 fw-600 text-center mb-5 wow fadeInUp">Cada divisão, uma <span class="text-primary">história</span></h3>
        <div class="row g-4 wow fadeInUp">
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-light-1 h-100">
                    <div class="text-5 mb-2">🌸</div>
                    <h5 class="heading-font-family fw-600 mb-2">Cozinha & Sala — Jardim</h5>
                    <p class="text-3 text-body-secondary mb-0">Louça com motivos florais, puxadores dos armários em formato de insetos. Um espaço alegre onde se ouvem passarinhos ao entrar.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-light-1 h-100">
                    <div class="text-5 mb-2">⭐</div>
                    <h5 class="heading-font-family fw-600 mb-2">Quarto — Teto Estrelado</h5>
                    <p class="text-3 text-body-secondary mb-0">Teto estrelado com projeção Bluetooth personalizável por app. Constelações e atmosferas à escolha.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-light-1 h-100">
                    <div class="text-5 mb-2">🌈</div>
                    <h5 class="heading-font-family fw-600 mb-2">Quarto — Arco-Íris</h5>
                    <p class="text-3 text-body-secondary mb-0">Cabeceira da cama em arco-íris. Quadro interativo atrás de uma cortina que distribui doces.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-light-1 h-100">
                    <div class="text-5 mb-2">🐚</div>
                    <h5 class="heading-font-family fw-600 mb-2">Casa de Banho — Fundo do Mar</h5>
                    <p class="text-3 text-body-secondary mb-0">Decoração aquarela inspirada no fundo do mar. Lavatório iridescente em formato de concha.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-light-1 h-100">
                    <div class="text-5 mb-2">🌿</div>
                    <h5 class="heading-font-family fw-600 mb-2">Jardim, Piscina & Churrasqueira</h5>
                    <p class="text-3 text-body-secondary mb-0">Jardim amplo, piscina e churrasqueira para refeições inesquecíveis ao ar livre.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 rounded-4 border border-primary border-opacity-25 h-100 d-flex flex-column justify-content-center text-center">
                    <div class="text-primary text-8 mb-3"><i class="fa-solid fa-calendar-check"></i></div>
                    <h5 class="heading-font-family fw-600 mb-3">Disponível?</h5>
                    <a class="btn btn-new btn-primary rounded-pill" href="{{ route('reservas') }}">
                        <span class="btn-text"><span>Verificar & Reservar</span></span>
                        <span class="btn-icon"><i class="fa-solid fa-arrow-right"></i></span>
                    </a>
                </div>
            </div>
        </div>
        @else
        {{-- Spa CTA --}}
        <div class="text-center py-5 bg-light-1 rounded-5 wow fadeInUp">
            <h3 class="heading-font-family text-9 fw-600 mb-3">Reserva a tua <span class="text-primary">fuga de bem-estar</span></h3>
            <p class="text-body-secondary mb-4">Verifica disponibilidade e faz o teu pedido de reserva. Confirmação direta, sem pagamento online.</p>
            <a class="btn btn-new btn-primary rounded-pill" href="{{ route('reservas') }}">
                <span class="btn-text"><span>Verificar Disponibilidade</span></span>
                <span class="btn-icon"><i class="fa-solid fa-arrow-right"></i></span>
            </a>
        </div>
        @endif
    </div>
</section>

@endsection
