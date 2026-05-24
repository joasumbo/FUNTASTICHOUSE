<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Novo Pedido de Reserva</title>
<style>
  body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
  .wrap { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; }
  .header { background: #1a1a1a; padding: 24px 40px; }
  .header h1 { color: #c99f5b; font-size: 18px; margin: 0 0 2px; }
  .header p { color: rgba(255,255,255,.45); font-size: 12px; margin: 0; }
  .body { padding: 32px 40px; }
  .body h2 { font-size: 18px; color: #1a1a1a; margin: 0 0 16px; }
  .details { background: #f9f6f0; border-left: 4px solid #c99f5b; border-radius: 8px; padding: 20px 24px; margin: 0 0 20px; }
  .details table { width: 100%; border-collapse: collapse; }
  .details td { padding: 5px 0; font-size: 14px; color: #333; vertical-align: top; }
  .details td:first-child { font-weight: bold; width: 130px; color: #1a1a1a; }
  .id-badge { background: #e8e0d0; border-radius: 6px; padding: 8px 14px; font-size: 13px; color: #666; display: inline-block; margin-bottom: 20px; }
  .footer { background: #f0ece4; padding: 20px 40px; text-align: center; }
  .footer p { color: #999; font-size: 12px; margin: 0; }
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>Novo Pedido de Reserva</h1>
    <p>Funtastic House — Painel do Owner</p>
  </div>
  <div class="body">
    <div class="id-badge">Reserva #{{ $reservation->id }} · {{ now()->format('d/m/Y H:i') }}</div>
    <h2>{{ $reservation->name }} quer reservar</h2>

    <div class="details">
      <table>
        <tr><td>Experiência</td><td>{{ $experience->name_pt }}</td></tr>
        <tr><td>Check-in</td><td>{{ $reservation->check_in->format('d/m/Y') }}</td></tr>
        <tr><td>Check-out</td><td>{{ $reservation->check_out->format('d/m/Y') }}</td></tr>
        <tr><td>Hóspedes</td><td>{{ $reservation->guests }}</td></tr>
        <tr><td>Email</td><td><a href="mailto:{{ $reservation->email }}">{{ $reservation->email }}</a></td></tr>
        <tr><td>Telefone</td><td><a href="tel:{{ $reservation->phone }}">{{ $reservation->phone }}</a></td></tr>
        @if($reservation->message)
        <tr><td>Notas</td><td>{{ $reservation->message }}</td></tr>
        @endif
      </table>
    </div>

    <p style="font-size:13px; color:#888;">Responde directamente ao hóspede para confirmar ou recusar. Gestão de reservas disponível no painel admin em breve.</p>
  </div>
  <div class="footer">
    <p>Funtastic House · Email automático — não responder para este endereço</p>
  </div>
</div>
</body>
</html>
