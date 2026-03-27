<?php

namespace App\Mail;

use App\Models\StockProducto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AlertaStockBajo extends Mailable
{
    use Queueable, SerializesModels;

    public StockProducto $stock;
    public int $stockAnterior;
    public int $stockActual;

    /**
     * Create a new message instance.
     */
    public function __construct(StockProducto $stock, int $stockAnterior, int $stockActual)
    {
        $this->stock = $stock;
        $this->stockAnterior = $stockAnterior;
        $this->stockActual = $stockActual;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $nombreProducto = $this->stock->producto->nombre ?? 'Producto';

        return new Envelope(
            subject: "Alerta: Stock bajo - {$nombreProducto}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.alerta-stock-bajo',
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
