@extends('layouts.site')

@section('title', 'Porquê Nós? — Funtastic House')

@section('content')

{{-- Page Header --}}
<section class="page-header page-header-text-light py-0 mb-0">
    <div class="hero-wrap" style="height:280px;">
        <div class="hero-mask opacity-8 bg-black"></div>
        <div class="hero-bg" style="background-image:url('{{ asset('images/slider/slide-2.jpg') }}');"></div>
        <div class="hero-content d-flex align-items-end pb-5 h-100">
            <div class="container">
                <h1 class="heading-font-family text-white fw-700 mb-1">Porquê Nós?</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb text-3">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="link-primary">Início</a></li>
                        <li class="breadcrumb-item active">Porquê Nós?</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row g-5 align-items-center mb-5">
            <div class="col-lg-5 wow fadeInLeft">
                <p><span class="text-3 text-uppercase fw-600 rounded-pill border border-dark border-opacity-10 px-3 py-1">Porquê Nós?</span></p>
                <h2 class="heading-font-family text-13 fw-600 lh-sm mb-4">Uma casa <span class="text-primary">diferente</span> de todas as outras</h2>
                <p class="text-body-secondary">Cada detalhe foi pensado para te surpreender. Desde os puxadores em forma de inseto até ao quarto que distribui doces — a Funtastic House é uma viagem em si mesma.</p>
                <p class="text-body-secondary">Perto de Sintra, Ericeira e Mafra, tens a natureza, os palácios e as praias de surf ao alcance da mão, com o conforto e a magia de uma casa verdadeiramente única.</p>
            </div>
            <div class="col-lg-7 wow fadeInRight">
                <div class="row g-4">
                    <div class="col-sm-6">
                        <div class="p-4 rounded-4 border h-100">
                            <div class="text-primary text-7 mb-3"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                            <h4 class="heading-font-family text-5 fw-600 mb-2">Temáticas Únicas</h4>
                            <p class="text-3 text-body-secondary mb-0">Cada divisão conta uma história diferente. Do jardim encantado da cozinha ao lavatório em concha iridescente.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-4 rounded-4 border h-100">
                            <div class="text-primary text-7 mb-3"><i class="fa-solid fa-location-dot"></i></div>
                            <h4 class="heading-font-family text-5 fw-600 mb-2">Localização Privilegiada</h4>
                            <p class="text-3 text-body-secondary mb-0">A minutos de Sintra, Ericeira e Mafra. Palácios UNESCO, praias de surf e natureza deslumbrante.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-4 rounded-4 border h-100">
                            <div class="text-primary text-7 mb-3"><i class="fa-solid fa-heart"></i></div>
                            <h4 class="heading-font-family text-5 fw-600 mb-2">Momentos Inesquecíveis</h4>
                            <p class="text-3 text-body-secondary mb-0">Detalhes pensados ao pormenor para surpreender a cada canto — para famílias, casais ou grupos de amigos.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-4 rounded-4 border h-100">
                            <div class="text-primary text-7 mb-3"><i class="fa-solid fa-face-smile-beam"></i></div>
                            <h4 class="heading-font-family text-5 fw-600 mb-2">Para Todas as Idades</h4>
                            <p class="text-3 text-body-secondary mb-0">Alegre, divertida e cheia de surpresas escondidas. Uma experiência que agrada a miúdos e graúdos.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Números --}}
        <div class="row g-4 text-center wow fadeInUp mt-3">
            <div class="col-6 col-md-3">
                <h3 class="heading-font-family text-15 fw-700 text-primary mb-1">2</h3>
                <p class="text-4 fw-500 mb-0">Casas Temáticas</p>
            </div>
            <div class="col-6 col-md-3">
                <h3 class="heading-font-family text-15 fw-700 text-primary mb-1">6+</h3>
                <p class="text-4 fw-500 mb-0">Temáticas Diferentes</p>
            </div>
            <div class="col-6 col-md-3">
                <h3 class="heading-font-family text-15 fw-700 text-primary mb-1">10'</h3>
                <p class="text-4 fw-500 mb-0">de Sintra</p>
            </div>
            <div class="col-6 col-md-3">
                <h3 class="heading-font-family text-15 fw-700 text-primary mb-1">★ 5.0</h3>
                <p class="text-4 fw-500 mb-0">Avaliação Média</p>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="hero-wrap section py-0" style="height:320px;">
    <div class="hero-mask opacity-7 bg-dark"></div>
    <div class="hero-bg jarallax" style="background-image:url('{{ asset('images/experience-bg.jpg') }}');"></div>
    <div class="hero-content d-flex align-items-center justify-content-center">
        <div class="text-center">
            <h2 class="heading-font-family text-11 fw-700 text-white wow fadeInUp mb-4">Pronto para uma experiência <span class="text-primary">inesquecível?</span></h2>
            <a class="btn btn-new btn-primary rounded-pill wow fadeInUp" data-wow-delay=".2s" href="{{ route('reservas') }}">
                <span class="btn-text"><span>Reservar Agora</span></span>
                <span class="btn-icon"><i class="fa-solid fa-arrow-right"></i></span>
            </a>
        </div>
    </div>
</section>

@endsection
