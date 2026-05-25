<header id="header" class="sticky-top-slide">
    <nav class="primary-menu navbar navbar-expand-lg bg-transparent border-bottom-0 text-3 fw-600 mt-3">
        <div class="container">
            <a class="logo fh-logo" href="{{ route('home') }}">Funtastic <span>House</span></a>

            <div id="header-nav" class="collapse navbar-collapse justify-content-center">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            {{ __('nav.home') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('porque-nos') ? 'active' : '' }}" href="{{ route('porque-nos') }}">
                            {{ __('nav.porque_nos') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('galeria') ? 'active' : '' }}" href="{{ route('galeria') }}">
                            {{ __('nav.galeria') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('o-que-fazer') ? 'active' : '' }}" href="{{ route('o-que-fazer') }}">
                            {{ __('nav.o_que_fazer') }}
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('experiencia.*') ? 'active' : '' }}" href="#">
                            {{ __('nav.experiencias') }}
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('experiencia.show', 'imersiva') }}">
                                    {{ __('nav.exp_imersiva') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('experiencia.show', 'spa') }}">
                                    {{ __('nav.exp_spa') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reservas') ? 'active' : '' }}" href="{{ route('reservas') }}">
                            {{ __('nav.reservas') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contactos') ? 'active' : '' }}" href="{{ route('contactos') }}">
                            {{ __('nav.contactos') }}
                        </a>
                    </li>
                </ul>
                <div class="lang-switcher d-flex d-lg-none justify-content-center py-2 border-top mt-2">
                    <a href="{{ route('locale.switch', 'pt') }}" class="{{ app()->getLocale() === 'pt' ? 'active' : '' }}">PT</a>
                    <span>|</span>
                    <a href="{{ route('locale.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 ms-auto">
                <div class="lang-switcher d-none d-lg-flex align-items-center">
                    <a href="{{ route('locale.switch', 'pt') }}" class="{{ app()->getLocale() === 'pt' ? 'active' : '' }}">PT</a>
                    <span>|</span>
                    <a href="{{ route('locale.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                </div>
                <a href="{{ route('reservas') }}" class="btn btn-new btn-primary text-capitalize rounded-pill text-nowrap">
                    <span class="btn-text"><span>{{ __('nav.reservar') }}</span></span>
                    <span class="btn-icon"><i class="fa-solid fa-arrow-right"></i></span>
                </a>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#header-nav">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>
</header>
