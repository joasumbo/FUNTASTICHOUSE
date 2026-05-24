@extends('layouts.site')

@section('title', 'Reservas — Funtastic House')

@section('content')

{{-- Page Header --}}
<section class="page-header page-header-text-light py-0 mb-0">
    <div class="hero-wrap" style="height:280px;">
        <div class="hero-mask opacity-8 bg-black"></div>
        <div class="hero-bg" style="background-image:url('{{ asset('images/slider/slide-2.jpg') }}');"></div>
        <div class="hero-content d-flex align-items-end pb-5 h-100">
            <div class="container">
                <h1 class="heading-font-family text-white fw-700 mb-1">Reservas</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb text-3">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="link-primary">Início</a></li>
                        <li class="breadcrumb-item active">Reservas</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="section bg-dark">
    <div class="container">
        <div class="mx-auto text-center mb-5">
            <p class="wow fadeInUp"><span class="text-3 text-uppercase fw-600 rounded-pill border border-white border-opacity-25 px-3 py-1 text-white-50">Disponibilidade & Reservas</span></p>
            <h2 class="heading-font-family text-13 fw-600 lh-sm text-white wow fadeInUp" data-wow-delay=".2s">Verifica disponibilidade<br>e <span class="text-primary">faz o teu pedido</span></h2>
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
                        <span class="fh-cal-month" id="fh-cal-label">Carregando...</span>
                        <button class="fh-cal-nav" onclick="fhChangeMonth(1)">&#8250;</button>
                    </div>
                    <div class="fh-cal-grid">
                        <div class="fh-cal-dow">Seg</div><div class="fh-cal-dow">Ter</div><div class="fh-cal-dow">Qua</div>
                        <div class="fh-cal-dow">Qui</div><div class="fh-cal-dow">Sex</div><div class="fh-cal-dow">Sáb</div><div class="fh-cal-dow">Dom</div>
                    </div>
                    <div class="fh-cal-grid" id="fh-cal-grid"></div>
                    <div class="fh-cal-legend">
                        <div class="fh-cal-legend-item"><div class="fh-dot" style="background:rgba(201,159,91,.3);border:1px solid var(--bs-themecolor);"></div>Disponível</div>
                        <div class="fh-cal-legend-item"><div class="fh-dot" style="background:rgba(255,255,255,.1);"></div>Ocupado</div>
                        <div class="fh-cal-legend-item"><div class="fh-dot" style="background:rgba(201,159,91,.15);border:1px solid rgba(201,159,91,.4);"></div>Fim de semana (+)</div>
                    </div>
                    <p class="text-3 mt-3 mb-0" style="color:rgba(255,255,255,.35);">
                        <i class="fa-solid fa-circle-info me-1" style="color:var(--bs-themecolor);"></i>
                        Preços ilustrativos. Valores finais confirmados após pedido.
                    </p>
                </div>
            </div>
            {{-- Formulário --}}
            <div class="col-lg-6 wow fadeInRight">
                <div class="p-4 rounded-4" style="background:rgba(255,255,255,.04);border:1px solid rgba(201,159,91,.15);">
                    <h4 class="heading-font-family text-7 fw-600 text-white mb-1">Pedido de Reserva</h4>
                    <p class="text-3 mb-3" style="color:rgba(255,255,255,.4);">Preenche o formulário e entraremos em contacto para confirmar. Sem pagamento online.</p>

                    @if($errors->any())
                    <div class="alert alert-danger rounded-4 text-3 mb-3 py-2">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('reservas.store') }}" method="POST">
                    @csrf
                    <div class="row g-3 form-dark">
                        <div class="col-12">
                            <label class="form-label">Nome Completo *</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control rounded-pill @error('name') is-invalid @enderror" placeholder="O teu nome completo">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefone *</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" class="form-control rounded-pill @error('phone') is-invalid @enderror" placeholder="+351 9XX XXX XXX">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control rounded-pill @error('email') is-invalid @enderror" placeholder="o.teu@email.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Check-in *</label>
                            <input id="reservaCheckIn" type="text" name="check_in" value="{{ old('check_in') }}" class="form-control rounded-pill @error('check_in') is-invalid @enderror" placeholder="DD/MM/AAAA" autocomplete="off">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Check-out *</label>
                            <input id="reservaCheckOut" type="text" name="check_out" value="{{ old('check_out') }}" class="form-control rounded-pill @error('check_out') is-invalid @enderror" placeholder="DD/MM/AAAA" autocomplete="off">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Adultos *</label>
                            <select name="adults" class="form-select rounded-pill @error('adults') is-invalid @enderror">
                                @foreach([1,2,3,4,5,6] as $n)
                                <option value="{{ $n }}" {{ old('adults', 2) == $n ? 'selected' : '' }}>{{ $n }} {{ $n === 1 ? 'Adulto' : 'Adultos' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Crianças</label>
                            <select name="children" class="form-select rounded-pill" id="num-children" onchange="toggleChildAges(this.value)">
                                @foreach([0,1,2,3] as $n)
                                <option value="{{ $n }}" {{ old('children', 0) == $n ? 'selected' : '' }}>{{ $n }} {{ $n === 1 ? 'Criança' : 'Crianças' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 children-ages {{ old('children', 0) > 0 ? 'show' : '' }}" id="children-ages">
                            <label class="form-label">Idades das Crianças</label>
                            <input type="text" name="children_ages" value="{{ old('children_ages') }}" class="form-control rounded-pill" placeholder="Ex: 5, 8, 12 anos">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Experiência Pretendida *</label>
                            <select name="experience_slug" class="form-select rounded-pill @error('experience_slug') is-invalid @enderror" id="exp-select">
                                <option value="">Selecionar...</option>
                                @foreach($experiences as $exp)
                                <option value="{{ $exp->slug }}" {{ old('experience_slug') === $exp->slug ? 'selected' : '' }}>
                                    {{ app()->getLocale() === 'pt' ? $exp->name_pt : $exp->name_en }}
                                    {{ $exp->slug === 'imersiva' ? '🌟' : '🫧' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Mensagem / Pedidos Especiais</label>
                            <textarea name="message" class="form-control rounded-4" rows="3" placeholder="Algum pedido especial?">{{ old('message') }}</textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 text-4 fw-600">
                                <i class="fa-regular fa-paper-plane me-2"></i>Enviar Pedido de Reserva
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
var calState = { exp: '{{ $experiences->first()->slug ?? 'imersiva' }}', year: new Date().getFullYear(), month: new Date().getMonth() };
var fhCache = {};
var apiBase = '{{ url('/api/availability') }}';
var months_pt = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];

function fetchAvailability(slug, callback) {
    if (fhCache[slug]) { callback(fhCache[slug]); return; }
    fetch(apiBase + '/' + slug)
        .then(function(r) { return r.json(); })
        .then(function(data) { fhCache[slug] = data; callback(data); })
        .catch(function() { callback({ blocked_dates: [], prices: { base: 0, weekend: 0 } }); });
}

var fhSel = { in: null, out: null };

function fmtDatePt(ds) {
    var p = ds.split('-');
    return p[2] + '/' + p[1] + '/' + p[0];
}

function renderCal(data) {
    var y = calState.year, m = calState.month;
    var occupied = (data && data.blocked_dates) ? data.blocked_dates : [];
    var p = (data && data.prices) ? data.prices : { base: 0, weekend: 0 };
    document.getElementById('fh-cal-label').textContent = months_pt[m] + ' ' + y;
    var first = new Date(y, m, 1).getDay();
    first = first === 0 ? 6 : first - 1;
    var days = new Date(y, m + 1, 0).getDate();
    var html = '';
    for (var i = 0; i < first; i++) html += '<div class="fh-cal-day empty"></div>';
    for (var d = 1; d <= days; d++) {
        var ds = y + '-' + String(m + 1).padStart(2, '0') + '-' + String(d).padStart(2, '0');
        var dow = new Date(y, m, d).getDay();
        var wknd = (dow === 0 || dow === 6);
        var occ = occupied.indexOf(ds) !== -1;
        var selIn  = fhSel.in  === ds ? ' sel-in'  : '';
        var selOut = fhSel.out === ds ? ' sel-out' : '';
        var inRange = (fhSel.in && fhSel.out && ds > fhSel.in && ds < fhSel.out) ? ' in-range' : '';
        var cls = 'fh-cal-day' + (occ ? ' occ' : '') + (wknd && !occ ? ' wknd' : '') + selIn + selOut + inRange;
        var price = wknd ? p.weekend : p.base;
        var priceHtml = occ ? '' : '<span class="dp">' + price + '€</span>';
        var click = occ ? '' : ' onclick="fhPickDate(\'' + ds + '\')"';
        html += '<div class="' + cls + '"' + click + '>' + d + priceHtml + '</div>';
    }
    document.getElementById('fh-cal-grid').innerHTML = html;
}

function fhPickDate(ds) {
    if (!fhSel.in || (fhSel.in && fhSel.out)) {
        fhSel.in = ds; fhSel.out = null;
        document.getElementById('reservaCheckIn').value  = fmtDatePt(ds);
        document.getElementById('reservaCheckOut').value = '';
    } else {
        if (ds <= fhSel.in) { fhSel.in = ds; document.getElementById('reservaCheckIn').value = fmtDatePt(ds); }
        else { fhSel.out = ds; document.getElementById('reservaCheckOut').value = fmtDatePt(ds); }
    }
    if (fhCache[calState.exp]) renderCal(fhCache[calState.exp]);
}

function fhRenderWithFetch() {
    document.getElementById('fh-cal-label').textContent = 'A carregar...';
    document.getElementById('fh-cal-grid').innerHTML = '';
    fetchAvailability(calState.exp, renderCal);
}

function fhChangeMonth(d) {
    calState.month += d;
    if (calState.month > 11) { calState.month = 0; calState.year++; }
    if (calState.month < 0) { calState.month = 11; calState.year--; }
    if (fhCache[calState.exp]) { renderCal(fhCache[calState.exp]); }
    else { fhRenderWithFetch(); }
}

function fhSwitchExp(exp, btn) {
    calState.exp = exp;
    document.querySelectorAll('.fh-cal-tab').forEach(function(t) { t.classList.remove('active'); });
    btn.classList.add('active');
    var sel = document.getElementById('exp-select');
    if (sel) sel.value = exp;
    fhRenderWithFetch();
}

function toggleChildAges(v) {
    var el = document.getElementById('children-ages');
    if (el) el.classList.toggle('show', parseInt(v) > 0);
}

document.addEventListener('DOMContentLoaded', fhRenderWithFetch);
</script>
@endpush
