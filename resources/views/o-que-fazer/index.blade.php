@extends('layouts.site')

@section('title', __('o_que_fazer.page_title'))
@section('meta_description', __('o_que_fazer.seo_desc'))

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.min.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
#fh-map { height: 480px; width: 100%; border-radius: 1.25rem; z-index: 0; }

.fh-map-filter { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; }
.fh-map-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 14px; border-radius: 99px; font-size: 12px; font-weight: 600;
    cursor: pointer; transition: all .2s; border: 2px solid transparent;
    background: #f3f4f6; color: #374151;
}
.fh-map-pill:hover { background: #e5e7eb; }
.fh-map-pill.active { color: #fff; border-color: transparent; }
.fh-map-pill i { font-size: 11px; }

.poi-card { cursor: pointer; transition: all .2s; }
.poi-card:hover { transform: translateX(6px); box-shadow: 0 4px 20px rgba(0,0,0,.1); }
.poi-card.highlighted { box-shadow: 0 0 0 2px var(--bs-themecolor); }

.leaflet-popup-content-wrapper { border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,.15); }
.leaflet-popup-content { margin: 12px 16px; }
.fh-popup-title { font-weight: 700; font-size: 14px; color: #111; margin-bottom: 4px; }
.fh-popup-desc { font-size: 12px; color: #6b7280; line-height: 1.5; margin-bottom: 6px; }
.fh-popup-dist { font-size: 11px; font-weight: 600; color: #c99f5b; }
</style>
@endpush

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

        {{-- Filtros por categoria --}}
        @if($categories->isNotEmpty())
        <div class="fh-map-filter wow fadeInUp mb-4">
            <button class="fh-map-pill active" data-cat="all" style="background:#374151;">
                <i class="fa-solid fa-map-location-dot"></i>
                {{ __('o_que_fazer.filter_all') }}
            </button>
            @foreach($categories->filter(fn($c) => $c->pois->isNotEmpty()) as $cat)
            @php
                $colors = [
                    'fa-crown'          => '#c99f5b',
                    'fa-umbrella-beach' => '#2C5F6E',
                    'fa-landmark'       => '#7c3aed',
                    'fa-person-biking'  => '#16a34a',
                    'fa-utensils'       => '#dc2626',
                    'fa-bag-shopping'   => '#d97706',
                ];
                $color = $colors[$cat->icon] ?? '#374151';
            @endphp
            <button class="fh-map-pill" data-cat="{{ $cat->id }}"
                    style="--cat-color:{{ $color }};"
                    data-color="{{ $color }}">
                @if($cat->icon)<i class="{{ $cat->icon }}"></i>@endif
                {{ app()->getLocale() === 'pt' ? $cat->name_pt : $cat->name_en }}
                <span class="badge rounded-pill ms-1" style="background:{{ $color }};font-size:10px;">{{ $cat->pois->count() }}</span>
            </button>
            @endforeach
        </div>
        @endif

        <div class="row g-4">

            {{-- Mapa --}}
            <div class="col-lg-7 wow fadeInLeft">
                <div class="rounded-5 overflow-hidden border" style="height:480px;">
                    <div id="fh-map"></div>
                </div>
                <p class="text-3 text-body-secondary mt-2 fst-italic">
                    <i class="fa-solid fa-circle-info text-primary me-1"></i>
                    {{ __('o_que_fazer.map_note') }}
                </p>
            </div>

            {{-- Lista de POIs --}}
            <div class="col-lg-5 wow fadeInRight" style="max-height:520px;overflow-y:auto;padding-right:4px;">
                @forelse($categories as $cat)
                @if($cat->pois->isNotEmpty())
                @php
                    $colors = [
                        'fa-crown'          => '#c99f5b',
                        'fa-umbrella-beach' => '#2C5F6E',
                        'fa-landmark'       => '#7c3aed',
                        'fa-person-biking'  => '#16a34a',
                        'fa-utensils'       => '#dc2626',
                        'fa-bag-shopping'   => '#d97706',
                    ];
                    $catColor = $colors[$cat->icon] ?? '#374151';
                @endphp
                <div class="poi-category-group mb-3" data-cat-group="{{ $cat->id }}">
                    <h5 class="heading-font-family text-5 fw-600 mb-2" style="color:{{ $catColor }}">
                        @if($cat->icon)<i class="{{ $cat->icon }} me-2"></i>@endif
                        {{ app()->getLocale() === 'pt' ? $cat->name_pt : $cat->name_en }}
                    </h5>
                    @foreach($cat->pois as $poi)
                    <div class="poi-card mb-2 p-3" data-poi-id="{{ $poi->id }}"
                         style="background:var(--bs-body-bg);border:1px solid var(--bs-border-color);border-radius:10px;border-left:3px solid {{ $catColor }};">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <p class="fw-600 text-3 mb-0" style="color:{{ $catColor }}">
                                {{ app()->getLocale() === 'pt' ? $poi->name_pt : $poi->name_en }}
                            </p>
                            @if($poi->distance_km)
                            <span class="text-nowrap" style="font-size:11px;color:#9ca3af;">{{ number_format($poi->distance_km, 1) }} km</span>
                            @endif
                        </div>
                        @if(app()->getLocale() === 'pt' ? $poi->description_pt : $poi->description_en)
                        <p class="text-3 text-body-secondary mb-0 mt-1" style="font-size:12px;line-height:1.5;">
                            {{ Str::limit(app()->getLocale() === 'pt' ? $poi->description_pt : $poi->description_en, 90) }}
                        </p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
                @empty
                <div class="poi-card">
                    <p class="text-body-secondary fst-italic mb-0">{{ __('o_que_fazer.pois_soon') }}</p>
                </div>
                @endforelse

                @foreach($categories->filter(fn($c) => $c->pois->isEmpty()) as $cat)
                <div class="poi-card mb-2 poi-category-group" data-cat-group="{{ $cat->id }}">
                    <h5 class="heading-font-family text-5 fw-600 mb-1">
                        @if($cat->icon)<i class="{{ $cat->icon }} text-primary me-2"></i>@endif
                        {{ app()->getLocale() === 'pt' ? $cat->name_pt : $cat->name_en }}
                    </h5>
                    <p class="text-3 text-body-secondary mb-0 fst-italic">{{ __('o_que_fazer.coming_soon') }}</p>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.min.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV/XN/sp38=" crossorigin=""></script>
<script>
(function () {
    var pois = @json($poisJson);

    var catColors = {};
    @foreach($categories as $cat)
    @php
        $colors = [
            'fa-crown'          => '#c99f5b',
            'fa-umbrella-beach' => '#2C5F6E',
            'fa-landmark'       => '#7c3aed',
            'fa-person-biking'  => '#16a34a',
            'fa-utensils'       => '#dc2626',
            'fa-bag-shopping'   => '#d97706',
        ];
        $c = $colors[$cat->icon] ?? '#374151';
    @endphp
    catColors[{{ $cat->id }}] = '{{ $c }}';
    @endforeach

    function makeIcon(color) {
        var svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 36" width="24" height="36">'
            + '<path fill="' + color + '" stroke="#fff" stroke-width="1.5" d="M12 0C7.6 0 4 3.6 4 8c0 5.4 8 20 8 20s8-14.6 8-20c0-4.4-3.6-8-8-8z"/>'
            + '<circle fill="#fff" cx="12" cy="8" r="3.5"/>'
            + '</svg>';
        return L.divIcon({
            html: svg,
            className: '',
            iconSize: [24, 36],
            iconAnchor: [12, 36],
            popupAnchor: [0, -38],
        });
    }

    var map = L.map('fh-map', { zoomControl: true, scrollWheelZoom: false })
               .setView([38.93, -9.38], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 18,
    }).addTo(map);

    var markers = {};

    pois.forEach(function (p) {
        if (!p.lat || !p.lng) return;
        var color  = catColors[p.category_id] || '#374151';
        var marker = L.marker([p.lat, p.lng], { icon: makeIcon(color) });

        var popup = '<div class="fh-popup-title">' + p.name + '</div>';
        if (p.description) popup += '<div class="fh-popup-desc">' + p.description + '</div>';
        if (p.distance_km) popup += '<div class="fh-popup-dist">📍 ' + p.distance_km.toFixed(1) + ' km</div>';
        marker.bindPopup(popup, { maxWidth: 240 });
        marker.addTo(map);
        markers[p.id] = { marker: marker, catId: p.category_id };
    });

    /* ── Filtros por categoria ─────────────────────── */
    var activeCat = 'all';

    document.querySelectorAll('.fh-map-pill').forEach(function (btn) {
        btn.addEventListener('click', function () {
            activeCat = this.dataset.cat;

            document.querySelectorAll('.fh-map-pill').forEach(function (b) {
                b.classList.remove('active');
                b.style.background = '#f3f4f6';
                b.style.color = '#374151';
            });
            this.classList.add('active');
            this.style.background = activeCat === 'all' ? '#374151' : (this.dataset.color || '#374151');
            this.style.color = '#fff';

            Object.values(markers).forEach(function (m) {
                if (activeCat === 'all' || String(m.catId) === activeCat) {
                    m.marker.addTo(map);
                } else {
                    map.removeLayer(m.marker);
                }
            });

            document.querySelectorAll('.poi-category-group').forEach(function (g) {
                var gid = g.dataset.catGroup;
                g.style.display = (activeCat === 'all' || gid === activeCat) ? '' : 'none';
            });
        });
    });

    /* ── Clicar na lista abre popup ────────────────── */
    document.querySelectorAll('.poi-card[data-poi-id]').forEach(function (card) {
        card.addEventListener('click', function () {
            var id = parseInt(this.dataset.poiId);
            if (markers[id]) {
                map.setView(markers[id].marker.getLatLng(), 14, { animate: true });
                markers[id].marker.openPopup();
                document.querySelectorAll('.poi-card').forEach(function (c) { c.classList.remove('highlighted'); });
                this.classList.add('highlighted');
            }
        });
    });
})();
</script>
@endpush
