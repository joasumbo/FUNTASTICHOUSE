@extends('layouts.site')

@section('title', 'Pedido Enviado — Funtastic House')

@section('content')

<section class="page-header page-header-text-light py-0 mb-0">
    <div class="hero-wrap" style="height:280px;">
        <div class="hero-mask opacity-8 bg-black"></div>
        <div class="hero-bg" style="background-image:url('{{ asset('images/slider/slide-2.jpg') }}');"></div>
        <div class="hero-content d-flex align-items-end pb-5 h-100">
            <div class="container">
                <h1 class="heading-font-family text-white fw-700 mb-1">Pedido Enviado</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb text-3">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="link-primary">Início</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reservas') }}" class="link-primary">Reservas</a></li>
                        <li class="breadcrumb-item active">Confirmação</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center wow fadeInUp">
                <div class="text-primary mb-4" style="font-size:4rem;"><i class="fa-solid fa-circle-check"></i></div>
                <h2 class="heading-font-family text-11 fw-700 mb-3">Pedido recebido!</h2>

                @if(session('booking_name'))
                <p class="text-5 text-body-secondary mb-2">
                    Obrigado, <strong>{{ session('booking_name') }}</strong>. Enviámos uma confirmação para o teu email.
                </p>
                <div class="d-inline-block bg-light-1 rounded-4 px-4 py-3 mb-4 text-start">
                    <p class="text-3 mb-1"><i class="fa-solid fa-star text-primary me-2"></i><strong>Experiência:</strong> {{ session('booking_exp') }}</p>
                    <p class="text-3 mb-1"><i class="fa-regular fa-calendar text-primary me-2"></i><strong>Check-in:</strong> {{ session('booking_in') }}</p>
                    <p class="text-3 mb-0"><i class="fa-regular fa-calendar-check text-primary me-2"></i><strong>Check-out:</strong> {{ session('booking_out') }}</p>
                </div>
                @else
                <p class="text-5 text-body-secondary mb-4">Enviámos uma confirmação para o teu email.</p>
                @endif

                <p class="text-body-secondary mb-5">Vamos analisar o teu pedido e entrar em contacto em breve para confirmar disponibilidade. <strong>Sem pagamento online.</strong></p>

                <div class="d-flex justify-content-center gap-3">
                    <a class="btn btn-new btn-primary rounded-pill" href="{{ route('home') }}">
                        <span class="btn-text"><span>Voltar ao Início</span></span>
                        <span class="btn-icon"><i class="fa-solid fa-arrow-right"></i></span>
                    </a>
                    <a class="btn btn-new btn-outline-dark rounded-pill" href="{{ route('o-que-fazer') }}">
                        <span class="btn-text"><span>O Que Fazer</span></span>
                        <span class="btn-icon"><i class="fa-solid fa-map-location-dot"></i></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
