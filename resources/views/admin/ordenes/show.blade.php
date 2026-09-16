@extends('layouts.admin')
@section('title', 'Orden #' . $ordene->id_orden)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Orden de trabajo #{{ $ordene->id_orden }}</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.ordenes.edit', $ordene) }}" class="btn btn-sm btn-outline-primary">Editar</a>
        <a href="{{ route('admin.ordenes.index') }}" class="btn btn-sm btn-outline-secondary">← Volver</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header fw-semibold bg-light">Información general</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5">Estado</dt>
                    <dd class="col-7"><span class="badge bg-info text-dark">{{ $ordene->estado->nombre }}</span></dd>
                    <dt class="col-5">Vehículo</dt>
                    <dd class="col-7">{{ $ordene->vehiculo->placa }} — {{ $ordene->vehiculo->marca }} {{ $ordene->vehiculo->modelo }}</dd>
                    <dt class="col-5">Cliente</dt>
                    <dd class="col-7">{{ $ordene->vehiculo->cliente->usuario->nombre }}</dd>
                    <dt class="col-5">Técnico</dt>
                    <dd class="col-7">{{ $ordene->usuario->nombre }}</dd>
                    <dt class="col-5">Ingreso</dt>
                    <dd class="col-7">{{ $ordene->fecha_ingreso->format('d/m/Y') }}</dd>
                    <dt class="col-5">Salida</dt>
                    <dd class="col-7">{{ $ordene->fecha_salida?->format('d/m/Y') ?? '—' }}</dd>
                    <dt class="col-5">Problema</dt>
                    <dd class="col-7">{{ $ordene->descripcion_problema ?? '—' }}</dd>
                    <dt class="col-5">Diagnóstico</dt>
                    <dd class="col-7">{{ $ordene->diagnostico ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header fw-semibold bg-light">Fotos del Vehículo</div>
            <div class="card-body">
                @if($ordene->vehiculo->fotos->isEmpty())
                    <div class="text-center text-muted p-3">
                        <svg class="mb-2" width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="small">No hay fotos del vehículo</p>
                    </div>
                @else
                    <div class="row g-2">
                        @foreach($ordene->vehiculo->fotos as $foto)
                            <div class="col-6">
                                <img src="{{ asset('storage/' . $foto->ruta_foto) }}" 
                                     alt="Foto del vehículo" 
                                     class="img-fluid rounded"
                                     style="height: 100px; object-fit: cover;">
                                @if($foto->descripcion)
                                    <small class="d-block text-muted mt-1">{{ $foto->descripcion }}</small>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header fw-semibold bg-light">Totales</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5">Subtotal</dt><dd class="col-7">${{ number_format($ordene->subtotal, 2) }}</dd>
                    <dt class="col-5">Impuesto</dt><dd class="col-7">${{ number_format($ordene->impuesto, 2) }}</dd>
                    <dt class="col-5 fw-bold">Total</dt><dd class="col-7 fw-bold">${{ number_format($ordene->total, 2) }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header fw-semibold bg-light">Servicios</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light"><tr><th>Servicio</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        @forelse($ordene->servicios as $s)
                        <tr>
                            <td>{{ $s->servicio->nombre }}</td>
                            <td>{{ $s->cantidad }}</td>
                            <td>${{ number_format($s->precio, 2) }}</td>
                            <td>${{ number_format($s->subtotal, 2) }}</td>
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
            <div class="card-header fw-semibold bg-light">Productos utilizados</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light"><tr><th>Producto</th><th>Cantidad</th><th>Precio unitario</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        @forelse($ordene->productos as $p)
                        <tr>
                            <td>{{ $p->producto->nombre }}</td>
                            <td>{{ $p->cantidad }}</td>
                            <td>${{ number_format($p->precio_unitario, 2) }}</td>
                            <td>${{ number_format($p->subtotal, 2) }}</td>
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
