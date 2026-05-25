@extends('layouts.site')

@section('title', __('galeria.page_title'))

@section('content')

{{-- Page Header --}}
<section class="page-header page-header-text-light py-0 mb-0">
    <div class="hero-wrap" style="height:280px;">
        <div class="hero-mask opacity-8 bg-black"></div>
        <div class="hero-bg" style="background-image:url('{{ asset('images/rooms/room-1.jpg') }}');"></div>
        <div class="hero-content d-flex align-items-end pb-5 h-100">
            <div class="container">
                <h1 class="heading-font-family text-white fw-700 mb-1">{{ __('galeria.heading') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb text-3">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="link-primary">{{ __('nav.home') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('galeria.breadcrumb') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="mx-auto text-center mb-5">
            <p class="wow fadeInUp"><span class="text-3 text-uppercase fw-600 rounded-pill border border-dark border-opacity-10 px-3 py-1">{{ __('galeria.badge') }}</span></p>
            <h2 class="heading-font-family text-13 fw-600 lh-sm wow fadeInUp" data-wow-delay=".2s">{!! __('galeria.title') !!}</h2>
            <p class="text-body-secondary mx-auto" style="max-width:500px;">{{ __('galeria.subtitle') }}</p>
        </div>

        @if($images->isEmpty())
        {{-- Galeria estática --}}
        <div class="d-flex justify-content-center gap-2 mb-5 flex-wrap wow fadeInUp">
            <button class="btn btn-sm btn-primary rounded-pill px-4 gallery-filter active" data-filter="all">{{ __('galeria.filter_all') }}</button>
            <button class="btn btn-sm btn-outline-secondary rounded-pill px-4 gallery-filter" data-filter="imersiva">{{ __('galeria.filter_imersiva') }}</button>
            <button class="btn btn-sm btn-outline-secondary rounded-pill px-4 gallery-filter" data-filter="spa">{{ __('galeria.filter_spa') }}</button>
            <button class="btn btn-sm btn-outline-secondary rounded-pill px-4 gallery-filter" data-filter="exterior">{{ __('galeria.filter_exterior') }}</button>
        </div>
        <div class="row g-3 wow fadeInUp">
            <div class="col-md-6 col-lg-4 gallery-item" data-cat="imersiva">
                <a href="{{ asset('images/rooms/room-1.jpg') }}" class="glightbox d-block rounded-4 overflow-hidden" data-gallery="galeria">
                    <img class="img-fluid w-100" src="{{ asset('images/rooms/room-1.jpg') }}" alt="{{ __('galeria.alt_room1') }}" style="height:280px;object-fit:cover;">
                </a>
            </div>
            <div class="col-md-6 col-lg-4 gallery-item" data-cat="imersiva">
                <a href="{{ asset('images/rooms/room-2.jpg') }}" class="glightbox d-block rounded-4 overflow-hidden" data-gallery="galeria">
                    <img class="img-fluid w-100" src="{{ asset('images/rooms/room-2.jpg') }}" alt="{{ __('galeria.alt_room2') }}" style="height:280px;object-fit:cover;">
                </a>
            </div>
            <div class="col-md-6 col-lg-4 gallery-item" data-cat="imersiva">
                <a href="{{ asset('images/rooms/room-3.jpg') }}" class="glightbox d-block rounded-4 overflow-hidden" data-gallery="galeria">
                    <img class="img-fluid w-100" src="{{ asset('images/rooms/room-3.jpg') }}" alt="{{ __('galeria.alt_room3') }}" style="height:280px;object-fit:cover;">
                </a>
            </div>
            <div class="col-md-6 col-lg-4 gallery-item" data-cat="imersiva">
                <a href="{{ asset('images/rooms/room-4.jpg') }}" class="glightbox d-block rounded-4 overflow-hidden" data-gallery="galeria">
                    <img class="img-fluid w-100" src="{{ asset('images/rooms/room-4.jpg') }}" alt="{{ __('galeria.alt_room4') }}" style="height:280px;object-fit:cover;">
                </a>
            </div>
            <div class="col-md-6 col-lg-4 gallery-item" data-cat="spa">
                <a href="{{ asset('images/spa/spa.jpg') }}" class="glightbox d-block rounded-4 overflow-hidden" data-gallery="galeria">
                    <img class="img-fluid w-100" src="{{ asset('images/spa/spa.jpg') }}" alt="{{ __('galeria.alt_spa') }}" style="height:280px;object-fit:cover;">
                </a>
            </div>
            <div class="col-md-6 col-lg-4 gallery-item" data-cat="spa">
                <a href="{{ asset('images/spa/spa-about.jpg') }}" class="glightbox d-block rounded-4 overflow-hidden" data-gallery="galeria">
                    <img class="img-fluid w-100" src="{{ asset('images/spa/spa-about.jpg') }}" alt="{{ __('galeria.alt_jacuzzi') }}" style="height:280px;object-fit:cover;">
                </a>
            </div>
            <div class="col-md-6 col-lg-4 gallery-item" data-cat="exterior">
                <a href="{{ asset('images/rooms/room-5.jpg') }}" class="glightbox d-block rounded-4 overflow-hidden" data-gallery="galeria">
                    <img class="img-fluid w-100" src="{{ asset('images/rooms/room-5.jpg') }}" alt="{{ __('galeria.alt_garden') }}" style="height:280px;object-fit:cover;">
                </a>
            </div>
            <div class="col-md-6 col-lg-4 gallery-item" data-cat="exterior">
                <a href="{{ asset('images/experience.jpg') }}" class="glightbox d-block rounded-4 overflow-hidden" data-gallery="galeria">
                    <img class="img-fluid w-100" src="{{ asset('images/experience.jpg') }}" alt="{{ __('galeria.alt_exterior') }}" style="height:280px;object-fit:cover;">
                </a>
            </div>
            <div class="col-md-6 col-lg-4 gallery-item" data-cat="imersiva">
                <a href="{{ asset('images/about.jpg') }}" class="glightbox d-block rounded-4 overflow-hidden" data-gallery="galeria">
                    <img class="img-fluid w-100" src="{{ asset('images/about.jpg') }}" alt="{{ __('galeria.alt_lounge') }}" style="height:280px;object-fit:cover;">
                </a>
            </div>
        </div>
        @else
        {{-- Galeria dinâmica da BD --}}
        <div class="d-flex justify-content-center gap-2 mb-5 flex-wrap wow fadeInUp">
            <button class="btn btn-sm btn-primary rounded-pill px-4 gallery-filter active" data-filter="all">{{ __('galeria.filter_all') }}</button>
            @foreach($images->keys() as $cat)
            <button class="btn btn-sm btn-outline-secondary rounded-pill px-4 gallery-filter" data-filter="{{ $cat }}">{{ ucfirst($cat) }}</button>
            @endforeach
        </div>
        <div class="row g-3 wow fadeInUp">
            @foreach($images->flatten() as $img)
            <div class="col-md-6 col-lg-4 gallery-item" data-cat="{{ $img->category }}">
                <a href="{{ asset($img->filename) }}" class="glightbox d-block rounded-4 overflow-hidden" data-gallery="galeria">
                    <img class="img-fluid w-100" src="{{ asset($img->filename) }}"
                         alt="{{ app()->getLocale() === 'pt' ? $img->alt_pt : $img->alt_en }}"
                         style="height:280px;object-fit:cover;">
                </a>
            </div>
            @endforeach
        </div>
        @endif

        <p class="text-center text-3 text-body-secondary mt-4 fst-italic">
            <i class="fa-solid fa-circle-info text-primary me-1"></i>
            {{ __('galeria.note') }}
        </p>
    </div>
</section>

@endsection

@push('scripts')
<script>
$(document).ready(function(){
    $('.gallery-filter').on('click', function(){
        $('.gallery-filter').removeClass('active btn-primary').addClass('btn-outline-secondary');
        $(this).addClass('active btn-primary').removeClass('btn-outline-secondary');
        var f = $(this).data('filter');
        if(f==='all'){ $('.gallery-item').fadeIn(300); }
        else { $('.gallery-item').hide(); $('.gallery-item[data-cat="'+f+'"]').fadeIn(300); }
    });
});
</script>
@endpush
