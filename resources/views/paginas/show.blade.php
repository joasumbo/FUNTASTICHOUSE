@extends('layouts.site')

@section('title', (app()->getLocale() === 'pt' ? $page->title_pt : $page->title_en) . ' — Funtastic House')
@section('meta_description', app()->getLocale() === 'pt' ? $page->title_pt : $page->title_en)

@section('content')

<section class="page-header page-header-text-light py-0 mb-0">
    <div class="hero-wrap" style="height:200px;">
        <div class="hero-mask opacity-8 bg-black"></div>
        <div class="hero-bg" style="background-image:url('{{ asset('images/slider/slide-1.jpg') }}');"></div>
        <div class="hero-content d-flex align-items-end pb-5 h-100">
            <div class="container">
                <h1 class="heading-font-family text-white fw-700 mb-1">
                    {{ app()->getLocale() === 'pt' ? $page->title_pt : $page->title_en }}
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb text-3">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="link-primary">{{ __('nav.home') }}</a></li>
                        <li class="breadcrumb-item active">{{ app()->getLocale() === 'pt' ? $page->title_pt : $page->title_en }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="fh-page-content">
                    {!! nl2br(e(app()->getLocale() === 'pt' ? $page->content_pt : $page->content_en)) !!}
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
.fh-page-content {
    font-size: 1rem;
    line-height: 1.8;
    color: var(--bs-body-color);
}
.fh-page-content strong {
    font-weight: 700;
    color: #1a1a1a;
}
</style>
@endpush

@endsection
