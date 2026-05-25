@extends('layouts.site')

@section('title', __('contactos.page_title'))

@section('content')

{{-- Page Header --}}
<section class="page-header page-header-text-light py-0 mb-0">
    <div class="hero-wrap" style="height:280px;">
        <div class="hero-mask opacity-8 bg-black"></div>
        <div class="hero-bg" style="background-image:url('{{ asset('images/contact-us.jpg') }}');"></div>
        <div class="hero-content d-flex align-items-end pb-5 h-100">
            <div class="container">
                <h1 class="heading-font-family text-white fw-700 mb-1">{{ __('contactos.heading') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb text-3">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="link-primary">{{ __('nav.home') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('contactos.breadcrumb') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5 wow fadeInLeft">
                <p><span class="text-3 text-uppercase fw-600 rounded-pill border border-dark border-opacity-10 px-3 py-1">{{ __('contactos.badge') }}</span></p>
                <h2 class="heading-font-family text-11 fw-600 lh-sm mb-4">{!! __('contactos.title') !!}</h2>

                @if($settings->get('phone'))
                <div class="d-flex gap-4 mb-4">
                    <span class="text-7 text-primary"><i class="fa-solid fa-phone-volume"></i></span>
                    <div>
                        <p class="text-3 text-body-secondary mb-1">{{ __('contactos.phone_label') }}</p>
                        <a href="tel:{{ $settings->get('phone') }}" class="text-5 fw-700 link-dark link-underline-opacity-0 link-underline-opacity-100-hover">{{ $settings->get('phone') }}</a>
                    </div>
                </div>
                @endif

                @if($settings->get('email'))
                <div class="d-flex gap-4 mb-4">
                    <span class="text-7 text-primary"><i class="fa-solid fa-envelope"></i></span>
                    <div>
                        <p class="text-3 text-body-secondary mb-1">{{ __('contactos.email_label') }}</p>
                        <a href="mailto:{{ $settings->get('email') }}" class="text-5 fw-700 link-dark link-underline-opacity-0 link-underline-opacity-100-hover">{{ $settings->get('email') }}</a>
                    </div>
                </div>
                @endif

                @if($settings->get('address'))
                <div class="d-flex gap-4 mb-4">
                    <span class="text-7 text-primary"><i class="fa-solid fa-location-dot"></i></span>
                    <div>
                        <p class="text-3 text-body-secondary mb-1">{{ __('contactos.location_label') }}</p>
                        <p class="text-5 fw-700 mb-0">{{ $settings->get('address') }}</p>
                    </div>
                </div>
                @else
                <div class="d-flex gap-4 mb-4">
                    <span class="text-7 text-primary"><i class="fa-solid fa-location-dot"></i></span>
                    <div>
                        <p class="text-3 text-body-secondary mb-1">{{ __('contactos.location_label') }}</p>
                        <p class="text-5 fw-700 mb-0">Sintra / Ericeira / Mafra<br><span class="text-3 text-body-secondary fw-400 fst-italic">{{ __('contactos.location_fallback') }}</span></p>
                    </div>
                </div>
                @endif

                @if($settings->get('instagram_url'))
                <div class="d-flex gap-4 mb-4">
                    <span class="text-7 text-primary"><i class="fa-brands fa-instagram"></i></span>
                    <div>
                        <p class="text-3 text-body-secondary mb-1">{{ __('contactos.instagram_label') }}</p>
                        <a href="{{ $settings->get('instagram_url') }}" target="_blank" class="text-5 fw-700 link-dark link-underline-opacity-0 link-underline-opacity-100-hover">@funtastichouse</a>
                    </div>
                </div>
                @endif

                <div class="mt-5">
                    <h4 class="heading-font-family text-7 fw-600 mb-3">{{ __('contactos.form_title') }}</h4>
                    <div class="row g-3">
                        <div class="col-12"><input type="text" class="form-control rounded-pill" placeholder="{{ __('contactos.ph_name') }}"></div>
                        <div class="col-12"><input type="email" class="form-control rounded-pill" placeholder="{{ __('contactos.ph_email') }}"></div>
                        <div class="col-12"><textarea class="form-control rounded-4" rows="4" placeholder="{{ __('contactos.ph_message') }}"></textarea></div>
                        <div class="col-12">
                            <button class="btn btn-new btn-primary rounded-pill w-100">
                                <span class="btn-text"><span>{{ __('contactos.btn_send') }}</span></span>
                                <span class="btn-icon"><i class="fa-solid fa-arrow-right"></i></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 wow fadeInRight">
                <div class="rounded-5 overflow-hidden" style="height:600px;">
                    <iframe src="{{ $settings->get('maps_embed_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d24182!2d-9.3894!3d38.7879!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd1ecb49f52cca3d%3A0x30a92d6a95916ef6!2sSintra!5e0!3m2!1spt!2spt!4v1700000000000!5m2!1spt!2spt') }}"
                        width="100%" height="600" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
