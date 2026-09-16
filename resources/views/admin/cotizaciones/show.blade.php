@extends('layouts.admin')
@section('title', 'Cotización #' . $cotizacione->id_cotizacion)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Cotización #{{ $cotizacione->id_cotizacion }}</h5>
    <div class="d-flex gap-2">
        @if($cotizacione->estado === 'Aprobada')
            <form method="POST" action="{{ route('admin.cotizaciones.factura', $cotizacione) }}">
                @csrf
                <button class="btn btn-sm btn-success">Convertir en factura</button>
            </form>
        @endif
        @if($cotizacione->estado === 'Pendiente')
            <form method="POST" action="{{ route('admin.cotizaciones.rechazar', $cotizacione) }}">
                @csrf @method('PATCH')
                <button class="btn btn-sm btn-danger" onclick="return confirm('¿Marcar como rechazada?')">Rechazar</button>
            </form>
        @endif
        <a href="{{ route('admin.cotizaciones.index') }}" class="btn btn-sm btn-outline-secondary">← Volver</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light fw-semibold">Datos generales</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5">Estado</dt>
                    <dd class="col-7">
                        @php $badge = match($cotizacione->estado){ 'Aprobada'=>'success','Rechazada'=>'danger','Vencida'=>'secondary',default=>'warning text-dark' }; @endphp
                        <span class="badge bg-{{ $badge }}">{{ $cotizacione->estado }}</span>
                    </dd>
                    <dt class="col-5">Cliente</dt><dd class="col-7">{{ $cotizacione->cliente->usuario->nombre }}</dd>
                    <dt class="col-5">Vehículo</dt><dd class="col-7">{{ $cotizacione->vehiculo?->placa ?? '—' }}</dd>
                    <dt class="col-5">Creada por</dt><dd class="col-7">{{ $cotizacione->usuario->nombre }}</dd>
                    <dt class="col-5">Fecha</dt><dd class="col-7">{{ $cotizacione->fecha->format('d/m/Y') }}</dd>
                    <dt class="col-5">Vence</dt><dd class="col-7">{{ $cotizacione->fecha_vencimiento?->format('d/m/Y') ?? '—' }}</dd>
                    <dt class="col-5">Observaciones</dt><dd class="col-7">{{ $cotizacione->observaciones ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light fw-semibold">Totales</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5">Subtotal</dt><dd class="col-7">${{ number_format($cotizacione->subtotal,2) }}</dd>
                    <dt class="col-5">Impuesto</dt><dd class="col-7">${{ number_format($cotizacione->impuesto,2) }}</dd>
                    <dt class="col-5 fw-bold">Total</dt><dd class="col-7 fw-bold">${{ number_format($cotizacione->total,2) }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light fw-semibold">Servicios incluidos</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light"><tr><th>Servicio</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        @forelse($cotizacione->servicios as $s)
                        <tr>
                            <td>{{ $s->servicio->nombre }}</td>
                            <td>{{ $s->cantidad }}</td>
                            <td>${{ number_format($s->precio,2) }}</td>
                            <td>${{ number_format($s->subtotal,2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted p-3">Sin servicios.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light fw-semibold">Productos incluidos</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light"><tr><th>Producto</th><th>Cantidad</th><th>Precio unit.</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        @forelse($cotizacione->productos as $p)
                        <tr>
                            <td>{{ $p->producto->nombre }}</td>
                            <td>{{ $p->cantidad }}</td>
                            <td>${{ number_format($p->precio_unitario,2) }}</td>
                            <td>${{ number_format($p->subtotal,2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted p-3">Sin productos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
