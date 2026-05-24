@extends('layouts.site')

@section('title', 'Início — Funtastic House')

@section('content')
<section class="hero-wrap">
    <div class="hero-mask bg-dark opacity-6"></div>
    <div class="hero-bg" style="background-image:url('{{ asset('images/slider/slide-1.jpg') }}');"></div>
    <div class="hero-content section pb-0 d-flex flex-column min-vh-100">
        <div class="container my-auto py-5 text-center">
            <p class="text-3 text-light text-uppercase fw-600 ls-2 wow fadeInUp">
                <span class="rounded-pill border border-white border-opacity-50 px-3 py-1">Sintra · Ericeira · Mafra</span>
            </p>
            <h1 class="heading-font-family text-19 fw-700 text-white wow fadeInUp" data-wow-delay=".2s">
                Uma casa.<br><span class="text-primary">Um mundo de surpresas.</span>
            </h1>
            <p class="text-5 text-light text-opacity-75 mx-auto mt-3 mb-5 wow fadeInUp" data-wow-delay=".3s" style="max-width:560px;">
                Cada divisão é uma experiência diferente — do teto estrelado ao fundo do mar. Vem descobrir o que te espera.
            </p>
            <div class="d-flex justify-content-center gap-3 wow fadeInUp" data-wow-delay=".4s">
                <a class="btn btn-new btn-primary rounded-pill" href="{{ route('experiencia.show', 'imersiva') }}">
                    <span class="btn-text"><span>Ver Experiências</span></span>
                    <span class="btn-icon"><i class="fa-solid fa-arrow-right"></i></span>
                </a>
                <a class="btn btn-new btn-outline-light rounded-pill" href="{{ route('porque-nos') }}">
                    <span class="btn-text"><span>Saber Mais</span></span>
                    <span class="btn-icon"><i class="fa-solid fa-arrow-right"></i></span>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
