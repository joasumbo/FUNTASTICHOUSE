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
/* Layout da página */
.fh-oqf-wrap {
    display: grid;
    grid-template-columns: 420px 1fr;
    gap: 0;
    min-height: 600px;
}

/* Painel esquerdo — lista */
.fh-list-panel {
    border-right: 1px solid #f0ece5;
    display: flex;
    flex-direction: column;
}

/* Filtros tipo tab */
.fh-tabs {
    display: flex;
    overflow-x: auto;
    scrollbar-width: none;
    border-bottom: 1px solid #e9e4db;
    flex-shrink: 0;
    padding: 0 24px;
}
.fh-tabs::-webkit-scrollbar { display: none; }

.fh-tab {
    padding: 14px 14px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
    color: #9ca3af;
    border: none;
    background: transparent;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    margin-bottom: -1px;
    white-space: nowrap;
    transition: color .18s, border-color .18s;
    display: flex;
    align-items: center;
    gap: 6px;
}
.fh-tab:hover { color: #4b5563; }
.fh-tab.active { color: #c99f5b; border-bottom-color: #c99f5b; }
.fh-tab i { font-size: 11px; }

/* Lista de POIs (scroll da página, não do painel) */
.fh-poi-list {
    padding: 0 24px 48px;
    overflow-y: auto;
    flex: 1;
}

/* Cabeçalho de categoria */
.fh-cat-head {
    padding: 28px 0 10px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.fh-cat-head-text {
    font-family: 'Cormorant Garamond', serif;
    font-size: 20px;
    font-weight: 600;
    color: #111;
    line-height: 1;
}
.fh-cat-head-icon {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.fh-cat-head-icon i { font-size: 14px; color: #fff; }

/* Item de POI */
.fh-poi-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 14px 10px;
    margin: 0 -10px;
    cursor: pointer;
    border-radius: 10px;
    transition: background .15s;
    border-bottom: 1px solid #f5f2ee;
}
.fh-poi-item:last-child { border-bottom: none; }
.fh-poi-item:hover { background: #fdf8f2; }
.fh-poi-item.highlighted { background: #fdf3e3; }

.fh-poi-bullet {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
    margin-top: 6px;
}
.fh-poi-body { flex: 1; min-width: 0; }
.fh-poi-name {
    font-size: 14px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 3px;
    line-height: 1.3;
}
.fh-poi-desc {
    font-size: 12px;
    color: #9ca3af;
    line-height: 1.55;
}
.fh-poi-dist {
    font-size: 11px;
    font-weight: 600;
    color: #b0a99c;
    white-space: nowrap;
    flex-shrink: 0;
    margin-top: 3px;
}

/* Painel direito — mapa */
.fh-map-panel {
    position: sticky;
    top: 80px;
    height: calc(100vh - 100px);
    max-height: 720px;
    display: flex;
    flex-direction: column;
}

#fh-map {
    flex: 1;
    width: 100%;
    border-radius: 0;
}

.fh-map-note {
    padding: 10px 20px;
    font-size: 12px;
    color: #9ca3af;
    font-style: italic;
    background: #fff;
    border-top: 1px solid #f0ece5;
    flex-shrink: 0;
}

/* Popup do mapa */
.leaflet-popup-content-wrapper {
    border-radius: 14px !important;
    box-shadow: 0 12px 36px rgba(0,0,0,.14) !important;
    border: none !important;
}
.leaflet-popup-content { margin: 14px 18px !important; }
.fh-popup-title { font-weight: 700; font-size: 14px; color: #111; margin-bottom: 4px; }
.fh-popup-desc { font-size: 12px; color: #6b7280; line-height: 1.5; margin-bottom: 6px; }
.fh-popup-dist { font-size: 11px; font-weight: 600; color: #c99f5b; }
.leaflet-popup-tip-container { display: block; }

/* Responsive */
@media (max-width: 991px) {
    .fh-oqf-wrap {
        grid-template-columns: 1fr;
    }
    .fh-list-panel { border-right: none; order: 2; }
    .fh-map-panel {
        order: 1;
        position: relative;
        top: 0;
        height: 320px;
        max-height: 320px;
        border-bottom: 1px solid #f0ece5;
    }
    #fh-map { border-radius: 0; }
    .fh-tabs { padding: 0 16px; }
    .fh-poi-list { padding: 0 16px 32px; }
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

{{-- Introdução --}}
<section class="py-5">
    <div class="container">
        <div class="mx-auto text-center">
            <p class="wow fadeInUp">
                <span class="text-3 text-uppercase fw-600 rounded-pill border border-dark border-opacity-10 px-3 py-1">{{ __('o_que_fazer.badge') }}</span>
            </p>
            <h2 class="heading-font-family text-13 fw-600 lh-sm wow fadeInUp" data-wow-delay=".2s">
                {!! __('o_que_fazer.title') !!}
            </h2>
            <p class="text-body-secondary mx-auto mb-0" style="max-width:540px;">{{ __('o_que_fazer.subtitle') }}</p>
        </div>
    </div>
</section>

{{-- Layout principal: lista + mapa --}}
<div class="fh-oqf-wrap border-top border-bottom" style="border-color:#f0ece5 !important;">

    {{-- Painel esquerdo: filtros + lista --}}
    <div class="fh-list-panel">

        {{-- Tabs de filtro --}}
        <div class="fh-tabs">
            <button class="fh-tab active" data-cat="all">
                <i class="fa-solid fa-map-location-dot"></i>
                {{ __('o_que_fazer.filter_all') }}
            </button>
            @foreach($categories->filter(fn($c) => $c->pois->isNotEmpty()) as $cat)
            @php $color = $iconColors[$cat->icon] ?? '#374151'; @endphp
            <button class="fh-tab" data-cat="{{ $cat->id }}" data-color="{{ $color }}">
                @if($cat->icon)<i class="fa-solid {{ $cat->icon }}" style="color:{{ $color }};"></i>@endif
                {{ app()->getLocale() === 'pt' ? $cat->name_pt : $cat->name_en }}
            </button>
            @endforeach
        </div>

        {{-- Lista de POIs --}}
        <div class="fh-poi-list">
            @forelse($categories as $cat)
            @if($cat->pois->isNotEmpty())
            @php $catColor = $iconColors[$cat->icon] ?? '#374151'; @endphp
            <div class="poi-category-group" data-cat-group="{{ $cat->id }}">
                <div class="fh-cat-head">
                    <div class="fh-cat-head-icon" style="background:{{ $catColor }};">
                        @if($cat->icon)<i class="fa-solid {{ $cat->icon }}"></i>@endif
                    </div>
                    <span class="fh-cat-head-text">
                        {{ app()->getLocale() === 'pt' ? $cat->name_pt : $cat->name_en }}
                    </span>
                </div>

                @foreach($cat->pois as $poi)
                @php $desc = app()->getLocale() === 'pt' ? $poi->description_pt : $poi->description_en; @endphp
                <div class="fh-poi-item" data-poi-id="{{ $poi->id }}">
                    <div class="fh-poi-bullet" style="background:{{ $catColor }};"></div>
                    <div class="fh-poi-body">
                        <div class="fh-poi-name">{{ app()->getLocale() === 'pt' ? $poi->name_pt : $poi->name_en }}</div>
                        @if($desc)
                        <div class="fh-poi-desc">{{ Str::limit($desc, 100) }}</div>
                        @endif
                    </div>
                    @if($poi->distance_km)
                    <div class="fh-poi-dist">{{ number_format($poi->distance_km, 1) }} km</div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
            @empty
            <p class="text-body-secondary fst-italic text-3 mt-4">{{ __('o_que_fazer.pois_soon') }}</p>
            @endforelse

            {{-- Grupos vazios (ocultos, necessários para o filtro) --}}
            @foreach($categories->filter(fn($c) => $c->pois->isEmpty()) as $cat)
            <div class="poi-category-group" data-cat-group="{{ $cat->id }}" style="display:none;" data-empty="1"></div>
            @endforeach
        </div>

    </div>

    {{-- Painel direito: mapa --}}
    <div class="fh-map-panel">
        <div id="fh-map"></div>
        <div class="fh-map-note">
            <i class="fa-solid fa-circle-info text-primary me-1"></i>
            {{ __('o_que_fazer.map_note') }}
        </div>
    </div>

</div>

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
            popupAnchor: [0, -40],
        });
    }

    function makePinActive(color) {
        var svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 48" width="32" height="48">'
            + '<path fill="' + color + '" stroke="#fff" stroke-width="2" d="M16 0C9.4 0 4 5.4 4 12c0 8 12 28 12 28s12-20 12-28c0-6.6-5.4-12-12-12z"/>'
            + '<circle fill="#fff" cx="16" cy="12" r="5"/>'
            + '</svg>';
        return L.divIcon({
            html: svg,
            className: '',
            iconSize: [32, 48],
            iconAnchor: [16, 48],
            popupAnchor: [0, -50],
        });
    }

    var map = L.map('fh-map', {
        zoomControl: true,
        scrollWheelZoom: true,
        tap: true,
        dragging: true,
    }).setView([38.93, -9.38], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

    /* Move os controlos de zoom para o canto inferior direito */
    map.zoomControl.setPosition('bottomright');

    var markers = {};
    var activeMarker = null;

    pois.forEach(function (p) {
        if (!p.lat || !p.lng) return;
        var color  = catColors[p.category_id] || '#374151';
        var marker = L.marker([p.lat, p.lng], { icon: makePin(color) });

        var popup = '<div class="fh-popup-title">' + p.name + '</div>';
        if (p.description) popup += '<div class="fh-popup-desc">' + p.description + '</div>';
        if (p.distance_km) popup += '<div class="fh-popup-dist">📍 ' + p.distance_km.toFixed(1) + ' km</div>';
        marker.bindPopup(popup, { maxWidth: 240, closeButton: false });

        /* Clicar no marker destaca o item na lista */
        marker.on('click', function () {
            if (activeMarker && activeMarker.id !== p.id) {
                var prev = markers[activeMarker.id];
                if (prev) prev.marker.setIcon(makePin(catColors[prev.catId] || '#374151'));
            }
            marker.setIcon(makePinActive(color));
            activeMarker = { id: p.id };

            var card = document.querySelector('.fh-poi-item[data-poi-id="' + p.id + '"]');
            if (card) {
                document.querySelectorAll('.fh-poi-item').forEach(function (c) { c.classList.remove('highlighted'); });
                card.classList.add('highlighted');
                card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });

        map.on('popupclose', function () {
            if (activeMarker && activeMarker.id === p.id) {
                marker.setIcon(makePin(color));
            }
        });

        marker.addTo(map);
        markers[p.id] = { marker: marker, catId: p.category_id };
    });

    /* Clicar num item da lista abre popup e anima o pin */
    document.querySelectorAll('.fh-poi-item[data-poi-id]').forEach(function (item) {
        item.addEventListener('click', function () {
            var id = parseInt(this.dataset.poiId);
            if (!markers[id]) return;

            var color = catColors[markers[id].catId] || '#374151';

            if (activeMarker && markers[activeMarker.id]) {
                var prev = markers[activeMarker.id];
                prev.marker.setIcon(makePin(catColors[prev.catId] || '#374151'));
            }

            markers[id].marker.setIcon(makePinActive(color));
            activeMarker = { id: id };

            map.flyTo(markers[id].marker.getLatLng(), 14, { animate: true, duration: 0.6 });
            markers[id].marker.openPopup();

            document.querySelectorAll('.fh-poi-item').forEach(function (c) { c.classList.remove('highlighted'); });
            this.classList.add('highlighted');
        });
    });

    /* Filtros por categoria */
    document.querySelectorAll('.fh-tab').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var cat = this.dataset.cat;

            document.querySelectorAll('.fh-tab').forEach(function (b) { b.classList.remove('active'); });
            this.classList.add('active');

            /* Mostrar/ocultar markers */
            Object.values(markers).forEach(function (m) {
                if (cat === 'all' || String(m.catId) === cat) {
                    m.marker.addTo(map);
                } else {
                    map.removeLayer(m.marker);
                }
            });

            /* Mostrar/ocultar grupos na lista */
            document.querySelectorAll('.poi-category-group').forEach(function (g) {
                if (!g.dataset.empty) {
                    g.style.display = (cat === 'all' || g.dataset.catGroup === cat) ? '' : 'none';
                }
            });

            /* Ajustar zoom ao filtro activo */
            if (cat !== 'all') {
                var visibleLatLngs = [];
                Object.values(markers).forEach(function (m) {
                    if (String(m.catId) === cat) {
                        visibleLatLngs.push(m.marker.getLatLng());
                    }
                });
                if (visibleLatLngs.length > 0) {
                    map.flyToBounds(L.latLngBounds(visibleLatLngs).pad(0.3), { duration: 0.6 });
                }
            } else {
                map.flyTo([38.93, -9.38], 11, { duration: 0.6 });
            }
        });
    });
})();
</script>
@endpush
