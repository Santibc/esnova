<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\SolicitudCotizacion;

class SolicitudAplicada extends Mailable
{
    use Queueable, SerializesModels;

    public $solicitud;
    protected $pdf;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SolicitudCotizacion $solicitud, $pdf)
    {
        $this->solicitud = $solicitud;
        $this->pdf = $pdf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Solicitud de Cotización #' . $this->solicitud->numero_solicitud . ' - Confirmada')
                    ->view('emails.solicitud-aplicada')
                    ->attachData($this->pdf->output(), 'solicitud-' . $this->solicitud->numero_solicitud . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}