<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EnlaceAcceso;

class EnlaceCreado extends Mailable
{
    use Queueable, SerializesModels;

    public $enlace;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EnlaceAcceso $enlace)
    {
        $this->enlace = $enlace;
        $this->url = route('catalogo.token', $enlace->token);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Acceso al Catálogo de Productos')
                    ->view('emails.enlace-creado');
    }
}