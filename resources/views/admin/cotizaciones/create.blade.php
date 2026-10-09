@extends('layouts.admin')
@section('title', 'Nueva Cotización')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Crear Cotización</h1>
        <p class="text-sm text-slate-500 mt-1">Genera una propuesta de servicios y costos para el cliente</p>
    </div>
    <a href="{{ route('admin.cotizaciones.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 transition shadow-sm self-start sm:self-auto">
        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Volver
    </a>
</div>

<form method="POST" action="{{ route('admin.cotizaciones.store') }}" id="formCotizacion" class="space-y-6 max-w-5xl mx-auto">
    @csrf

    {{-- Sección 1: Cliente y Vehículo --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-7">
        <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-slate-100">
            <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7 7z"/></svg>
            </div>
            <h2 class="text-base font-bold text-slate-800">Información del Cliente y Vehículo</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Cliente <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <select name="id_cliente" required id="selectCliente" class="w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-2.5 text-sm text-slate-800 transition outline-none shadow-sm cursor-pointer @error('id_cliente') border-rose-400 bg-rose-50/30 @enderror">
                        <option value="">Seleccione cliente...</option>
                        @foreach($clientes as $c)
                            <option value="{{ $c->id_cliente }}" data-id="{{ $c->id_cliente }}" {{ old('id_cliente') == $c->id_cliente ? 'selected' : '' }}>{{ $c->usuario->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                @error('id_cliente')
                    <p class="text-rose-500 text-xs mt-1.5 font-medium flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Vehículo <span class="text-slate-400 font-normal">(Opcional)</span></label>
                <div class="relative">
                    <select name="id_vehiculo" id="selectVehiculo" class="w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-2.5 text-sm text-slate-800 transition outline-none shadow-sm cursor-pointer @error('id_vehiculo') border-rose-400 bg-rose-50/30 @enderror">
                        <option value="">Seleccione vehículo...</option>
                        @foreach($vehiculos as $v)
                            <option value="{{ $v->id_vehiculo }}" data-cliente="{{ $v->id_cliente }}" {{ old('id_vehiculo') == $v->id_vehiculo ? 'selected' : '' }}>
                                {{ $v->placa }} — {{ $v->marca }} {{ $v->modelo }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('id_vehiculo')
                    <p class="text-rose-500 text-xs mt-1.5 font-medium flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Sección 2: Fechas y Observaciones --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-7">
        <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-slate-100">
            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h2 class="text-base font-bold text-slate-800">Detalles y Plazos</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Fecha de Emisión <span class="text-rose-500">*</span></label>
                <input type="date" name="fecha" class="w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-2.5 text-sm text-slate-800 transition outline-none shadow-sm @error('fecha') border-rose-400 bg-rose-50/30 @enderror" value="{{ old('fecha', date('Y-m-d')) }}" required>
                @error('fecha')
                    <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Fecha de Vencimiento</label>
                <input type="date" name="fecha_vencimiento" class="w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-2.5 text-sm text-slate-800 transition outline-none shadow-sm @error('fecha_vencimiento') border-rose-400 bg-rose-50/30 @enderror" value="{{ old('fecha_vencimiento') }}">
                @error('fecha_vencimiento')
                    <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Observaciones</label>
                <input type="text" name="observaciones" class="w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-2.5 text-sm text-slate-800 transition outline-none shadow-sm" placeholder="Notas o términos adicionales..." value="{{ old('observaciones') }}">
                @error('observaciones')
                    <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Sección 3: Servicios --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-7">
        <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-800">Servicios a Cotizar</h2>
                    <p class="text-xs text-slate-500">Agrega los ítems que conformarán el presupuesto</p>
                </div>
            </div>
            <button type="button" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white text-xs font-bold shadow-md shadow-orange-500/20 transition cursor-pointer" id="addServicio">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Agregar Servicio
            </button>
        </div>

        <div id="serviciosContainer" class="space-y-3">
            <div class="servicio-row grid grid-cols-12 gap-3 p-4 bg-slate-50/90 border border-slate-200/90 rounded-2xl hover:border-slate-300 transition items-end">
                <div class="col-span-12 sm:col-span-5">
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Servicio</label>
                    <select name="servicios[0][id]" class="w-full bg-white border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-3.5 py-2 text-sm text-slate-800 transition outline-none servicio-select cursor-pointer">
                        <option value="">Seleccione servicio...</option>
                        @foreach($servicios as $s)
                            <option value="{{ $s->id_servicio }}" data-precio="{{ $s->precio_base }}">{{ $s->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-6 sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Cantidad</label>
                    <input type="number" name="servicios[0][cantidad]" class="w-full bg-white border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-3 py-2 text-sm text-slate-800 transition outline-none text-center font-medium" placeholder="1" min="1" value="1">
                </div>
                <div class="col-span-6 sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Precio Unit.</label>
                    <input type="number" name="servicios[0][precio]" class="w-full bg-white border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-3 py-2 text-sm text-slate-800 transition outline-none text-right font-medium" placeholder="0.00" step="0.01">
                </div>
                <div class="col-span-8 sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Subtotal</label>
                    <input type="number" name="servicios[0][subtotal]" class="w-full bg-slate-100/80 border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-700 text-right cursor-not-allowed outline-none" placeholder="0.00" step="0.01" readonly>
                </div>
                <div class="col-span-4 sm:col-span-1">
                    <button type="button" onclick="removeServicio(this)" class="w-full py-2.5 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white font-semibold text-xs transition border border-rose-200/80 flex items-center justify-center gap-1 shadow-sm cursor-pointer" title="Eliminar ítem">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Sección 4: Totales --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-7">
        <h2 class="text-base font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Resumen Económico</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50/50 p-4 rounded-2xl border border-blue-200/80">
                <span class="block text-xs font-bold text-blue-700 uppercase tracking-wider mb-1.5">Subtotal</span>
                <input type="number" name="subtotal" id="subtotal" class="w-full bg-transparent text-2xl font-black text-blue-900 border-0 p-0 outline-none" step="0.01" value="0" readonly>
            </div>
            <div class="bg-gradient-to-br from-amber-50 to-orange-50/50 p-4 rounded-2xl border border-amber-200/80">
                <span class="block text-xs font-bold text-amber-700 uppercase tracking-wider mb-1.5">IVA (19%)</span>
                <input type="number" name="impuesto" id="impuesto" class="w-full bg-transparent text-2xl font-black text-amber-900 border-0 p-0 outline-none" step="0.01" value="0" readonly>
            </div>
            <div class="bg-gradient-to-br from-emerald-50 to-teal-50/50 p-4 rounded-2xl border border-emerald-200/80">
                <span class="block text-xs font-bold text-emerald-700 uppercase tracking-wider mb-1.5">Total Estimado</span>
                <input type="number" name="total" id="total" class="w-full bg-transparent text-2xl font-black text-emerald-900 border-0 p-0 outline-none" step="0.01" value="0" readonly>
            </div>
        </div>
    </div>

    {{-- Botones de Acción --}}
    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-bold px-7 py-3 rounded-xl shadow-lg shadow-orange-500/25 transition cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            Generar Cotización
        </button>
        <a href="{{ route('admin.cotizaciones.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
            Cancelar
        </a>
    </div>
</form>


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
