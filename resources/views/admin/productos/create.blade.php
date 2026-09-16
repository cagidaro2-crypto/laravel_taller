@extends('layouts.admin')
@section('title', 'Agregar Producto')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Agregar producto</h5>
    <a href="{{ route('admin.productos.index') }}" class="btn btn-outline-secondary btn-sm">← Volver</a>
</div>
<div class="card shadow-sm border-0" style="max-width:700px">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.productos.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Código</label>
                    <input type="text" name="codigo" class="form-control" value="{{ old('codigo') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Categoría <span class="text-danger">*</span></label>
                    <select name="id_categoria" class="form-select @error('id_categoria') is-invalid @enderror" required>
                        <option value="">Seleccione...</option>
                        @foreach($categorias as $c)
                            <option value="{{ $c->id_categoria }}" {{ old('id_categoria') == $c->id_categoria ? 'selected' : '' }}>{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Proveedor</label>
                    <select name="id_proveedor" class="form-select">
                        <option value="">Sin proveedor</option>
                        @foreach($proveedores as $p)
                            <option value="{{ $p->id_proveedor }}" {{ old('id_proveedor') == $p->id_proveedor ? 'selected' : '' }}>{{ $p->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Marca</label>
                    <input type="text" name="marca" class="form-control" value="{{ old('marca') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Unidad de medida</label>
                    <input type="text" name="unidad_medida" class="form-control" value="{{ old('unidad_medida', 'unidad') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Stock mínimo <span class="text-danger">*</span></label>
                    <input type="number" name="stock_minimo" class="form-control" value="{{ old('stock_minimo', 5) }}" min="0" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Stock inicial</label>
                    <input type="number" name="stock_inicial" class="form-control" value="{{ old('stock_inicial', 0) }}" min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Precio de compra</label>
                    <input type="number" name="precio_compra" class="form-control" value="{{ old('precio_compra') }}" step="0.01" min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Precio de venta <span class="text-danger">*</span></label>
                    <input type="number" name="precio_venta" class="form-control @error('precio_venta') is-invalid @enderror" value="{{ old('precio_venta') }}" step="0.01" min="0" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="2">{{ old('descripcion') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Foto del producto</label>
                    <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/jpg,image/jpeg,image/png">
                    @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection
