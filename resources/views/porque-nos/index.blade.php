@extends('layouts.site')

@section('title', __('porque_nos.page_title'))
@section('meta_description', __('porque_nos.seo_desc'))

@section('content')

{{-- Page Header --}}
<section class="page-header page-header-text-light py-0 mb-0">
    <div class="hero-wrap" style="height:280px;">
        <div class="hero-mask opacity-8 bg-black"></div>
        <div class="hero-bg" style="background-image:url('{{ asset('images/slider/slide-2.jpg') }}');"></div>
        <div class="hero-content d-flex align-items-end pb-5 h-100">
            <div class="container">
                <h1 class="heading-font-family text-white fw-700 mb-1">{{ __('porque_nos.heading') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb text-3">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="link-primary">{{ __('nav.home') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('porque_nos.breadcrumb') }}</li>
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
                <p><span class="text-3 text-uppercase fw-600 rounded-pill border border-dark border-opacity-10 px-3 py-1">{{ __('porque_nos.badge') }}</span></p>
                <h2 class="heading-font-family text-13 fw-600 lh-sm mb-4">{!! __('porque_nos.title') !!}</h2>
                <p class="text-body-secondary">{{ __('porque_nos.p1') }}</p>
                <p class="text-body-secondary">{{ __('porque_nos.p2') }}</p>
            </div>
            <div class="col-lg-7 wow fadeInRight">
                <div class="row g-4">
                    <div class="col-sm-6">
                        <div class="p-4 rounded-4 border h-100">
                            <div class="text-primary text-7 mb-3"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                            <h4 class="heading-font-family text-5 fw-600 mb-2">{{ __('porque_nos.feat_themes') }}</h4>
                            <p class="text-3 text-body-secondary mb-0">{{ __('porque_nos.feat_themes_d') }}</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-4 rounded-4 border h-100">
                            <div class="text-primary text-7 mb-3"><i class="fa-solid fa-location-dot"></i></div>
                            <h4 class="heading-font-family text-5 fw-600 mb-2">{{ __('porque_nos.feat_location') }}</h4>
                            <p class="text-3 text-body-secondary mb-0">{{ __('porque_nos.feat_location_d') }}</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-4 rounded-4 border h-100">
                            <div class="text-primary text-7 mb-3"><i class="fa-solid fa-heart"></i></div>
                            <h4 class="heading-font-family text-5 fw-600 mb-2">{{ __('porque_nos.feat_moments') }}</h4>
                            <p class="text-3 text-body-secondary mb-0">{{ __('porque_nos.feat_moments_d') }}</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-4 rounded-4 border h-100">
                            <div class="text-primary text-7 mb-3"><i class="fa-solid fa-face-smile-beam"></i></div>
                            <h4 class="heading-font-family text-5 fw-600 mb-2">{{ __('porque_nos.feat_ages') }}</h4>
                            <p class="text-3 text-body-secondary mb-0">{{ __('porque_nos.feat_ages_d') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Números --}}
        <div class="row g-4 text-center wow fadeInUp mt-3">
            <div class="col-6 col-md-3">
                <h3 class="heading-font-family text-15 fw-700 text-primary mb-1">2</h3>
                <p class="text-4 fw-500 mb-0">{{ __('porque_nos.stat_houses') }}</p>
            </div>
            <div class="col-6 col-md-3">
                <h3 class="heading-font-family text-15 fw-700 text-primary mb-1">6+</h3>
                <p class="text-4 fw-500 mb-0">{{ __('porque_nos.stat_themes') }}</p>
            </div>
            <div class="col-6 col-md-3">
                <h3 class="heading-font-family text-15 fw-700 text-primary mb-1">10'</h3>
                <p class="text-4 fw-500 mb-0">{{ __('porque_nos.stat_distance') }}</p>
            </div>
            <div class="col-6 col-md-3">
                <h3 class="heading-font-family text-15 fw-700 text-primary mb-1">★ 5.0</h3>
                <p class="text-4 fw-500 mb-0">{{ __('porque_nos.stat_rating') }}</p>
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
            <h2 class="heading-font-family text-11 fw-700 text-white wow fadeInUp mb-4">{!! __('porque_nos.cta_title') !!}</h2>
            <a class="btn btn-new btn-primary rounded-pill wow fadeInUp" data-wow-delay=".2s" href="{{ route('reservas') }}">
                <span class="btn-text"><span>{{ __('porque_nos.btn_reserve') }}</span></span>
                <span class="btn-icon"><i class="fa-solid fa-arrow-right"></i></span>
            </a>
        </div>
    </div>
</section>

@endsection
