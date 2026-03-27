<x-mail::message>
# Nueva Venta Confirmada

Has recibido una nueva venta en tu tienda.

## Detalles del Pedido
- **Numero de orden:** #{{ $compra->numero_compra }}
- **Fecha:** {{ $compra->created_at->format('d/m/Y H:i') }}
- **Cliente:** {{ $compra->nombre_cliente }}
- **Email:** {{ $compra->email_cliente }}
- **Telefono:** {{ $compra->telefono_cliente ?? 'No proporcionado' }}

@if($compra->direccion_envio)
## Direccion de Envio
{{ $compra->direccion_envio }}
@if($compra->ciudad)
{{ $compra->ciudad->nombre }}, {{ $compra->ciudad->departamento->nombre ?? '' }}
@endif
@endif

## Productos

<x-mail::table>
| Producto | Cantidad | Precio |
|:---------|:--------:|-------:|
@foreach($compra->items as $item)
| {{ $item->nombre_producto }}@if($item->info_variante) ({{ $item->info_variante }})@endif | {{ $item->cantidad }} | ${{ number_format($item->precio_total, 0, ',', '.') }} |
@endforeach
</x-mail::table>

## Resumen
- **Subtotal:** ${{ number_format($compra->subtotal, 0, ',', '.') }}
@if($compra->descuento_total > 0)
- **Descuento:** -${{ number_format($compra->descuento_total, 0, ',', '.') }}
@endif
@if($compra->impuestos > 0)
- **Impuestos:** ${{ number_format($compra->impuestos, 0, ',', '.') }}
@endif
- **Envio:** ${{ number_format($compra->costo_envio, 0, ',', '.') }}
- **Total:** **${{ number_format($compra->total, 0, ',', '.') }}**

<x-mail::button :url="route('compras.show', $compra->id)">
Ver Pedido Completo
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
