@extends('layouts.site')

@section('title', __('o_que_fazer.page_title'))
@section('meta_description', __('o_que_fazer.seo_desc'))

@php
$iconColors = [
    'fa-crown'          => '#c99f5b',
    'fa-umbrella-beach' => '#2C5F6E',
    'fa-landmark'       => '#7c3aed',
    'fa-person-biking'  => '#16a34a',
    'fa-utensils'       => '#dc2626',
    'fa-bag-shopping'   => '#d97706',
];
@endphp

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.css">
<style>
/* Página O Que Fazer */
#fh-map {
    height: 560px;
    width: 100%;
    border-radius: 1rem;
}

.fh-map-sticky {
    position: sticky;
    top: 90px;
}

.fh-poi-panel {
    max-height: 560px;
    overflow-y: auto;
    padding-right: 4px;
    scrollbar-width: thin;
    scrollbar-color: rgba(201,159,91,.4) transparent;
}
.fh-poi-panel::-webkit-scrollbar { width: 4px; }
.fh-poi-panel::-webkit-scrollbar-thumb { background: rgba(201,159,91,.35); border-radius: 4px; }

.fh-filter-bar { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 20px; }
.fh-filter-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 14px; border-radius: 99px;
    font-size: 12px; font-weight: 600; letter-spacing: .02em;
    cursor: pointer; transition: all .18s;
    border: 1.5px solid #e5e7eb; background: #fff; color: #4b5563;
}
.fh-filter-btn:hover { border-color: var(--bs-themecolor); color: var(--bs-themecolor); }
.fh-filter-btn.active { background: var(--bs-themecolor); border-color: var(--bs-themecolor); color: #fff; }
.fh-filter-btn.active i { color: #fff !important; }

.fh-cat-label {
    font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em;
    padding: 12px 0 7px;
    display: flex; align-items: center; gap: 7px;
    border-bottom: 1px solid #f0ece5; margin-bottom: 8px;
}

.fh-poi-card {
    background: #fff;
    border: 1px solid #f0ece5;
    border-radius: 10px;
    padding: 12px 14px;
    cursor: pointer;
    transition: transform .18s, box-shadow .18s;
    margin-bottom: 8px;
}
.fh-poi-card:hover { box-shadow: 0 4px 18px rgba(0,0,0,.09); transform: translateX(5px); }
.fh-poi-card.highlighted { box-shadow: 0 0 0 2px var(--bs-themecolor); }

/* Popup Leaflet */
.leaflet-popup-content-wrapper { border-radius: 12px; box-shadow: 0 8px 28px rgba(0,0,0,.14); }
.leaflet-popup-content { margin: 12px 16px; }
.fh-popup-title { font-weight: 700; font-size: 14px; color: #111; margin-bottom: 3px; }
.fh-popup-desc { font-size: 12px; color: #6b7280; line-height: 1.5; margin-bottom: 5px; }
.fh-popup-dist { font-size: 11px; font-weight: 600; color: #c99f5b; }

@media (max-width: 991px) {
    #fh-map { height: 320px; }
    .fh-map-sticky { position: relative; top: 0; }
    .fh-poi-panel { max-height: none; overflow-y: visible; padding-right: 0; }
}
</style>
@endpush

@section('content')

{{-- Cabeçalho da página --}}
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

        {{-- Introdução --}}
        <div class="mx-auto text-center mb-5">
            <p class="wow fadeInUp">
                <span class="text-3 text-uppercase fw-600 rounded-pill border border-dark border-opacity-10 px-3 py-1">{{ __('o_que_fazer.badge') }}</span>
            </p>
            <h2 class="heading-font-family text-13 fw-600 lh-sm wow fadeInUp" data-wow-delay=".2s">
                {!! __('o_que_fazer.title') !!}
            </h2>
            <p class="text-body-secondary mx-auto" style="max-width:560px;">{{ __('o_que_fazer.subtitle') }}</p>
        </div>

        <div class="row g-4 align-items-start">

            {{-- Painel esquerdo: filtros + lista de POIs --}}
            <div class="col-lg-5 wow fadeInLeft">

                @if($categories->isNotEmpty())
                <div class="fh-filter-bar">
                    <button class="fh-filter-btn active" data-cat="all">
                        <i class="fa-solid fa-map-location-dot" style="font-size:11px;"></i>
                        {{ __('o_que_fazer.filter_all') }}
                    </button>
                    @foreach($categories->filter(fn($c) => $c->pois->isNotEmpty()) as $cat)
                    @php $color = $iconColors[$cat->icon] ?? '#374151'; @endphp
                    <button class="fh-filter-btn" data-cat="{{ $cat->id }}" data-color="{{ $color }}">
                        @if($cat->icon)
                        <i class="fa-solid {{ $cat->icon }}" style="font-size:11px;color:{{ $color }};"></i>
                        @endif
                        {{ app()->getLocale() === 'pt' ? $cat->name_pt : $cat->name_en }}
                        <span style="background:{{ $color }};color:#fff;border-radius:99px;font-size:10px;padding:1px 7px;font-weight:700;line-height:1.8;">{{ $cat->pois->count() }}</span>
                    </button>
                    @endforeach
                </div>
                @endif

                <div class="fh-poi-panel">
                    @forelse($categories as $cat)
                    @if($cat->pois->isNotEmpty())
                    @php $catColor = $iconColors[$cat->icon] ?? '#374151'; @endphp
                    <div class="poi-category-group" data-cat-group="{{ $cat->id }}">
                        <div class="fh-cat-label" style="color:{{ $catColor }};">
                            @if($cat->icon)<i class="fa-solid {{ $cat->icon }}"></i>@endif
                            {{ app()->getLocale() === 'pt' ? $cat->name_pt : $cat->name_en }}
                        </div>
                        @foreach($cat->pois as $poi)
                        <div class="fh-poi-card" data-poi-id="{{ $poi->id }}"
                             style="border-left: 3px solid {{ $catColor }};">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <p class="fw-600 mb-0" style="font-size:14px; color:{{ $catColor }};">
                                    {{ app()->getLocale() === 'pt' ? $poi->name_pt : $poi->name_en }}
                                </p>
                                @if($poi->distance_km)
                                <span class="text-nowrap flex-shrink-0" style="font-size:11px;color:#9ca3af;">
                                    {{ number_format($poi->distance_km, 1) }} km
                                </span>
                                @endif
                            </div>
                            @php
                                $desc = app()->getLocale() === 'pt' ? $poi->description_pt : $poi->description_en;
                            @endphp
                            @if($desc)
                            <p class="mb-0 mt-1" style="font-size:12px;color:#6b7280;line-height:1.5;">
                                {{ Str::limit($desc, 90) }}
                            </p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif
                    @empty
                    <p class="text-body-secondary fst-italic text-3">{{ __('o_que_fazer.pois_soon') }}</p>
                    @endforelse

                    {{-- Grupos vazios (ocultos, usados apenas para o filtro) --}}
                    @foreach($categories->filter(fn($c) => $c->pois->isEmpty()) as $cat)
                    <div class="poi-category-group" data-cat-group="{{ $cat->id }}" style="display:none;" data-empty="1"></div>
                    @endforeach
                </div>

            </div>

            {{-- Mapa (lado direito, sticky no desktop) --}}
            <div class="col-lg-7 wow fadeInRight">
                <div class="fh-map-sticky">
                    <div id="fh-map" class="border shadow-sm"></div>
                    <p class="text-3 text-body-secondary mt-2 fst-italic">
                        <i class="fa-solid fa-circle-info text-primary me-1"></i>
                        {{ __('o_que_fazer.map_note') }}
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.js"></script>
<script>
(function () {
    var pois = @json($poisJson);

    var catColors = {};
    @foreach($categories as $cat)
    @php $c = $iconColors[$cat->icon] ?? '#374151'; @endphp
    catColors[{{ $cat->id }}] = '{{ $c }}';
    @endforeach

    function makePin(color) {
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
        var marker = L.marker([p.lat, p.lng], { icon: makePin(color) });

        var popup = '<div class="fh-popup-title">' + p.name + '</div>';
        if (p.description) popup += '<div class="fh-popup-desc">' + p.description + '</div>';
        if (p.distance_km) popup += '<div class="fh-popup-dist">📍 ' + p.distance_km.toFixed(1) + ' km</div>';
        marker.bindPopup(popup, { maxWidth: 240 });
        marker.addTo(map);
        markers[p.id] = { marker: marker, catId: p.category_id };
    });

    /* Filtros por categoria */
    document.querySelectorAll('.fh-filter-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var cat = this.dataset.cat;

            document.querySelectorAll('.fh-filter-btn').forEach(function (b) {
                b.classList.remove('active');
            });
            this.classList.add('active');

            Object.values(markers).forEach(function (m) {
                if (cat === 'all' || String(m.catId) === cat) {
                    m.marker.addTo(map);
                } else {
                    map.removeLayer(m.marker);
                }
            });

            document.querySelectorAll('.poi-category-group').forEach(function (g) {
                if (!g.dataset.empty) {
                    g.style.display = (cat === 'all' || g.dataset.catGroup === cat) ? '' : 'none';
                }
            });
        });
    });

    /* Clicar num card abre popup no mapa */
    document.querySelectorAll('.fh-poi-card[data-poi-id]').forEach(function (card) {
        card.addEventListener('click', function () {
            var id = parseInt(this.dataset.poiId);
            if (markers[id]) {
                map.setView(markers[id].marker.getLatLng(), 14, { animate: true });
                markers[id].marker.openPopup();
                document.querySelectorAll('.fh-poi-card').forEach(function (c) {
                    c.classList.remove('highlighted');
                });
                this.classList.add('highlighted');
            }
        });
    });
})();
</script>
@endpush
