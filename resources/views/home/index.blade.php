@extends('layouts.site')

@section('title', __('home.page_title'))
@section('meta_description', __('home.seo_desc'))

@section('content')

{{-- Hero --}}
<section class="hero-wrap">
    <div class="hero-mask bg-dark opacity-6"></div>
    <div class="hero-bg" style="background-image:url('{{ asset('images/slider/slide-1.jpg') }}');"></div>
    <div class="hero-content section pb-0 d-flex flex-column min-vh-100">
        <div class="container my-auto py-5 text-center">
            <p class="text-3 text-light text-uppercase fw-600 ls-2 wow fadeInUp">
                <span class="rounded-pill border border-white border-opacity-50 px-3 py-1">Sintra · Ericeira · Mafra</span>
            </p>
            <h1 class="heading-font-family text-19 fw-700 text-white wow fadeInUp" data-wow-delay=".2s">
                {!! __('home.hero_title') !!}
            </h1>
            <p class="text-5 text-light text-opacity-75 mx-auto mt-3 mb-5 wow fadeInUp" data-wow-delay=".3s" style="max-width:560px;">
                {{ __('home.hero_subtitle') }}
            </p>
            <div class="d-flex justify-content-center gap-3 wow fadeInUp" data-wow-delay=".4s">
                <a class="btn btn-new btn-primary rounded-pill" href="{{ route('experiencia.show', 'imersiva') }}">
                    <span class="btn-text"><span>{{ __('home.btn_experiences') }}</span></span>
                    <span class="btn-icon"><i class="fa-solid fa-arrow-right"></i></span>
                </a>
                <a class="btn btn-new btn-outline-light rounded-pill" href="{{ route('porque-nos') }}">
                    <span class="btn-text"><span>{{ __('home.btn_learn_more') }}</span></span>
                    <span class="btn-icon"><i class="fa-solid fa-arrow-right"></i></span>
                </a>
            </div>
            {{-- Quick booking strip --}}
            <div class="intro-booking-form bg-black bg-opacity-75 rounded-pill p-4 p-lg-3 mt-5 wow fadeInUp" data-wow-delay=".5s">
                <div class="row gy-3 gx-lg-0 input-group">
                    <div class="col-md-6 col-lg">
                        <select id="heroExperience" class="form-select rounded-pill h-100">
                            <option value="">{{ __('home.f_experience') }}</option>
                            @foreach($experiences as $exp)
                                <option value="{{ $exp->slug }}">{{ app()->getLocale() === 'pt' ? $exp->name_pt : $exp->name_en }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-lg">
                        <div class="position-relative">
                            <input id="heroCheckIn" type="text" class="form-control rounded-pill" placeholder="{{ __('home.f_checkin') }}" autocomplete="off" readonly>
                            <span class="icon-inside"><i class="fa-regular fa-calendar-alt"></i></span>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg">
                        <div class="position-relative">
                            <input id="heroCheckOut" type="text" class="form-control rounded-pill" placeholder="{{ __('home.f_checkout') }}" autocomplete="off" readonly>
                            <span class="icon-inside"><i class="fa-regular fa-calendar-alt"></i></span>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg">
                        <select id="heroAdults" class="form-select rounded-pill h-100">
                            <option value="">{{ __('home.f_adults') }}</option>
                            <option value="1">{{ __('home.f_adults_1') }}</option>
                            <option value="2" selected>{{ __('home.f_adults_2') }}</option>
                            <option value="3">{{ __('home.f_adults_3') }}</option>
                            <option value="4">{{ __('home.f_adults_4') }}</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-lg col-xl-auto d-grid">
                        <button id="heroCheck" type="button" class="btn btn-primary text-nowrap rounded-pill">{{ __('home.f_check') }}</button>
                    </div>
                </div>
            </div>

            {{-- Availability loader --}}
            <div id="fh-avail-loader" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.92);align-items:center;justify-content:center;flex-direction:column;gap:1.5rem;">
                <div class="spinner-border text-primary" style="width:3.5rem;height:3.5rem;" role="status"></div>
                <p class="text-white fw-600 mb-0" style="font-size:1.15rem;">
                    {{ app()->getLocale() === 'pt' ? 'Verificando disponibilidade…' : 'Checking availability…' }}
                </p>
            </div>

            {{-- Unavailable modal --}}
            <div class="modal fade" id="fhUnavailModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-4 border-0">
                        <div class="modal-body text-center p-5">
                            <div class="mb-3" style="font-size:2.5rem;color:#c99f5b;"><i class="fa-solid fa-calendar-xmark"></i></div>
                            <h5 class="heading-font-family fw-700 mb-2">
                                {{ app()->getLocale() === 'pt' ? 'Datas não disponíveis' : 'Dates unavailable' }}
                            </h5>
                            <p id="fh-unavail-msg" class="text-body-secondary mb-4"></p>
                            <div class="d-flex gap-3 justify-content-center flex-wrap">
                                <button class="btn btn-outline-dark rounded-pill px-4" data-bs-dismiss="modal">
                                    {{ app()->getLocale() === 'pt' ? 'Fechar' : 'Close' }}
                                </button>
                                <a id="fh-unavail-suggest" href="#" class="btn btn-primary rounded-pill px-4" style="display:none;">
                                    {{ app()->getLocale() === 'pt' ? 'Ver sugestão de datas' : 'View suggested dates' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- Hero end --}}

{{-- Sobre nós --}}
<section class="section">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 wow fadeInLeft">
                <p><span class="text-3 text-uppercase fw-600 rounded-pill border border-dark border-opacity-10 px-3 py-1">{{ __('home.about_badge') }}</span></p>
                <h2 class="heading-font-family text-13 fw-600 lh-sm mb-4">{!! __('home.about_title') !!}</h2>
                <p class="text-5 text-body-secondary">{{ __('home.about_p1') }}</p>
                <p class="text-body-secondary mb-4">{{ __('home.about_p2') }}</p>
                <div class="d-inline-flex align-items-center gap-4">
                    <a class="btn btn-new btn-primary rounded-pill" href="{{ route('porque-nos') }}">
                        <span class="btn-text"><span>{{ __('home.btn_learn_more') }}</span></span>
                        <span class="btn-icon"><i class="fa-solid fa-arrow-right"></i></span>
                    </a>
                    @if($settings->get('phone'))
                    <div class="d-flex align-items-center gap-3 ms-sm-2">
                        <div class="text-body-tertiary text-7 opacity-7 d-inline-flex"><i class="fa-solid fa-phone-volume"></i></div>
                        <div class="vr my-1 opacity-1"></div>
                        <div class="text-start">
                            <div class="text-2 fw-600 text-body-tertiary mb-1">{{ __('home.about_contact') }}</div>
                            <h3 class="text-4 fw-700 mb-0">{{ $settings->get('phone') }}</h3>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-6 text-center wow fadeInRight">
                <div class="position-relative d-inline-flex">
                    <img class="img-fluid rounded-5" src="{{ asset('images/about.jpg') }}" alt="Funtastic House">
                    <div class="position-absolute top-0 end-0">
                        <div class="circle-text bg-white border border-2 border-primary mt-5 me-5 wow bounceIn" data-wow-delay="0.5s">
                            <svg viewBox="0 0 500 500"><defs><path id="circlePath" d="M50,250c0-110.5,89.5-200,200-200s200,89.5,200,200s-89.5,200-200,200S50,360.5,50,250"></path></defs><text class="text-uppercase fw-700 ls-4"><textPath xlink:href="#circlePath">{{ __('home.about_circle') }}</textPath></text></svg>
                            <div class="circle-icon text-bg-primary translate-middle"><i class="fa-solid fa-star"></i></div>
                        </div>
                    </div>
                    <div class="position-absolute bottom-0 start-50 translate-middle-x w-100">
                        <div class="text-light text-start bg-dark bg-opacity-50 rounded-5 m-5 p-4 wow fadeInUp">{{ __('home.about_tagline') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Experiências --}}
<section class="section bg-light-1">
    <div class="container">
        <div class="mx-auto text-center mb-5">
            <p class="wow fadeInUp"><span class="text-3 text-uppercase fw-600 rounded-pill border border-dark border-opacity-10 px-3 py-1">{{ __('home.exp_badge') }}</span></p>
            <h2 class="heading-font-family text-13 fw-600 lh-sm wow fadeInUp" data-wow-delay=".2s">{!! __('home.exp_title') !!}</h2>
        </div>
        <div class="room-items-wrap wow fadeInUp">
            <div class="row justify-content-center g-4">
                @foreach($experiences as $exp)
                <div class="col-md-6">
                    <div class="room-item rounded-5">
                        <a class="stretched-link" href="{{ route('experiencia.show', $exp->slug) }}"></a>
                        <div class="room-item-img rounded-5">
                            <img class="img-fluid d-block" src="{{ asset('images/rooms/room-' . ($loop->index + 1) . '.jpg') }}" alt="{{ app()->getLocale() === 'pt' ? $exp->name_pt : $exp->name_en }}">
                        </div>
                        <div class="room-discount position-absolute top-0 start-0 text-2 fw-500 text-light rounded-pill border border-light border-opacity-50">
                            @if($exp->slug === 'imersiva')
                            {{ __('home.badge_main') }}
                            @else
                            {{ __('home.badge_spa') }}
                            @endif
                        </div>
                        <div class="room-details d-flex align-items-center justify-content-between w-100 bottom-0 start-0">
                            <div>
                                <p class="text-3 fw-500 text-uppercase text-light mb-0">{{ __('home.exp_from') }} {{ number_format($exp->base_price, 0, ',', '.') }}€ {{ __('home.exp_night') }}</p>
                                <h3 class="text-white text-7 fw-600 mb-0">{{ app()->getLocale() === 'pt' ? $exp->name_pt : $exp->name_en }}</h3>
                            </div>
                            <span class="details-link-icon"><i class="fa-solid fa-arrow-right"></i></span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="position-relative text-center z-1 mt-5 wow fadeInUp">
            <a class="btn btn-new btn-primary rounded-pill" href="{{ route('reservas') }}">
                <span class="btn-text"><span>{{ __('home.btn_availability') }}</span></span>
                <span class="btn-icon"><i class="fa-solid fa-arrow-right"></i></span>
            </a>
        </div>
    </div>
</section>

{{-- Divisões temáticas --}}
<section class="section">
    <div class="container">
        <div class="mx-auto text-center mb-5">
            <p class="wow fadeInUp"><span class="text-3 text-uppercase fw-600 rounded-pill border border-dark border-opacity-10 px-3 py-1">{{ __('home.rooms_badge') }}</span></p>
            <h2 class="heading-font-family text-13 fw-600 lh-sm wow fadeInUp" data-wow-delay=".2s">{!! __('home.rooms_title') !!}</h2>
        </div>
        <div class="row g-4 text-center wow fadeInUp">
            <div class="col-6 col-md-4 col-lg-2">
                <div class="p-3 rounded-4 border border-opacity-10 h-100">
                    <div class="text-primary text-8 mb-2">🌸</div>
                    <p class="text-4 fw-600 mb-1">{{ __('home.room_kitchen') }}</p>
                    <p class="text-3 text-body-secondary mb-0">{{ __('home.room_kitchen_desc') }}</p>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="p-3 rounded-4 border border-opacity-10 h-100">
                    <div class="text-primary text-8 mb-2">⭐</div>
                    <p class="text-4 fw-600 mb-1">{{ __('home.room_stars') }}</p>
                    <p class="text-3 text-body-secondary mb-0">{{ __('home.room_stars_desc') }}</p>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="p-3 rounded-4 border border-opacity-10 h-100">
                    <div class="text-primary text-8 mb-2">🌈</div>
                    <p class="text-4 fw-600 mb-1">{{ __('home.room_rainbow') }}</p>
                    <p class="text-3 text-body-secondary mb-0">{{ __('home.room_rainbow_desc') }}</p>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="p-3 rounded-4 border border-opacity-10 h-100">
                    <div class="text-primary text-8 mb-2">🐚</div>
                    <p class="text-4 fw-600 mb-1">{{ __('home.room_bathroom') }}</p>
                    <p class="text-3 text-body-secondary mb-0">{{ __('home.room_bathroom_desc') }}</p>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="p-3 rounded-4 border border-opacity-10 h-100">
                    <div class="text-primary text-8 mb-2">🌿</div>
                    <p class="text-4 fw-600 mb-1">{{ __('home.room_garden') }}</p>
                    <p class="text-3 text-body-secondary mb-0">{{ __('home.room_garden_desc') }}</p>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="p-3 rounded-4 border border-opacity-10 h-100">
                    <div class="text-primary text-8 mb-2">🫧</div>
                    <p class="text-4 fw-600 mb-1">{{ __('home.room_jacuzzi') }}</p>
                    <p class="text-3 text-body-secondary mb-0">{{ __('home.room_jacuzzi_desc') }}</p>
                </div>
            </div>
        </div>
        <div class="text-center mt-5 wow fadeInUp">
            <a class="btn btn-new btn-primary rounded-pill" href="{{ route('experiencia.show', 'imersiva') }}">
                <span class="btn-text"><span>{{ __('home.btn_immersive') }}</span></span>
                <span class="btn-icon"><i class="fa-solid fa-arrow-right"></i></span>
            </a>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="hero-wrap section">
    <div class="hero-mask opacity-7 bg-dark"></div>
    <div class="hero-bg jarallax" style="background-image:url('{{ asset('images/experience-bg.jpg') }}');"></div>
    <div class="hero-content">
        <div class="container text-center">
            <h2 class="heading-font-family text-13 fw-700 text-white wow fadeInUp mb-4">{!! __('home.cta_title') !!}</h2>
            <a class="btn btn-new btn-primary rounded-pill btn-lg wow fadeInUp" data-wow-delay=".2s" href="{{ route('reservas') }}">
                <span class="btn-text"><span>{{ __('home.btn_reserve') }}</span></span>
                <span class="btn-icon"><i class="fa-solid fa-arrow-right"></i></span>
            </a>
        </div>
    </div>
</section>

{{-- Testemunhos --}}
<section class="section">
    <div class="container">
        <div class="mx-auto text-center mb-5">
            <p class="wow fadeInUp"><span class="text-3 text-uppercase fw-600 rounded-pill border border-dark border-opacity-10 px-3 py-1">{{ __('home.test_badge') }}</span></p>
            <h2 class="heading-font-family text-13 fw-600 lh-sm wow fadeInUp" data-wow-delay=".2s">{!! __('home.test_title') !!}</h2>
        </div>
        <div class="swiper testimonialSwiper wow fadeInUp">
            <div class="swiper-wrapper">
                @forelse($testimonials as $t)
                <div class="swiper-slide">
                    <div class="testimonial-item text-center p-4">
                        <div class="text-primary mb-3">
                            @for($i = 0; $i < $t->rating; $i++)<i class="fa-solid fa-star"></i>@endfor
                        </div>
                        <p class="text-5 fst-italic mb-4">"{{ app()->getLocale() === 'pt' ? $t->content_pt : $t->content_en }}"</p>
                        <h5 class="heading-font-family text-5 fw-600 mb-0">{{ $t->author_name }}</h5>
                        <p class="text-3 text-body-secondary">{{ $t->author_location }}</p>
                    </div>
                </div>
                @empty
                <div class="swiper-slide">
                    <div class="testimonial-item text-center p-4">
                        <p class="text-body-secondary">{{ __('home.test_empty') }}</p>
                    </div>
                </div>
                @endforelse
            </div>
            <div class="swiper-pagination mt-4"></div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
(function () {
    var heroExp    = document.getElementById('heroExperience');
    var heroIn     = document.getElementById('heroCheckIn');
    var heroOut    = document.getElementById('heroCheckOut');
    var heroAdults = document.getElementById('heroAdults');
    var heroBtn    = document.getElementById('heroCheck');
    var loader     = document.getElementById('fh-avail-loader');
    var locale     = '{{ app()->getLocale() }}';
    var blockedMap = {};

    var drpLocale = {
        pt: { format: 'DD/MM/YYYY', applyLabel: 'OK', cancelLabel: 'Cancelar',
              daysOfWeek: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
              monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
              firstDay: 1 },
        en: { format: 'DD/MM/YYYY', applyLabel: 'OK', cancelLabel: 'Cancel',
              daysOfWeek: ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'],
              monthNames: ['January','February','March','April','May','June','July','August','September','October','November','December'],
              firstDay: 1 }
    };

    function initPickers(blocked) {
        var blk = blocked || [];
        var inv = function (d) {
            return d.isBefore(moment(), 'day') || blk.indexOf(d.format('YYYY-MM-DD')) !== -1;
        };
        $(heroIn).daterangepicker({
            singleDatePicker: true, autoApply: true,
            minDate: moment().add(1, 'day'),
            locale: drpLocale[locale] || drpLocale.pt,
            isInvalidDate: inv
        });
        $(heroOut).daterangepicker({
            singleDatePicker: true, autoApply: true,
            minDate: moment().add(2, 'days'),
            locale: drpLocale[locale] || drpLocale.pt,
            isInvalidDate: inv
        });
    }

    initPickers([]);

    $(heroExp).on('change', function () {
        var slug = this.value;
        if (!slug) return;
        if (blockedMap[slug]) { initPickers(blockedMap[slug]); return; }
        $.get('/api/availability/' + slug, function (d) {
            blockedMap[slug] = d.blocked_dates || [];
            initPickers(blockedMap[slug]);
        });
    });

    $(heroBtn).on('click', function () {
        var slug    = heroExp.value;
        var checkin = heroIn.value;
        var checkout= heroOut.value;
        var adults  = heroAdults.value;
        var valid   = true;

        [heroExp, heroIn, heroOut, heroAdults].forEach(function (el) { el.classList.remove('is-invalid'); });

        if (!slug)    { heroExp.classList.add('is-invalid');    valid = false; }
        if (!checkin) { heroIn.classList.add('is-invalid');     valid = false; }
        if (!checkout){ heroOut.classList.add('is-invalid');    valid = false; }
        if (!adults)  { heroAdults.classList.add('is-invalid'); valid = false; }
        if (!valid) return;

        var ci = moment(checkin,  'DD/MM/YYYY');
        var co = moment(checkout, 'DD/MM/YYYY');
        if (!co.isAfter(ci)) { heroOut.classList.add('is-invalid'); return; }

        loader.style.display = 'flex';

        $.get('/api/availability/' + slug, function (data) {
            var blk = data.blocked_dates || [];
            var cur = ci.clone();
            var ok  = true;
            while (cur.isBefore(co)) {
                if (blk.indexOf(cur.format('YYYY-MM-DD')) !== -1) { ok = false; break; }
                cur.add(1, 'day');
            }
            if (ok) {
                window.location = '/reservas?experience=' + encodeURIComponent(slug)
                    + '&checkin='  + encodeURIComponent(checkin)
                    + '&checkout=' + encodeURIComponent(checkout)
                    + '&adults='   + encodeURIComponent(adults);
            } else {
                loader.style.display = 'none';
                var nights = co.diff(ci, 'days');
                var sug    = findWindow(blk, nights);
                showModal(sug, nights, slug, adults);
            }
        }).fail(function () { loader.style.display = 'none'; });
    });

    function findWindow(blk, nights) {
        var d = moment().add(1, 'day');
        for (var i = 0; i < 120; i++) {
            var win = true;
            for (var j = 0; j < nights; j++) {
                if (blk.indexOf(d.clone().add(j, 'days').format('YYYY-MM-DD')) !== -1) { win = false; break; }
            }
            if (win) return d.format('DD/MM/YYYY');
            d.add(1, 'day');
        }
        return null;
    }

    function showModal(suggestion, nights, slug, adults) {
        var msgEl  = document.getElementById('fh-unavail-msg');
        var sugBtn = document.getElementById('fh-unavail-suggest');
        msgEl.textContent = locale === 'pt'
            ? 'As datas selecionadas não estão disponíveis para esta experiência.'
            : 'The selected dates are not available for this experience.';
        if (suggestion) {
            var co2 = moment(suggestion, 'DD/MM/YYYY').add(nights, 'days').format('DD/MM/YYYY');
            sugBtn.href = '/reservas?experience=' + encodeURIComponent(slug)
                + '&checkin='  + encodeURIComponent(suggestion)
                + '&checkout=' + encodeURIComponent(co2)
                + '&adults='   + encodeURIComponent(adults);
            sugBtn.style.display = '';
        } else {
            sugBtn.style.display = 'none';
        }
        new bootstrap.Modal(document.getElementById('fhUnavailModal')).show();
    }
}());
</script>
@endpush
