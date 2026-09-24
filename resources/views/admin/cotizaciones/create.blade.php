@extends('layouts.admin')
@section('title', 'Nueva Cotización')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-slate-900">Crear Cotización</h1>
    <a href="{{ route('admin.cotizaciones.index') }}" class="btn btn-outline">← Volver</a>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('admin.cotizaciones.store') }}" id="formCotizacion">
        @csrf

        {{-- Sección 1: Cliente y Vehículo --}}
        <div class="form-section">
            <h3 class="form-section-title">Información de Cliente</h3>
            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Cliente <span class="required">*</span></label>
                    <select name="id_cliente" class="form-control" required id="selectCliente">
                        <option value="">Seleccione cliente...</option>
                        @foreach($clientes as $c)
                            <option value="{{ $c->id_cliente }}" data-id="{{ $c->id_cliente }}">{{ $c->usuario->nombre }}</option>
                        @endforeach
                    </select>
                    @error('id_cliente')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Vehículo</label>
                    <select name="id_vehiculo" class="form-control" id="selectVehiculo">
                        <option value="">Seleccione vehículo...</option>
                        @foreach($vehiculos as $v)
                            <option value="{{ $v->id_vehiculo }}" data-cliente="{{ $v->id_cliente }}">
                                {{ $v->placa }} — {{ $v->marca }} {{ $v->modelo }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_vehiculo')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Sección 2: Fechas y Observaciones --}}
        <div class="form-section">
            <h3 class="form-section-title">Detalles de la Cotización</h3>
            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label">Fecha <span class="required">*</span></label>
                    <input type="date" name="fecha" class="form-control" value="{{ old('fecha', date('Y-m-d')) }}" required>
                    @error('fecha')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Fecha de Vencimiento</label>
                    <input type="date" name="fecha_vencimiento" class="form-control" value="{{ old('fecha_vencimiento') }}">
                    @error('fecha_vencimiento')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Observaciones</label>
                    <input type="text" name="observaciones" class="form-control" placeholder="Notas adicionales" value="{{ old('observaciones') }}">
                    @error('observaciones')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Sección 3: Servicios --}}
        <div class="form-section">
            <div class="flex items-center justify-between mb-4">
                <h3 class="form-section-title">Servicios</h3>
                <button type="button" class="btn btn-sm btn-accent" id="addServicio">+ Agregar Servicio</button>
            </div>
            
            <div id="serviciosContainer" class="space-y-3">
                <div class="servicio-row grid grid-cols-5 gap-3 bg-slate-50 p-4 rounded-lg">
                    <div>
                        <label class="form-label text-xs">Servicio</label>
                        <select name="servicios[0][id]" class="form-control form-control-sm servicio-select">
                            <option value="">Seleccione...</option>
                            @foreach($servicios as $s)
                                <option value="{{ $s->id_servicio }}" data-precio="{{ $s->precio_base }}">{{ $s->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label text-xs">Cantidad</label>
                        <input type="number" name="servicios[0][cantidad]" class="form-control form-control-sm" placeholder="0" min="1" value="1">
                    </div>
                    <div>
                        <label class="form-label text-xs">Precio</label>
                        <input type="number" name="servicios[0][precio]" class="form-control form-control-sm" placeholder="0.00" step="0.01">
                    </div>
                    <div>
                        <label class="form-label text-xs">Subtotal</label>
                        <input type="number" name="servicios[0][subtotal]" class="form-control form-control-sm" placeholder="0.00" step="0.01" readonly style="background:#f1f5f9;">
                    </div>
                    <div class="flex items-end">
                        <button type="button" onclick="removeServicio(this)" class="btn btn-sm btn-danger w-full">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sección 4: Totales --}}
        <div class="form-section">
            <h3 class="form-section-title">Resumen de Cotización</h3>
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <label class="form-label text-xs text-blue-700">Subtotal</label>
                    <input type="number" name="subtotal" id="subtotal" class="form-control text-lg font-bold text-blue-900" step="0.01" value="0" readonly style="background:#f0f9ff; border:1px solid #bfdbfe;">
                </div>
                <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                    <label class="form-label text-xs text-amber-700">Impuesto (19%)</label>
                    <input type="number" name="impuesto" id="impuesto" class="form-control text-lg font-bold text-amber-900" step="0.01" value="0" readonly style="background:#fffbeb; border:1px solid #fcd34d;">
                </div>
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <label class="form-label text-xs text-green-700">Total</label>
                    <input type="number" name="total" id="total" class="form-control text-lg font-bold text-green-900" step="0.01" value="0" readonly style="background:#f0fdf4; border:1px solid #86efac;">
                </div>
            </div>
        </div>

        {{-- Botones de Acción --}}
        <div class="flex gap-3 pt-4 mt-6 border-t border-slate-200">
            <button type="submit" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Generar Cotización
            </button>
            <a href="{{ route('admin.cotizaciones.index') }}" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>


@push('scripts')
<script>
let servicioIdx = 1;

document.getElementById('addServicio').addEventListener('click', () => {
    const template = document.querySelector('.servicio-row').cloneNode(true);
    template.querySelectorAll('input, select').forEach(el => {
        el.name = el.name.replace(/\[\d+\]/, `[${servicioIdx}]`);
        el.value = '';
    });
    document.getElementById('serviciosContainer').appendChild(template);
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
    const cant = parseFloat(row.querySelector('[name*="cantidad"]').value) || 0;
    const prec = parseFloat(row.querySelector('[name*="precio"]').value) || 0;
    const sub = cant * prec;
    row.querySelector('[name*="subtotal"]').value = sub.toFixed(2);
    recalcularTotal();
}

function removeServicio(btn) {
    btn.closest('.servicio-row').remove();
    recalcularTotal();
}

function recalcularTotal() {
    let sub = 0;
    document.querySelectorAll('.servicio-row [name*="[subtotal]"]').forEach(el => {
        sub += parseFloat(el.value) || 0;
    });
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
