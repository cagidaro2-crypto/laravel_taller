@extends('layouts.admin')
@section('title', 'Nueva Cotización')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Crear cotización</h5>
    <a href="{{ route('admin.cotizaciones.index') }}" class="btn btn-outline-secondary btn-sm">← Volver</a>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.cotizaciones.store') }}" id="formCotizacion">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Cliente <span class="text-danger">*</span></label>
                    <select name="id_cliente" class="form-select" required id="selectCliente">
                        <option value="">Seleccione cliente...</option>
                        @foreach($clientes as $c)
                            <option value="{{ $c->id_cliente }}" data-id="{{ $c->id_cliente }}">{{ $c->usuario->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Vehículo</label>
                    <select name="id_vehiculo" class="form-select" id="selectVehiculo">
                        <option value="">Seleccione vehículo...</option>
                        @foreach($vehiculos as $v)
                            <option value="{{ $v->id_vehiculo }}" data-cliente="{{ $v->id_cliente }}">
                                {{ $v->placa }} — {{ $v->marca }} {{ $v->modelo }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fecha <span class="text-danger">*</span></label>
                    <input type="date" name="fecha" class="form-control" value="{{ old('fecha', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fecha de vencimiento</label>
                    <input type="date" name="fecha_vencimiento" class="form-control" value="{{ old('fecha_vencimiento') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Observaciones</label>
                    <input type="text" name="observaciones" class="form-control" value="{{ old('observaciones') }}">
                </div>

                {{-- Servicios --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Servicios</label>
                    <div id="serviciosContainer">
                        <div class="row g-2 mb-2 servicio-row">
                            <div class="col-md-5">
                                <select name="servicios[0][id]" class="form-select form-select-sm servicio-select">
                                    <option value="">Seleccione servicio...</option>
                                    @foreach($servicios as $s)
                                        <option value="{{ $s->id_servicio }}" data-precio="{{ $s->precio_base }}">{{ $s->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="servicios[0][cantidad]" class="form-control form-control-sm" placeholder="Cant." min="1" value="1">
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="servicios[0][precio]" class="form-control form-control-sm" placeholder="Precio" step="0.01">
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="servicios[0][subtotal]" class="form-control form-control-sm" placeholder="Subtotal" step="0.01" readonly>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="addServicio">+ Agregar servicio</button>
                </div>

                {{-- Totales --}}
                <div class="col-md-4 offset-md-8">
                    <label class="form-label fw-semibold">Subtotal</label>
                    <input type="number" name="subtotal" id="subtotal" class="form-control" step="0.01" value="0" readonly>
                    <label class="form-label fw-semibold mt-2">Impuesto (19%)</label>
                    <input type="number" name="impuesto" id="impuesto" class="form-control" step="0.01" value="0" readonly>
                    <label class="form-label fw-semibold mt-2">Total</label>
                    <input type="number" name="total" id="total" class="form-control fw-bold" step="0.01" value="0" readonly>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Generar cotización</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let servicioIdx = 1;
document.getElementById('addServicio').addEventListener('click', () => {
    const c = document.querySelector('.servicio-row').cloneNode(true);
    c.querySelectorAll('input,select').forEach(el => {
        el.name = el.name.replace(/\[\d+\]/, `[${servicioIdx}]`);
        el.value = '';
    });
    document.getElementById('serviciosContainer').appendChild(c);
    servicioIdx++;
});

document.addEventListener('change', e => {
    if (e.target.classList.contains('servicio-select')) {
        const row = e.target.closest('.servicio-row');
        const precio = e.target.selectedOptions[0]?.dataset.precio ?? 0;
        row.querySelector('[name*="precio"]').value = precio;
        calcularSubtotal(row);
    }
});
document.addEventListener('input', e => {
    const row = e.target.closest('.servicio-row');
    if (row) calcularSubtotal(row);
});

function calcularSubtotal(row) {
    const cant  = parseFloat(row.querySelector('[name*="cantidad"]').value) || 0;
    const prec  = parseFloat(row.querySelector('[name*="precio"]').value) || 0;
    const sub   = cant * prec;
    row.querySelector('[name*="subtotal"]').value = sub.toFixed(2);
    recalcularTotal();
}

function recalcularTotal() {
    let sub = 0;
    document.querySelectorAll('[name*="subtotal"]').forEach(el => {
        if (!el.name.includes('subtotal') || el.readOnly) return;
        sub += parseFloat(el.value) || 0;
    });
    // Re-sum only service rows
    sub = 0;
    document.querySelectorAll('.servicio-row [name*="[subtotal]"]').forEach(el => sub += parseFloat(el.value) || 0);
    const imp = sub * 0.19;
    document.getElementById('subtotal').value = sub.toFixed(2);
    document.getElementById('impuesto').value = imp.toFixed(2);
    document.getElementById('total').value = (sub + imp).toFixed(2);
}

// Filtrar vehículos por cliente
document.getElementById('selectCliente').addEventListener('change', function() {
    const clienteId = this.value;
    document.querySelectorAll('#selectVehiculo option').forEach(opt => {
        if (!opt.value) return;
        opt.hidden = opt.dataset.cliente != clienteId;
    });
    document.getElementById('selectVehiculo').value = '';
});
</script>
@endpush
