<x-mail::message>
# Pago Aprobado

Hola **{{ $compra->nombre_cliente }}**,

Nos complace informarte que tu pago para el pedido **#{{ $compra->numero_compra }}** ha sido **aprobado**.

## Resumen de tu pedido
- **Numero de orden:** #{{ $compra->numero_compra }}
- **Fecha de compra:** {{ $compra->created_at->format('d/m/Y H:i') }}
- **Total:** ${{ number_format($compra->total, 0, ',', '.') }}
- **Total de productos:** {{ $compra->items->count() }} items

### Productos:
@foreach($compra->items as $item)
- {{ $item->nombre_producto }}@if($item->info_variante) - {{ $item->info_variante }}@endif ({{ $item->cantidad }} unidad{{ $item->cantidad > 1 ? 'es' : '' }})
@endforeach

<x-mail::panel>
**Direccion de envio:**
{{ $compra->direccion_envio }}, {{ $compra->ciudad->nombre ?? '' }}, {{ $compra->ciudad->departamento->nombre ?? '' }}
</x-mail::panel>

## Proximos pasos

Tu pedido sera procesado y enviado pronto. Te enviaremos otro correo con la informacion de seguimiento cuando tu pedido sea despachado.

Si tienes alguna pregunta sobre tu pedido, no dudes en contactarnos.

Gracias por tu compra,<br>
**{{ $compra->empresa->nombre }}**
</x-mail::message>
