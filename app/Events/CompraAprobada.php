<?php

namespace App\Events;

use App\Models\Compra;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CompraAprobada
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Compra $compra;

    /**
     * Create a new event instance.
     */
    public function __construct(Compra $compra)
    {
        $this->compra = $compra;
    }
}
