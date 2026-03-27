{{-- stock/historial.blade.php --}}
<div class="table-responsive">
  @if($movimientos->isEmpty())
    <p class="text-center text-muted">No hay movimientos registrados para este producto.</p>
  @else
    <table class="table table-striped table-sm">
      <thead>
        <tr>
          <th>Fecha/Hora</th>
          <th>Tipo</th>
          <th>Cantidad</th>
          <th>Stock Anterior</th>
          <th>Stock Nuevo</th>
          <th>Origen</th>
          <th>Referencia</th>
          <th>Motivo</th>
          <th>Usuario</th>
        </tr>
      </thead>
      <tbody>
        @foreach($movimientos as $movimiento)
          <tr>
            <td>{{ $movimiento->created_at->format('d/m/Y H:i') }}</td>
            <td>
              <span class="badge bg-{{ $movimiento->color_movimiento }}">
                <i class="{{ $movimiento->icono_movimiento }}"></i>
                {{ ucfirst($movimiento->tipo_movimiento) }}
              </span>
            </td>
            <td>
              @if($movimiento->tipo_movimiento == 'salida')
                <span class="text-danger">-{{ $movimiento->cantidad }}</span>
              @elseif($movimiento->tipo_movimiento == 'entrada')
                <span class="text-success">+{{ $movimiento->cantidad }}</span>
              @elseif($movimiento->tipo_movimiento == 'ajuste')
                <span class="text-warning">
                  @if($movimiento->cantidad > 0)
                    +{{ $movimiento->cantidad }}
                  @else
                    {{ $movimiento->cantidad }}
                  @endif
                </span>
              @else
                {{ $movimiento->cantidad }}
              @endif
            </td>
            <td>{{ $movimiento->stock_anterior }}</td>
            <td>
              <strong>{{ $movimiento->stock_nuevo }}</strong>
            </td>
            <td>{{ $movimiento->descripcion_origen }}</td>
            <td>
              @if($movimiento->referencia_documento)
                <code>{{ $movimiento->referencia_documento }}</code>
              @else
                -
              @endif
            </td>
            <td>
              @if($movimiento->motivo)
                <small>{{ Str::limit($movimiento->motivo, 50) }}</small>
              @else
                -
              @endif
            </td>
            <td>{{ $movimiento->usuario->name }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
    
    <div class="text-center mt-3">
      <small class="text-muted">Mostrando los últimos 50 movimientos</small>
    </div>
  @endif
</div>