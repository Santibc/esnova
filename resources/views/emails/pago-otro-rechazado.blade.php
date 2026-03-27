<x-mail::message>
# Pago Rechazado

Hola **{{ $compra->nombre_cliente }}**,

Lamentamos informarte que tu pago para el pedido **#{{ $compra->numero_compra }}** ha sido **rechazado**.

## Motivo del rechazo

<x-mail::panel>
{{ $compra->motivo_rechazo }}
</x-mail::panel>

## Detalles del pedido
- **Numero de orden:** #{{ $compra->numero_compra }}
- **Fecha de compra:** {{ $compra->created_at->format('d/m/Y H:i') }}
- **Total:** ${{ number_format($compra->total, 0, ',', '.') }}

## Que puedes hacer

El stock de los productos ha sido liberado. Si deseas, puedes realizar una nueva compra y asegurarte de incluir la informacion correcta de pago.

Si crees que esto es un error o necesitas mas informacion, por favor contactanos para que podamos ayudarte.

Saludos,<br>
**{{ $compra->empresa->nombre }}**
</x-mail::message>
