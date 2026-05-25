@php
$locale    = app()->getLocale();
$metaTitle = $settings->get('meta_title_' . $locale, 'Funtastic House');
$metaDesc  = $settings->get('meta_desc_' . $locale, $locale === 'pt'
    ? 'Alojamento temático único perto de Sintra, Ericeira e Mafra.'
    : 'Unique themed accommodation near Sintra, Ericeira and Mafra.');
$ogImage   = $settings->get('og_image')
    ? asset('storage/' . $settings->get('og_image'))
    : asset('images/slider/slide-1.jpg');
@endphp
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
<link href="{{ asset('images/favicon.png') }}" rel="icon">

<title>@yield('title', $metaTitle)</title>
<meta name="description" content="@yield('meta_description', $metaDesc)">
<link rel="canonical" href="{{ url()->current() }}">

{{-- Open Graph --}}
<meta property="og:type" content="website">
<meta property="og:locale" content="{{ $locale === 'pt' ? 'pt_PT' : 'en_GB' }}">
<meta property="og:site_name" content="Funtastic House">
<meta property="og:title" content="@yield('title', $metaTitle)">
<meta property="og:description" content="@yield('meta_description', $metaDesc)">
<meta property="og:image" content="@yield('og_image', $ogImage)">
<meta property="og:url" content="{{ url()->current() }}">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('title', $metaTitle)">
<meta name="twitter:description" content="@yield('meta_description', $metaDesc)">
<meta name="twitter:image" content="@yield('og_image', $ogImage)">

<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/font-awesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/swiper-bundle.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/glightbox.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/daterangepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/stylesheet.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&display=swap" rel="stylesheet">
