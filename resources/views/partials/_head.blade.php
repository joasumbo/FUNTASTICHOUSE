<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
<link href="{{ asset('images/favicon.png') }}" rel="icon">

<title>@yield('title', $settings->get('meta_title_pt', 'Funtastic House'))</title>
<meta name="description" content="@yield('meta_description', $settings->get('meta_desc_pt', 'Alojamento temático único perto de Sintra, Ericeira e Mafra.'))">

{{-- Open Graph --}}
<meta property="og:type" content="website">
<meta property="og:title" content="@yield('title', $settings->get('meta_title_pt', 'Funtastic House'))">
<meta property="og:description" content="@yield('meta_description', $settings->get('meta_desc_pt', ''))">
<meta property="og:image" content="{{ asset('images/slider/slide-1.jpg') }}">
<meta property="og:url" content="{{ url()->current() }}">

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
