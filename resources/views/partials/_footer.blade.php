<footer id="footer" class="footer-dark">
    <div class="hero-wrap section pb-0">
        <div class="hero-mask opacity-9 bg-black"></div>
        <div class="hero-bg hero-bg-scroll" style="background-image:url('{{ asset('images/footer-bg.jpg') }}');"></div>
        <div class="hero-content">
            <div class="container">
                <div class="row gy-4">
                    <div class="col-sm-7 col-md-6 col-lg-4">
                        <div class="mb-4">
                            <a href="{{ route('home') }}" class="fh-logo" style="font-size:28px;">Funtastic <span>House</span></a>
                        </div>
                        <p class="text-3">{{ __('footer.tagline') }}</p>
                        <ul class="social-icons social-icons-light mt-4">
                            @if($settings->get('instagram_url'))
                                <li><a href="{{ $settings->get('instagram_url') }}" target="_blank" title="Instagram"><i class="fa-brands fa-instagram"></i></a></li>
                            @endif
                            @if($settings->get('facebook_url'))
                                <li><a href="{{ $settings->get('facebook_url') }}" target="_blank" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a></li>
                            @endif
                            @if($settings->get('whatsapp'))
                                <li><a href="https://wa.me/{{ $settings->get('whatsapp') }}" target="_blank" title="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a></li>
                            @endif
                        </ul>
                    </div>

                    <div class="col-sm-4 col-md-4 col-lg-2 ms-auto">
                        <h5 class="heading-font-family fw-600 mb-3">{{ __('footer.nav_title') }}</h5>
                        <ul class="nav flex-column fw-500 text-3">
                            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">{{ __('nav.home') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('porque-nos') }}">{{ __('nav.porque_nos') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('galeria') }}">{{ __('nav.galeria') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('o-que-fazer') }}">{{ __('nav.o_que_fazer') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('reservas') }}">{{ __('nav.reservas') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('contactos') }}">{{ __('nav.contactos') }}</a></li>
                        </ul>
                    </div>

                    <div class="col-sm-4 col-md-3 col-lg-2">
                        <h5 class="heading-font-family fw-600 mb-3">{{ __('footer.experiences_title') }}</h5>
                        <ul class="nav flex-column fw-500 text-3">
                            <li class="nav-item"><a class="nav-link" href="{{ route('experiencia.show', 'imersiva') }}">{{ __('nav.exp_imersiva') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('experiencia.show', 'spa') }}">{{ __('nav.exp_spa') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">{{ __('footer.privacy') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">{{ __('footer.terms') }}</a></li>
                        </ul>
                    </div>

                    <div class="col-12 col-lg-3">
                        <h5 class="heading-font-family fw-600 mb-3">{{ __('footer.contact_title') }}</h5>
                        @if($settings->get('phone'))
                            <a href="tel:{{ preg_replace('/\s+/', '', $settings->get('phone')) }}" class="d-flex align-items-center gap-3 mb-3 link-primary link-underline-opacity-0 link-underline-opacity-100-hover">
                                <span class="text-5"><i class="fa-solid fa-phone-volume"></i></span>
                                <div class="text-4 fw-700">{{ $settings->get('phone') }}</div>
                            </a>
                        @endif
                        @if($settings->get('email'))
                            <a href="mailto:{{ $settings->get('email') }}" class="d-flex align-items-center gap-3 mb-3 link-light link-underline-opacity-0 link-underline-opacity-100-hover">
                                <span class="text-5"><i class="fa-solid fa-envelope"></i></span>
                                <div class="text-3">{{ $settings->get('email') }}</div>
                            </a>
                        @endif
                        @if($settings->get('whatsapp'))
                            <a href="https://wa.me/{{ $settings->get('whatsapp') }}" target="_blank" class="d-flex align-items-center gap-3 link-light link-underline-opacity-0 link-underline-opacity-100-hover">
                                <span class="text-5"><i class="fa-brands fa-whatsapp"></i></span>
                                <div class="text-3">{{ __('footer.whatsapp_direct') }}</div>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="footer-copyright">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <p class="text-3 text-center mb-1">© {{ date('Y') }} <a class="fw-500 link-primary link-underline-opacity-0 link-underline-opacity-100-hover" href="{{ route('home') }}">Funtastic House</a>. {{ __('footer.rights') }}</p>
                            <p class="text-2 text-center mb-0 opacity-4">Website desenvolvido por <a class="link-primary link-underline-opacity-0" href="https://workmind.pt" target="_blank">Workmind</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
