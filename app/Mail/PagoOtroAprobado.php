<?php

namespace App\Mail;

use App\Models\Compra;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PagoOtroAprobado extends Mailable
{
    use Queueable, SerializesModels;

    public $compra;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Compra $compra)
    {
        $this->compra = $compra;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Pago aprobado - Orden #' . $this->compra->numero_compra,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'emails.pago-otro-aprobado',
            with: [
                'compra' => $this->compra,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
