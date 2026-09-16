@extends('layouts.admin')
@section('title', $producto->nombre)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">{{ $producto->nombre }}</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.productos.edit', $producto) }}" class="btn btn-sm btn-outline-primary">Editar</a>
        @if($producto->activo)
        <form method="POST" action="{{ route('admin.productos.desactivar', $producto) }}">
            @csrf @method('PATCH')
            <button class="btn btn-sm btn-outline-warning" onclick="return confirm('¿Desactivar este producto?')">Desactivar</button>
        </form>
        @endif
        <a href="{{ route('admin.productos.index') }}" class="btn btn-sm btn-outline-secondary">← Volver</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        @if($producto->fotos->isNotEmpty())
            <div id="galeriaFotos" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner rounded">
                    @foreach($producto->fotos as $i => $foto)
                        <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $foto->ruta_foto) }}" class="d-block w-100" style="height:260px;object-fit:cover;">
                        </div>
                    @endforeach
                </div>
                @if($producto->fotos->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#galeriaFotos" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#galeriaFotos" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                @endif
            </div>
        @else
            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height:260px;">
                <i class="bi bi-box-seam fs-1 text-muted"></i>
            </div>
        @endif
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-4">Código</dt><dd class="col-8">{{ $producto->codigo ?? '—' }}</dd>
                    <dt class="col-4">Categoría</dt><dd class="col-8">{{ $producto->categoria->nombre }}</dd>
                    <dt class="col-4">Marca</dt><dd class="col-8">{{ $producto->marca ?? '—' }}</dd>
                    <dt class="col-4">Proveedor</dt><dd class="col-8">{{ $producto->proveedor?->nombre ?? '—' }}</dd>
                    <dt class="col-4">Precio venta</dt><dd class="col-8">${{ number_format($producto->precio_venta,2) }}</dd>
                    <dt class="col-4">Stock actual</dt>
                    <dd class="col-8">
                        {{ $producto->inventario?->cantidad ?? 0 }}
                        @if($producto->inventario?->tieneStockBajo())
                            <span class="badge bg-warning text-dark ms-1">Bajo stock</span>
                        @endif
                    </dd>
                    <dt class="col-4">Stock mínimo</dt><dd class="col-8">{{ $producto->stock_minimo }}</dd>
                    <dt class="col-4">Estado</dt>
                    <dd class="col-8"><span class="badge {{ $producto->activo ? 'bg-success' : 'bg-secondary' }}">{{ $producto->activo ? 'Activo' : 'Inactivo' }}</span></dd>
                    <dt class="col-4">Descripción</dt><dd class="col-8">{{ $producto->descripcion ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
