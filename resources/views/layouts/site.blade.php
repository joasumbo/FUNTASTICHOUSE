<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials._head')
    @stack('styles')
</head>
<body>
    <div class="preloader"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>

    <div id="main-wrapper">
        @include('partials._navbar')

        <div id="content" role="main">
            @yield('content')
        </div>

        @include('partials._footer')
    </div>

    <a id="back-to-top" href="javascript:void(0)"><i class="fa-solid fa-arrow-up"></i></a>

    {{-- Mobile sticky bottom bar --}}
    <div class="fh-mobile-bar d-lg-none" role="navigation" aria-label="Acções rápidas">
        @if($settings->get('whatsapp'))
        <a href="https://wa.me/{{ preg_replace('/\D/', '', $settings->get('whatsapp')) }}"
           target="_blank" rel="noopener" class="fh-wa-btn" aria-label="WhatsApp">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
        @endif
        <a href="{{ route('reservas') }}" class="btn btn-primary fw-700 rounded-pill">
            {{ __('nav.reservar') }}
            <i class="fa-solid fa-arrow-right ms-2"></i>
        </a>
    </div>

    @include('partials._scripts')
    @stack('scripts')
    @include('partials._cookie_consent')
    <script>
    // Close mobile nav when a nav link is clicked
    document.querySelectorAll('#header-nav .nav-link, #header-nav .dropdown-item').forEach(function(el) {
        el.addEventListener('click', function() {
            var collapse = document.getElementById('header-nav');
            if (collapse && collapse.classList.contains('show')) {
                var bsCollapse = bootstrap.Collapse.getInstance(collapse);
                if (bsCollapse) bsCollapse.hide();
            }
        });
    });
    </script>
</body>
</html>
