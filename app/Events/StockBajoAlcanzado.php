<?php

namespace App\Events;

use App\Models\StockProducto;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockBajoAlcanzado
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public StockProducto $stock;
    public int $stockAnterior;
    public int $stockActual;

    /**
     * Create a new event instance.
     */
    public function __construct(StockProducto $stock, int $stockAnterior, int $stockActual)
    {
        $this->stock = $stock;
        $this->stockAnterior = $stockAnterior;
        $this->stockActual = $stockActual;
    }
}
