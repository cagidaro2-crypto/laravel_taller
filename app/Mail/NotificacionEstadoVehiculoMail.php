<?php

namespace App\Mail;

use App\Models\Admin\Notificacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificacionEstadoVehiculoMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Notificacion $notificacion;

    /**
     * Create a new message instance.
     */
    public function __construct(Notificacion $notificacion)
    {
        $this->notificacion = $notificacion;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->notificacion->titulo,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.notificacion-estado-vehiculo',
            with: [
                'notificacion' => $this->notificacion,
                'cliente' => $this->notificacion->usuarioDestinatario,
                'vehiculo' => $this->notificacion->vehiculo,
                'orden' => $this->notificacion->orden,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
