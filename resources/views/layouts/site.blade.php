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

    @include('partials._scripts')
    @stack('scripts')
</body>
</html>
