<x-mail::message>
# Alerta de Stock Bajo

El siguiente producto ha alcanzado un nivel de stock bajo y requiere atencion.

## Producto
- **Nombre:** {{ $stock->producto->nombre ?? 'N/A' }}
- **Referencia:** {{ $stock->producto->referencia ?? 'N/A' }}
@if($stock->variante)
- **Variante:** {{ $stock->variante->nombre }}
@endif

## Estado del Inventario

<x-mail::panel>
| Estado | Unidades |
|:-------|:--------:|
| Stock anterior | {{ $stockAnterior }} |
| Stock actual | {{ $stockActual }} |
| Stock minimo configurado | {{ $stock->stock_minimo }} |
</x-mail::panel>

<x-mail::panel>
**Recomendacion:** Considera reponer el inventario de este producto para evitar quedarte sin stock y perder ventas.
</x-mail::panel>

<x-mail::button :url="route('stock.index')">
Gestionar Inventario
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
