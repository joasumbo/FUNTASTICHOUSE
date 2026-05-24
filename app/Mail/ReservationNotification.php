<?php

namespace App\Mail;

use App\Models\Experience;
use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Reservation $reservation,
        public Experience  $experience,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Novo Pedido de Reserva — ' . $this->reservation->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
