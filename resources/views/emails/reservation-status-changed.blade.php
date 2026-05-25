<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Actualização da Reserva</title>
<style>
  body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
  .wrap { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; }
  .header { background: #1a1a1a; padding: 32px 40px; text-align: center; }
  .header h1 { color: #c99f5b; font-size: 22px; margin: 0 0 4px; }
  .header p { color: rgba(255,255,255,.5); font-size: 13px; margin: 0; }
  .body { padding: 40px; }
  .body h2 { font-size: 20px; color: #1a1a1a; margin: 0 0 8px; }
  .body p { color: #555; line-height: 1.6; font-size: 15px; }
  .details { background: #f9f6f0; border-left: 4px solid #c99f5b; border-radius: 8px; padding: 20px 24px; margin: 24px 0; }
  .details table { width: 100%; border-collapse: collapse; }
  .details td { padding: 6px 0; font-size: 14px; color: #333; }
  .details td:first-child { font-weight: bold; width: 140px; color: #1a1a1a; }
  .badge-confirmed { display: inline-block; background: #16a34a; color: #fff; font-size: 12px; padding: 3px 10px; border-radius: 20px; margin-bottom: 20px; }
  .badge-cancelled  { display: inline-block; background: #dc2626; color: #fff; font-size: 12px; padding: 3px 10px; border-radius: 20px; margin-bottom: 20px; }
  .footer { background: #f0ece4; padding: 24px 40px; text-align: center; }
  .footer p { color: #888; font-size: 13px; margin: 0; }
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>Funtastic House</h1>
    <p>Uma casa. Um mundo de surpresas.</p>
  </div>
  <div class="body">
    @if($reservation->status === 'confirmed')
      <span class="badge-confirmed">Reserva Confirmada</span>
      <h2>Boa notícia, {{ $reservation->name }}!</h2>
      <p>A tua reserva foi <strong>confirmada</strong>. Estamos a preparar tudo para a tua chegada. Entra em contacto connosco se tiveres alguma questão.</p>
    @else
      <span class="badge-cancelled">Reserva Cancelada</span>
      <h2>Olá, {{ $reservation->name }}</h2>
      <p>Lamentamos informar que a tua reserva foi <strong>cancelada</strong>. Se tiveres questões ou quiseres fazer uma nova reserva, não hesites em contactar-nos.</p>
    @endif

    <div class="details">
      <table>
        <tr><td>Experiência</td><td>{{ $reservation->experience?->name_pt ?? '—' }}</td></tr>
        <tr><td>Check-in</td><td>{{ $reservation->check_in->format('d/m/Y') }}</td></tr>
        <tr><td>Check-out</td><td>{{ $reservation->check_out->format('d/m/Y') }}</td></tr>
        <tr><td>Hóspedes</td><td>{{ $reservation->guests }}</td></tr>
      </table>
    </div>

    <p style="color:#888; font-size:13px;">Se não reconheces esta reserva, ignora este email.</p>
  </div>
  <div class="footer">
    <p>© {{ date('Y') }} Funtastic House · Sintra / Ericeira / Mafra</p>
  </div>
</div>
</body>
</html>
