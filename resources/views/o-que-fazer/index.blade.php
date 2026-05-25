@extends('layouts.site')

@section('title', __('o_que_fazer.page_title'))
@section('meta_description', __('o_que_fazer.seo_desc'))

@section('content')

{{-- Page Header --}}
<section class="page-header page-header-text-light py-0 mb-0">
    <div class="hero-wrap" style="height:280px;">
        <div class="hero-mask opacity-8 bg-black"></div>
        <div class="hero-bg" style="background-image:url('{{ asset('images/slider/slide-3.jpg') }}');"></div>
        <div class="hero-content d-flex align-items-end pb-5 h-100">
            <div class="container">
                <h1 class="heading-font-family text-white fw-700 mb-1">{{ __('o_que_fazer.heading') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb text-3">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="link-primary">{{ __('nav.home') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('o_que_fazer.breadcrumb') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="mx-auto text-center mb-5">
            <p class="wow fadeInUp"><span class="text-3 text-uppercase fw-600 rounded-pill border border-dark border-opacity-10 px-3 py-1">{{ __('o_que_fazer.badge') }}</span></p>
            <h2 class="heading-font-family text-13 fw-600 lh-sm wow fadeInUp" data-wow-delay=".2s">{!! __('o_que_fazer.title') !!}</h2>
            <p class="text-body-secondary mx-auto" style="max-width:560px;">{{ __('o_que_fazer.subtitle') }}</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-7 wow fadeInLeft">
                <div class="rounded-5 overflow-hidden border" style="height:480px;">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d96728!2d-9.3894!3d38.7879!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1spt!2spt!4v1700000000000!5m2!1spt!2spt"
                        width="100%" height="480" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
                <p class="text-3 text-body-secondary mt-2 fst-italic"><i class="fa-solid fa-circle-info text-primary me-1"></i> {{ __('o_que_fazer.map_note') }}</p>
            </div>
            <div class="col-lg-5 wow fadeInRight">
                @forelse($categories as $cat)
                <div class="poi-card mb-3">
                    <h5 class="heading-font-family text-5 fw-600 mb-1">
                        @if($cat->icon)<i class="{{ $cat->icon }} text-primary me-2"></i>@endif
                        {{ app()->getLocale() === 'pt' ? $cat->name_pt : $cat->name_en }}
                    </h5>
                    @if($cat->pois->isNotEmpty())
                    <p class="text-3 text-body-secondary mb-0">
                        {{ $cat->pois->map(fn($p) => app()->getLocale() === 'pt' ? $p->name_pt : $p->name_en)->join(' · ') }}
                    </p>
                    @else
                    <p class="text-3 text-body-secondary mb-0 fst-italic">{{ __('o_que_fazer.coming_soon') }}</p>
                    @endif
                </div>
                @empty
                <p class="text-body-secondary fst-italic">{{ __('o_que_fazer.pois_soon') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</section>

@endsection
