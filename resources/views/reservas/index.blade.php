@extends('layouts.site')

@section('title', __('reservas.page_title'))
@section('meta_description', __('reservas.seo_desc'))

@php
$fhI18n = [
    'months'      => __('reservas.js_months'),
    'months_sh'   => __('reservas.js_months_sh'),
    'adult_s'     => __('reservas.js_adult_s'),
    'adult_p'     => __('reservas.js_adult_p'),
    'child_s'     => __('reservas.js_child_s'),
    'child_p'     => __('reservas.js_child_p'),
    'night_s'     => __('reservas.js_night_s'),
    'night_p'     => __('reservas.js_night_p'),
    'free'        => __('reservas.js_free'),
    'weekend'     => __('reservas.js_weekend'),
    'loading'     => __('reservas.js_loading'),
    'hint_ci'     => __('reservas.js_hint_ci'),
    'hint_co'     => __('reservas.js_hint_co'),
    'hint_done_s' => __('reservas.js_hint_done_s'),
    'hint_done_p' => __('reservas.js_hint_done_p'),
    'sending'     => __('reservas.js_sending'),
    'err_generic' => __('reservas.js_err_generic'),
    'err_network' => __('reservas.js_err_network'),
    'select_co'   => __('reservas.js_select_co'),
    'select_dates'=> __('reservas.js_select_dates'),
];
$dow = app()->getLocale() === 'pt'
    ? ['Seg','Ter','Qua','Qui','Sex','Sáb','Dom']
    : ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
@endphp

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css">
<style>
/* ── Tom Select dark ────────────────────────────────────────────── */
.ts-wrapper .ts-control{background:rgba(255,255,255,.06)!important;border:1px solid rgba(255,255,255,.15)!important;border-radius:50rem!important;color:rgba(255,255,255,.85)!important;padding:.5rem 1.25rem!important;box-shadow:none!important;min-height:calc(1.5em + 1rem + 2px)!important;cursor:pointer!important;transition:border-color .2s,background .2s!important;}
.ts-wrapper.focus .ts-control{border-color:rgba(201,159,91,.6)!important;background:rgba(255,255,255,.09)!important;box-shadow:0 0 0 .2rem rgba(201,159,91,.15)!important;}
.ts-wrapper .ts-control input,.ts-wrapper .ts-control .item{color:rgba(255,255,255,.85)!important;background:transparent!important;}
.ts-wrapper .ts-dropdown{background:#1c1c1c!important;border:1px solid rgba(201,159,91,.25)!important;border-radius:1rem!important;box-shadow:0 12px 32px rgba(0,0,0,.5)!important;margin-top:.25rem!important;overflow:hidden!important;}
.ts-wrapper .ts-dropdown .option{color:rgba(255,255,255,.8)!important;padding:.6rem 1.25rem!important;transition:background .15s!important;}
.ts-wrapper .ts-dropdown .option:hover,.ts-wrapper .ts-dropdown .option.active{background:rgba(201,159,91,.18)!important;color:#fff!important;}
.ts-wrapper .ts-dropdown .option.selected{background:rgba(201,159,91,.3)!important;color:#fff!important;}
.ts-wrapper .ts-dropdown-content{max-height:220px!important;}

/* ── Calendar range ─────────────────────────────────────────────── */
.fh-cal-day{position:relative!important;overflow:visible!important;cursor:default;user-select:none;}
.fh-cal-day:not(.occ):not(.empty){cursor:pointer;}
.fh-day-inner{position:relative;z-index:2;display:flex;flex-direction:column;align-items:center;justify-content:center;width:100%;height:100%;pointer-events:none;transition:color .15s;}
.fh-cal-day.sel-in .fh-day-inner,.fh-cal-day.sel-out .fh-day-inner{color:#000!important;font-weight:700;}
.fh-cal-day.sel-in::before,.fh-cal-day.sel-out::before{content:'';position:absolute;top:50%;left:50%;width:2.4rem;height:2.4rem;transform:translate(-50%,-50%) scale(0);background:#c99f5b;border-radius:50%;z-index:1;animation:fhDotPop .35s cubic-bezier(.34,1.56,.64,1) forwards;}
.fh-cal-day.sel-in.has-range::after{content:'';position:absolute;top:calc(50% - .55rem);height:1.1rem;left:50%;right:-1px;background:rgba(201,159,91,.2);z-index:0;}
.fh-cal-day.sel-out::after{content:'';position:absolute;top:calc(50% - .55rem);height:1.1rem;left:-1px;right:50%;background:rgba(201,159,91,.2);z-index:0;}
.fh-cal-day.in-range{background:transparent!important;border-radius:0!important;}
.fh-cal-day.in-range::before{content:'';position:absolute;top:calc(50% - .55rem);height:1.1rem;left:-1px;right:-1px;background:rgba(201,159,91,.2);z-index:0;}
.fh-cal-day.hover-range{background:transparent!important;border-radius:0!important;}
.fh-cal-day.hover-range::before{content:'';position:absolute;top:calc(50% - .55rem);height:1.1rem;left:-1px;right:-1px;background:rgba(201,159,91,.07);z-index:0;}
.fh-cal-day.hover-end{background:transparent!important;}
.fh-cal-day.hover-end .fh-day-inner{color:#fff;}
.fh-cal-day.hover-end::before{content:'';position:absolute;top:50%;left:50%;width:2.4rem;height:2.4rem;transform:translate(-50%,-50%);background:rgba(201,159,91,.32);border-radius:50%;z-index:1;}
.fh-cal-day.hover-end::after{content:'';position:absolute;top:calc(50% - .55rem);height:1.1rem;left:-1px;right:50%;background:rgba(201,159,91,.07);z-index:0;}
.fh-cal-day.sel-in.awaiting-out::before{box-shadow:0 0 0 0 rgba(201,159,91,.5);animation:fhDotPop .35s cubic-bezier(.34,1.56,.64,1) forwards,fhRing 1.6s ease-out .4s infinite;}
@keyframes fhDotPop{0%{transform:translate(-50%,-50%) scale(.4);opacity:.3;}75%{transform:translate(-50%,-50%) scale(1.15);opacity:1;}100%{transform:translate(-50%,-50%) scale(1);opacity:1;}}
@keyframes fhRing{0%{box-shadow:0 0 0 0 rgba(201,159,91,.45);}70%{box-shadow:0 0 0 .6rem rgba(201,159,91,0);}100%{box-shadow:0 0 0 0 rgba(201,159,91,0);}}

/* ── Date summary bar ───────────────────────────────────────────── */
.fh-date-bar{display:flex;align-items:center;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:50rem;padding:.55rem 1.25rem;transition:border-color .25s,background .25s;min-height:2.85rem;}
.fh-date-bar.has-dates{border-color:rgba(201,159,91,.35);background:rgba(201,159,91,.06);}
.fh-date-bar-icon{color:rgba(201,159,91,.6);margin-right:.65rem;flex-shrink:0;font-size:.95rem;}
.fh-date-bar-text{color:rgba(255,255,255,.35);font-size:.9rem;flex:1;}
.fh-date-bar.has-dates .fh-date-bar-text{color:rgba(255,255,255,.85);}
.fh-date-bar-nights{font-size:.75rem;font-weight:600;background:rgba(201,159,91,.18);color:#c99f5b;border-radius:50rem;padding:.1rem .55rem;margin-left:.5rem;flex-shrink:0;}
.fh-date-bar-clear{background:none;border:none;color:rgba(255,255,255,.3);margin-left:.5rem;padding:0;line-height:1;font-size:.85rem;cursor:pointer;flex-shrink:0;}
.fh-date-bar-clear:hover{color:rgba(255,255,255,.7);}
.fh-cal-hint{font-size:.8rem;text-align:center;margin-top:.6rem;height:1.2rem;transition:opacity .2s;color:rgba(201,159,91,.6);}

/* ── Stepper ────────────────────────────────────────────────────── */
.fh-stepper{display:flex;align-items:center;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.15);border-radius:50rem;padding:.4rem .75rem;min-height:calc(1.5em + 1rem + 2px);gap:.5rem;}
.fh-stepper-btn{width:2rem;height:2rem;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);border-radius:50%;color:rgba(255,255,255,.75);font-size:1.15rem;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .15s,border-color .15s,color .15s;flex-shrink:0;padding:0;user-select:none;}
.fh-stepper-btn:hover:not(:disabled){background:rgba(201,159,91,.28);border-color:rgba(201,159,91,.5);color:#fff;}
.fh-stepper-btn:disabled{opacity:.25;cursor:default;}
.fh-stepper-val{flex:1;text-align:center;color:rgba(255,255,255,.9);font-weight:500;font-size:.92rem;white-space:nowrap;}

/* ── Price summary ──────────────────────────────────────────────── */
.fh-ps-box{background:rgba(201,159,91,.05);border:1px solid rgba(201,159,91,.22);border-radius:1rem;padding:1rem 1.25rem;animation:fhFadeIn .3s ease;}
.fh-ps-line{display:flex;justify-content:space-between;align-items:center;font-size:.84rem;color:rgba(255,255,255,.45);margin-bottom:.3rem;}
.fh-ps-line span:last-child{font-weight:500;color:rgba(255,255,255,.65);}
.fh-ps-divider{border-top:1px solid rgba(201,159,91,.2);margin:.75rem 0;}
.fh-ps-footer{display:flex;justify-content:space-between;align-items:center;}
.fh-ps-total-label{color:rgba(255,255,255,.85);font-weight:600;font-size:.9rem;}
.fh-ps-guests{color:rgba(255,255,255,.35);font-size:.78rem;margin-top:.15rem;}
.fh-ps-total-val{color:#c99f5b;font-size:1.9rem;font-weight:700;line-height:1;}
.fh-ps-note{font-size:.73rem;color:rgba(255,255,255,.22);margin-top:.65rem;text-align:center;}

/* ── Error Modal ────────────────────────────────────────────────── */
#fh-error-modal{position:fixed;inset:0;z-index:11000;background:rgba(0,0,0,.72);backdrop-filter:blur(6px);display:flex;align-items:center;justify-content:center;animation:fhFadeIn .2s ease both;}
#fh-error-modal.fh-modal-hide{animation:fhFadeOut .2s ease forwards;}
.fh-modal-box{background:#1c1c1c;border:1px solid rgba(220,53,69,.25);border-radius:1.5rem;padding:2.25rem 2rem;max-width:420px;width:90%;text-align:center;animation:fhScaleIn .3s cubic-bezier(.34,1.56,.64,1) both;box-shadow:0 24px 60px rgba(0,0,0,.6);}
#fh-error-modal.fh-modal-hide .fh-modal-box{animation:fhScaleOut .2s ease forwards;}
.fh-modal-icon{font-size:3rem;color:#dc3545;margin-bottom:1rem;line-height:1;}
.fh-modal-title{color:#fff;font-size:1.15rem;font-weight:700;margin-bottom:.2rem;}
.fh-modal-sub{color:rgba(255,255,255,.4);font-size:.85rem;margin-bottom:1.25rem;}
.fh-modal-list{text-align:left;list-style:none;padding:0;margin:0 0 1.5rem;}
.fh-modal-list li{display:flex;align-items:flex-start;gap:.6rem;padding:.45rem .75rem;border-radius:.6rem;margin-bottom:.3rem;background:rgba(220,53,69,.08);color:rgba(255,255,255,.8);font-size:.875rem;}
.fh-modal-list li i{color:#dc3545;flex-shrink:0;margin-top:.15rem;}
@keyframes fhFadeIn{from{opacity:0}to{opacity:1}}
@keyframes fhFadeOut{from{opacity:1}to{opacity:0}}
@keyframes fhScaleIn{from{transform:scale(.82);opacity:0}to{transform:scale(1);opacity:1}}
@keyframes fhScaleOut{from{transform:scale(1);opacity:1}to{transform:scale(.82);opacity:0}}

/* ── Success card ───────────────────────────────────────────────── */
#reserva-success-card{display:none;animation:fhScaleIn .4s cubic-bezier(.34,1.56,.64,1) both;}
</style>
@endpush

@section('content')

{{-- Error Modal --}}
<div id="fh-error-modal" style="display:none;" role="dialog" aria-modal="true">
    <div class="fh-modal-box">
        <div class="fh-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <p class="fh-modal-title">{{ __('reservas.modal_title') }}</p>
        <p class="fh-modal-sub">{{ __('reservas.modal_sub') }}</p>
        <ul id="fh-modal-list" class="fh-modal-list"></ul>
        <button class="btn btn-primary rounded-pill px-5 py-2" onclick="fhCloseModal()">{{ __('reservas.modal_ok') }}</button>
    </div>
</div>

{{-- Page Header --}}
<section class="page-header page-header-text-light py-0 mb-0">
    <div class="hero-wrap" style="height:280px;">
        <div class="hero-mask opacity-8 bg-black"></div>
        <div class="hero-bg" style="background-image:url('{{ asset('images/slider/slide-2.jpg') }}');"></div>
        <div class="hero-content d-flex align-items-end pb-5 h-100">
            <div class="container">
                <h1 class="heading-font-family text-white fw-700 mb-1">{{ __('reservas.heading') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb text-3">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="link-primary">{{ __('nav.home') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('reservas.breadcrumb') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="section bg-dark">
    <div class="container">
        <div class="mx-auto text-center mb-5">
            <p class="wow fadeInUp"><span class="text-3 text-uppercase fw-600 rounded-pill border border-white border-opacity-25 px-3 py-1 text-white-50">{{ __('reservas.badge') }}</span></p>
            <h2 class="heading-font-family text-13 fw-600 lh-sm text-white wow fadeInUp" data-wow-delay=".2s">{!! __('reservas.title') !!}</h2>
        </div>
        <div class="row g-4">

            {{-- Calendário --}}
            <div class="col-lg-6 wow fadeInLeft">
                <div class="fh-cal">
                    <div class="fh-cal-tabs">
                        @foreach($experiences as $exp)
                        <button class="fh-cal-tab {{ $loop->first ? 'active' : '' }}"
                                onclick="fhSwitchExp('{{ $exp->slug }}',this)">
                            {{ $exp->slug === 'imersiva' ? '🌟' : '🫧' }}
                            {{ app()->getLocale() === 'pt' ? $exp->name_pt : $exp->name_en }}
                        </button>
                        @endforeach
                    </div>
                    <div class="fh-cal-header">
                        <button class="fh-cal-nav" onclick="fhChangeMonth(-1)">&#8249;</button>
                        <span class="fh-cal-month" id="fh-cal-label">{{ __('reservas.js_loading') }}</span>
                        <button class="fh-cal-nav" onclick="fhChangeMonth(1)">&#8250;</button>
                    </div>
                    <div class="fh-cal-grid">
                        @foreach($dow as $d)
                        <div class="fh-cal-dow">{{ $d }}</div>
                        @endforeach
                    </div>
                    <div class="fh-cal-grid" id="fh-cal-grid"></div>
                    <p class="fh-cal-hint" id="fh-cal-hint">{{ __('reservas.js_hint_ci') }}</p>
                    <div class="fh-cal-legend mt-2">
                        <div class="fh-cal-legend-item"><div class="fh-dot" style="background:rgba(201,159,91,.3);border:1px solid var(--bs-themecolor);"></div>{{ __('reservas.legend_available') }}</div>
                        <div class="fh-cal-legend-item"><div class="fh-dot" style="background:rgba(255,255,255,.1);"></div>{{ __('reservas.legend_occupied') }}</div>
                        <div class="fh-cal-legend-item"><div class="fh-dot" style="background:rgba(201,159,91,.15);border:1px solid rgba(201,159,91,.4);"></div>{{ __('reservas.legend_weekend') }}</div>
                    </div>
                    <p class="text-3 mt-3 mb-0" style="color:rgba(255,255,255,.35);">
                        <i class="fa-solid fa-circle-info me-1" style="color:var(--bs-themecolor);"></i>
                        {{ __('reservas.prices_note') }}
                    </p>
                </div>
            </div>

            {{-- Formulário --}}
            <div class="col-lg-6 wow fadeInRight">
                <div class="p-4 rounded-4" style="background:rgba(255,255,255,.04);border:1px solid rgba(201,159,91,.15);">

                    <div id="reserva-form-wrap">
                        <h4 class="heading-font-family text-7 fw-600 text-white mb-1">{{ __('reservas.form_title') }}</h4>
                        <p class="text-3 mb-4" style="color:rgba(255,255,255,.4);">{{ __('reservas.form_sub') }}</p>

                        <form id="reserva-form" action="{{ route('reservas.store') }}" method="POST">
                        @csrf
                        <input type="hidden" id="fh-checkin-val"  name="check_in">
                        <input type="hidden" id="fh-checkout-val" name="check_out">

                        <div class="row g-3 form-dark">

                            {{-- Date bar --}}
                            <div class="col-12">
                                <label class="form-label">{{ __('reservas.lbl_dates') }}</label>
                                <div class="fh-date-bar" id="fh-date-bar">
                                    <i class="fh-date-bar-icon fa-regular fa-calendar"></i>
                                    <span id="fh-date-bar-text" class="fh-date-bar-text">{{ __('reservas.js_select_dates') }}</span>
                                    <span id="fh-date-bar-nights" class="fh-date-bar-nights" style="display:none;"></span>
                                    <button type="button" id="fh-date-bar-clear" onclick="fhClearDates()" style="display:none;" class="fh-date-bar-clear" title="{{ __('reservas.date_clear_title') }}"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                            </div>

                            {{-- Nome --}}
                            <div class="col-12">
                                <label class="form-label">{{ __('reservas.lbl_name') }}</label>
                                <input type="text" name="name" class="form-control rounded-pill" placeholder="{{ __('reservas.ph_name') }}">
                            </div>

                            {{-- Telefone / Email --}}
                            <div class="col-md-6">
                                <label class="form-label">{{ __('reservas.lbl_phone') }}</label>
                                <input type="tel" name="phone" class="form-control rounded-pill" placeholder="{{ __('reservas.ph_phone') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('reservas.lbl_email') }}</label>
                                <input type="email" name="email" class="form-control rounded-pill" placeholder="{{ __('reservas.ph_email') }}">
                            </div>

                            {{-- Adultos stepper --}}
                            <div class="col-md-6">
                                <label class="form-label">{{ __('reservas.lbl_adults') }}</label>
                                <input type="hidden" name="adults" id="adults-val" value="2">
                                <div class="fh-stepper">
                                    <button type="button" class="fh-stepper-btn" id="adults-minus" onclick="fhStep('adults',-1)" disabled>−</button>
                                    <span class="fh-stepper-val" id="adults-display">2 {{ __('reservas.js_adult_p') }}</span>
                                    <button type="button" class="fh-stepper-btn" id="adults-plus"  onclick="fhStep('adults',1)">+</button>
                                </div>
                            </div>

                            {{-- Crianças stepper --}}
                            <div class="col-md-6">
                                <label class="form-label">{{ __('reservas.lbl_children') }}</label>
                                <input type="hidden" name="children" id="children-val" value="0">
                                <div class="fh-stepper">
                                    <button type="button" class="fh-stepper-btn" id="children-minus" onclick="fhStep('children',-1)" disabled>−</button>
                                    <span class="fh-stepper-val" id="children-display">0 {{ __('reservas.js_child_p') }}</span>
                                    <button type="button" class="fh-stepper-btn" id="children-plus"  onclick="fhStep('children',1)">+</button>
                                </div>
                            </div>

                            {{-- Idades das crianças --}}
                            <div class="col-12" id="children-ages" style="display:none;">
                                <label class="form-label">{{ __('reservas.lbl_ages') }}</label>
                                <input type="text" name="children_ages" class="form-control rounded-pill" placeholder="{{ __('reservas.ph_ages') }}">
                            </div>

                            {{-- Experiência --}}
                            <div class="col-12">
                                <label class="form-label">{{ __('reservas.lbl_experience') }}</label>
                                <select name="experience_slug" id="sel-exp">
                                    <option value="">{{ __('reservas.select_opt') }}</option>
                                    @foreach($experiences as $exp)
                                    <option value="{{ $exp->slug }}">
                                        {{ app()->getLocale() === 'pt' ? $exp->name_pt : $exp->name_en }}
                                        {{ $exp->slug === 'imersiva' ? '🌟' : '🫧' }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Price summary --}}
                            <div class="col-12" id="fh-price-wrap" style="display:none;">
                                <div class="fh-ps-box">
                                    <div id="fh-ps-lines"></div>
                                    <div class="fh-ps-divider"></div>
                                    <div class="fh-ps-footer">
                                        <div>
                                            <div class="fh-ps-total-label">{{ __('reservas.ps_total') }}</div>
                                            <div class="fh-ps-guests" id="fh-ps-guests"></div>
                                        </div>
                                        <div class="fh-ps-total-val" id="fh-ps-total"></div>
                                    </div>
                                    <div class="fh-ps-note">{{ __('reservas.ps_note') }}</div>
                                </div>
                            </div>

                            {{-- Mensagem --}}
                            <div class="col-12">
                                <label class="form-label">{{ __('reservas.lbl_message') }}</label>
                                <textarea name="message" class="form-control rounded-4" rows="3" placeholder="{{ __('reservas.ph_message') }}"></textarea>
                            </div>

                            <div class="col-12">
                                <button id="reserva-submit" type="submit" class="btn btn-primary w-100 rounded-pill py-3 text-4 fw-600">
                                    <i class="fa-regular fa-paper-plane me-2"></i>{{ __('reservas.btn_submit') }}
                                </button>
                            </div>
                        </div>
                        </form>
                    </div>

                    {{-- Success card --}}
                    <div id="reserva-success-card" class="text-center py-3">
                        <div class="mb-3" style="font-size:3.5rem;color:var(--bs-themecolor);"><i class="fa-solid fa-circle-check"></i></div>
                        <h4 class="heading-font-family text-8 fw-700 text-white mb-2">{{ __('reservas.success_title') }}</h4>
                        <p class="text-4 mb-3" style="color:rgba(255,255,255,.6);">
                            {{ __('reservas.success_thanks') }} <strong id="sc-name" class="text-white"></strong>.<br>
                            <span style="font-size:.9em;">{{ __('reservas.success_email_note') }}</span>
                        </p>
                        <div class="d-inline-block rounded-3 px-4 py-3 mb-4 text-start w-100" style="background:rgba(255,255,255,.04);border:1px solid rgba(201,159,91,.2);">
                            <p class="text-3 mb-1" style="color:rgba(255,255,255,.5);"><i class="fa-solid fa-star me-2" style="color:var(--bs-themecolor);"></i>{{ __('reservas.success_experience') }} <span id="sc-exp" class="text-white"></span></p>
                            <p class="text-3 mb-1" style="color:rgba(255,255,255,.5);"><i class="fa-regular fa-calendar me-2" style="color:var(--bs-themecolor);"></i>{{ __('reservas.success_checkin') }} <span id="sc-in" class="text-white"></span></p>
                            <p class="text-3 mb-0" style="color:rgba(255,255,255,.5);"><i class="fa-regular fa-calendar-check me-2" style="color:var(--bs-themecolor);"></i>{{ __('reservas.success_checkout') }} <span id="sc-out" class="text-white"></span></p>
                        </div>
                        <p class="text-3 mb-4" style="color:rgba(255,255,255,.4);">{{ __('reservas.success_info') }}</p>
                        <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4 me-2">{{ __('reservas.btn_home') }}</a>
                        <a href="{{ route('o-que-fazer') }}" class="btn btn-outline-light rounded-pill px-4">{{ __('reservas.btn_todo') }}</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
/* ── i18n ───────────────────────────────────────────────────────── */
var fhI18n = @json($fhI18n);

/* ── State ──────────────────────────────────────────────────────── */
var calState  = { exp: '{{ $experiences->first()->slug ?? 'imersiva' }}', year: new Date().getFullYear(), month: new Date().getMonth() };
var fhCache   = {};
var apiBase   = '{{ url('/api/availability') }}';
var fhSel     = { in: null, out: null };
var months_pt = fhI18n.months.split(',');
var months_sh = fhI18n.months_sh.split(',');

var steppers = {
    adults:   { val: 2, min: 1, max: 6, sing: fhI18n.adult_s, plur: fhI18n.adult_p },
    children: { val: 0, min: 0, max: 6, sing: fhI18n.child_s, plur: fhI18n.child_p }
};

/* ── API ────────────────────────────────────────────────────────── */
function fetchAvailability(slug, cb) {
    if (fhCache[slug]) { cb(fhCache[slug]); return; }
    fetch(apiBase + '/' + slug)
        .then(function(r) { return r.json(); })
        .then(function(d) { fhCache[slug] = d; cb(d); })
        .catch(function()  { cb({ blocked_dates: [], prices: { base: 0, weekend: 0 } }); });
}

/* ── Helpers ────────────────────────────────────────────────────── */
function fmtDatePt(ds) { var p = ds.split('-'); return p[2]+'/'+p[1]+'/'+p[0]; }
function fmtDisp(ds)   { var p = ds.split('-'); return p[2]+' '+months_sh[parseInt(p[1])-1]; }
function nightsCount() {
    if (!fhSel.in || !fhSel.out) return 0;
    return Math.round((new Date(fhSel.out) - new Date(fhSel.in)) / 86400000);
}

/* ── Stepper ────────────────────────────────────────────────────── */
function fhStep(field, delta) {
    var s = steppers[field];
    s.val = Math.min(s.max, Math.max(s.min, s.val + delta));
    var lbl = s.val === 1 ? s.sing : s.plur;
    document.getElementById(field + '-display').textContent = s.val + ' ' + lbl;
    document.getElementById(field + '-val').value = s.val;
    document.getElementById(field + '-minus').disabled = s.val <= s.min;
    document.getElementById(field + '-plus').disabled  = s.val >= s.max;
    if (field === 'children') {
        document.getElementById('children-ages').style.display = s.val > 0 ? 'block' : 'none';
    }
    updatePriceSummary();
}

/* ── Price summary ──────────────────────────────────────────────── */
function updatePriceSummary() {
    var wrap = document.getElementById('fh-price-wrap');
    if (!fhSel.in || !fhSel.out || !fhCache[calState.exp]) {
        wrap.style.display = 'none';
        return;
    }
    var prices   = fhCache[calState.exp].prices;
    var adults   = steppers.adults.val;
    var children = steppers.children.val;
    var cur = new Date(fhSel.in), end = new Date(fhSel.out);
    var baseN = 0, wkndN = 0;
    while (cur < end) {
        var dow = cur.getDay();
        if (dow === 0 || dow === 6) wkndN++; else baseN++;
        cur.setDate(cur.getDate() + 1);
    }
    var baseTotal = baseN * prices.base * adults;
    var wkndTotal = wkndN * prices.weekend * adults;
    var total     = baseTotal + wkndTotal;

    var lines = '';
    if (baseN > 0) {
        lines += '<div class="fh-ps-line">'
            + '<span>' + baseN + ' ' + (baseN > 1 ? fhI18n.night_p : fhI18n.night_s) + ' × ' + prices.base + '€ × ' + adults + ' ' + (adults > 1 ? fhI18n.adult_p : fhI18n.adult_s) + '</span>'
            + '<span>' + baseTotal + '€</span>'
            + '</div>';
    }
    if (wkndN > 0) {
        lines += '<div class="fh-ps-line">'
            + '<span>' + wkndN + ' ' + (wkndN > 1 ? fhI18n.night_p : fhI18n.night_s) + ' ' + fhI18n.weekend + ' × ' + prices.weekend + '€ × ' + adults + ' ' + (adults > 1 ? fhI18n.adult_p : fhI18n.adult_s) + '</span>'
            + '<span>' + wkndTotal + '€</span>'
            + '</div>';
    }
    if (children > 0) {
        lines += '<div class="fh-ps-line"><span>' + children + ' ' + (children === 1 ? fhI18n.child_s : fhI18n.child_p) + '</span><span style="color:rgba(201,159,91,.5);">' + fhI18n.free + '</span></div>';
    }
    document.getElementById('fh-ps-lines').innerHTML = lines;
    document.getElementById('fh-ps-total').textContent = total + '€';
    var nights = baseN + wkndN;
    var gStr = nights + ' ' + (nights > 1 ? fhI18n.night_p : fhI18n.night_s) + ' · ' + adults + ' ' + (adults > 1 ? fhI18n.adult_p : fhI18n.adult_s);
    if (children > 0) gStr += ' · ' + children + ' ' + (children === 1 ? fhI18n.child_s : fhI18n.child_p);
    document.getElementById('fh-ps-guests').textContent = gStr;
    wrap.style.display = 'block';
}

/* ── Calendar render ────────────────────────────────────────────── */
function renderCal(data) {
    var y = calState.year, m = calState.month;
    var occ = (data && data.blocked_dates) ? data.blocked_dates : [];
    var p   = (data && data.prices) ? data.prices : { base: 0, weekend: 0 };
    document.getElementById('fh-cal-label').textContent = months_pt[m] + ' ' + y;
    var first = new Date(y, m, 1).getDay();
    first = first === 0 ? 6 : first - 1;
    var days = new Date(y, m + 1, 0).getDate();

    var now = new Date(); now.setHours(0,0,0,0);
    var todayDs = now.getFullYear() + '-' + String(now.getMonth()+1).padStart(2,'0') + '-' + String(now.getDate()).padStart(2,'0');

    var html = '';
    for (var i = 0; i < first; i++) html += '<div class="fh-cal-day empty"></div>';
    for (var d = 1; d <= days; d++) {
        var ds   = y + '-' + String(m+1).padStart(2,'0') + '-' + String(d).padStart(2,'0');
        var dow  = new Date(y, m, d).getDay();
        var wknd = (dow === 0 || dow === 6);
        var isPast = ds < todayDs;
        var isOcc  = isPast || occ.indexOf(ds) !== -1;
        var cls  = 'fh-cal-day';
        if (isOcc)  cls += ' occ';
        if (isPast) cls += ' past';
        if (wknd && !isOcc) cls += ' wknd';
        if (!isPast && fhSel.in  === ds) cls += ' sel-in' + (fhSel.out ? ' has-range' : ' awaiting-out');
        if (!isPast && fhSel.out === ds) cls += ' sel-out';
        if (!isPast && fhSel.in && fhSel.out && ds > fhSel.in && ds < fhSel.out) cls += ' in-range';
        var price     = wknd ? p.weekend : p.base;
        var priceHtml = isOcc ? '' : '<span class="dp">' + price + '€</span>';
        var click     = isOcc ? '' : ' onclick="fhPickDate(\'' + ds + '\')"';
        html += '<div class="' + cls + '" data-ds="' + ds + '"' + click + '><span class="fh-day-inner">' + d + priceHtml + '</span></div>';
    }
    document.getElementById('fh-cal-grid').innerHTML = html;
    updateHint();
    updatePriceSummary();
}

/* ── Hover preview ──────────────────────────────────────────────── */
function addHoverListeners() {
    var grid = document.getElementById('fh-cal-grid');
    grid.addEventListener('mouseover', function(e) {
        if (!fhSel.in || fhSel.out) return;
        var cell = e.target.closest('.fh-cal-day[data-ds]');
        if (!cell || cell.classList.contains('occ')) { clearHover(); return; }
        var over = cell.dataset.ds;
        if (over <= fhSel.in) { clearHover(); return; }
        document.querySelectorAll('#fh-cal-grid .fh-cal-day[data-ds]').forEach(function(c) {
            var cds = c.dataset.ds;
            c.classList.remove('hover-range', 'hover-end');
            if (cds > fhSel.in && cds < over) c.classList.add('hover-range');
            if (cds === over)                  c.classList.add('hover-end');
        });
    });
    grid.addEventListener('mouseleave', clearHover);
}

function clearHover() {
    document.querySelectorAll('#fh-cal-grid .hover-range, #fh-cal-grid .hover-end').forEach(function(c) {
        c.classList.remove('hover-range', 'hover-end');
    });
}

/* ── Date picker ────────────────────────────────────────────────── */
function fhPickDate(ds) {
    clearHover();
    if (!fhSel.in || (fhSel.in && fhSel.out)) {
        fhSel.in = ds; fhSel.out = null;
        document.getElementById('fh-checkin-val').value  = fmtDatePt(ds);
        document.getElementById('fh-checkout-val').value = '';
    } else {
        if (ds <= fhSel.in) {
            fhSel.in = ds; fhSel.out = null;
            document.getElementById('fh-checkin-val').value  = fmtDatePt(ds);
            document.getElementById('fh-checkout-val').value = '';
        } else {
            fhSel.out = ds;
            document.getElementById('fh-checkout-val').value = fmtDatePt(ds);
        }
    }
    if (fhCache[calState.exp]) renderCal(fhCache[calState.exp]);
    updateDateBar();
}

function fhClearDates() {
    fhSel.in = null; fhSel.out = null;
    document.getElementById('fh-checkin-val').value  = '';
    document.getElementById('fh-checkout-val').value = '';
    if (fhCache[calState.exp]) renderCal(fhCache[calState.exp]);
    updateDateBar();
}

/* ── Date bar ───────────────────────────────────────────────────── */
function updateDateBar() {
    var bar    = document.getElementById('fh-date-bar');
    var text   = document.getElementById('fh-date-bar-text');
    var nights = document.getElementById('fh-date-bar-nights');
    var clear  = document.getElementById('fh-date-bar-clear');
    if (fhSel.in && fhSel.out) {
        var n = nightsCount();
        text.innerHTML = '<strong style="color:#c99f5b;">' + fmtDisp(fhSel.in) + '</strong>'
            + '<span style="color:rgba(201,159,91,.5);margin:0 .4rem;">→</span>'
            + '<strong style="color:#c99f5b;">' + fmtDisp(fhSel.out) + '</strong>';
        nights.textContent = n + ' ' + (n === 1 ? fhI18n.night_s : fhI18n.night_p);
        nights.style.display = '';
        clear.style.display = '';
        bar.classList.add('has-dates');
    } else if (fhSel.in) {
        text.innerHTML = '<strong style="color:#c99f5b;">' + fmtDisp(fhSel.in) + '</strong>'
            + '<span style="color:rgba(255,255,255,.3);margin:0 .4rem;">→</span>'
            + '<span style="color:rgba(255,255,255,.3);">' + fhI18n.select_co + '</span>';
        nights.style.display = 'none';
        clear.style.display = '';
        bar.classList.add('has-dates');
    } else {
        text.textContent = fhI18n.select_dates;
        nights.style.display = 'none';
        clear.style.display = 'none';
        bar.classList.remove('has-dates');
    }
}

/* ── Hint ───────────────────────────────────────────────────────── */
function updateHint() {
    var h = document.getElementById('fh-cal-hint');
    if (!h) return;
    if (!fhSel.in)       h.textContent = fhI18n.hint_ci;
    else if (!fhSel.out) h.textContent = fhI18n.hint_co;
    else                 h.textContent = nightsCount() + ' ' + (nightsCount() === 1 ? fhI18n.hint_done_s : fhI18n.hint_done_p);
}

/* ── Calendar navigation ────────────────────────────────────────── */
function fhRenderWithFetch() {
    document.getElementById('fh-cal-label').textContent = fhI18n.loading;
    document.getElementById('fh-cal-grid').innerHTML = '';
    fetchAvailability(calState.exp, renderCal);
}

function fhChangeMonth(d) {
    calState.month += d;
    if (calState.month > 11) { calState.month = 0; calState.year++; }
    if (calState.month < 0)  { calState.month = 11; calState.year--; }
    if (fhCache[calState.exp]) renderCal(fhCache[calState.exp]);
    else fhRenderWithFetch();
}

function fhSwitchExp(exp, btn) {
    calState.exp = exp;
    document.querySelectorAll('.fh-cal-tab').forEach(function(t) { t.classList.remove('active'); });
    btn.classList.add('active');
    if (window.tsExp) window.tsExp.setValue(exp, true);
    fhRenderWithFetch();
}

/* ── Error modal ────────────────────────────────────────────────── */
function fhShowModal(errors) {
    var list = document.getElementById('fh-modal-list');
    list.innerHTML = '';
    errors.forEach(function(msg) {
        var li = document.createElement('li');
        li.innerHTML = '<i class="fa-solid fa-circle-dot"></i>' + msg;
        list.appendChild(li);
    });
    var modal = document.getElementById('fh-error-modal');
    modal.style.display = 'flex';
    modal.classList.remove('fh-modal-hide');
    document.body.style.overflow = 'hidden';
}
function fhCloseModal() {
    var modal = document.getElementById('fh-error-modal');
    modal.classList.add('fh-modal-hide');
    document.body.style.overflow = '';
    setTimeout(function() { modal.style.display = 'none'; modal.classList.remove('fh-modal-hide'); }, 200);
}
document.getElementById('fh-error-modal').addEventListener('click', function(e) {
    if (e.target === this) fhCloseModal();
});

/* ── Success card ───────────────────────────────────────────────── */
function fhShowSuccess(data) {
    document.getElementById('sc-name').textContent = data.name;
    document.getElementById('sc-exp').textContent  = data.experience;
    document.getElementById('sc-in').textContent   = data.check_in;
    document.getElementById('sc-out').textContent  = data.check_out;
    document.getElementById('reserva-form-wrap').style.display = 'none';
    var card = document.getElementById('reserva-success-card');
    card.style.display = 'block';
    card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

/* ── Init ───────────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function () {

    /* Tom Select — Experiência */
    window.tsExp = new TomSelect('#sel-exp', { create: false, allowEmptyOption: true, searchField: [] });
    window.tsExp.on('change', function(val) {
        if (!val) return;
        calState.exp = val;
        document.querySelectorAll('.fh-cal-tab').forEach(function(t) { t.classList.remove('active'); });
        document.querySelectorAll('.fh-cal-tab').forEach(function(t) {
            if ((t.getAttribute('onclick') || '').indexOf("'" + val + "'") !== -1) t.classList.add('active');
        });
        fhRenderWithFetch();
    });

    /* Hover delegation */
    addHoverListeners();

    /* AJAX form submit */
    var form    = document.getElementById('reserva-form');
    var btn     = document.getElementById('reserva-submit');
    var btnOrig = btn.innerHTML;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>' + fhI18n.sending;

        fetch(form.action, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: new FormData(form)
        })
        .then(function(r) {
            return r.json().then(function(d) { return { status: r.status, data: d }; });
        })
        .then(function(res) {
            if (res.status === 200 && res.data.success) {
                fhShowSuccess(res.data);
            } else if (res.status === 422 && res.data.errors) {
                var msgs = [];
                Object.keys(res.data.errors).forEach(function(k) {
                    res.data.errors[k].forEach(function(m) { msgs.push(m); });
                });
                fhShowModal(msgs);
                btn.disabled = false;
                btn.innerHTML = btnOrig;
            } else {
                fhShowModal([fhI18n.err_generic]);
                btn.disabled = false;
                btn.innerHTML = btnOrig;
            }
        })
        .catch(function() {
            fhShowModal([fhI18n.err_network]);
            btn.disabled = false;
            btn.innerHTML = btnOrig;
        });
    });

    fhRenderWithFetch();
});
</script>
@endpush
