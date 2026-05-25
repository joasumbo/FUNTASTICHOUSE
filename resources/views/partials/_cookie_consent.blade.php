@php $locale = app()->getLocale(); @endphp
<div id="fh-cookie-bar" style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:8500;">
    <div class="container py-3">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 rounded-4 px-4 py-3"
             style="background:#111;border:1px solid rgba(201,159,91,0.25);">
            <p class="mb-0 text-3 text-light text-opacity-75">
                @if($locale === 'pt')
                    Utilizamos cookies para melhorar a sua experiência. Ao continuar, aceita a nossa
                    <a href="{{ route('paginas.politica') }}" class="link-primary">Política de Privacidade</a>.
                @else
                    We use cookies to improve your experience. By continuing, you accept our
                    <a href="{{ route('paginas.politica') }}" class="link-primary">Privacy Policy</a>.
                @endif
            </p>
            <div class="d-flex gap-2 flex-shrink-0">
                <button id="fh-cookie-decline" class="btn btn-sm btn-outline-light rounded-pill px-4">
                    {{ $locale === 'pt' ? 'Recusar' : 'Decline' }}
                </button>
                <button id="fh-cookie-accept" class="btn btn-sm rounded-pill px-4 fw-600" style="background:#c99f5b;color:#fff;border:none;">
                    {{ $locale === 'pt' ? 'Aceitar' : 'Accept' }}
                </button>
            </div>
        </div>
    </div>
</div>
<script>
(function () {
    var bar = document.getElementById('fh-cookie-bar');
    var k   = 'fh_cookie_consent';
    if (!localStorage.getItem(k)) bar.style.display = 'block';
    document.getElementById('fh-cookie-accept').addEventListener('click', function () {
        localStorage.setItem(k, 'accepted');
        bar.style.display = 'none';
    });
    document.getElementById('fh-cookie-decline').addEventListener('click', function () {
        localStorage.setItem(k, 'declined');
        bar.style.display = 'none';
    });
}());
</script>
