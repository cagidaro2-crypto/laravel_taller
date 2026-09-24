@extends('layouts.tecnico')
@section('title', 'Generar Factura para Cliente')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Generar Factura</h1>
            <p class="text-slate-600 mt-1 text-sm">Genera una factura para el cliente y notifícala automáticamente</p>
        </div>
        <a href="{{ route('tecnico.facturas.index') }}" class="text-slate-500 hover:text-slate-800 text-sm flex items-center gap-1 font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Volver
        </a>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl text-sm">
            <p class="font-bold">Corrige los errores antes de continuar:</p>
            <ul class="list-disc list-inside mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8">
        <form method="POST" action="{{ route('tecnico.facturas.store') }}" class="space-y-6">
            @csrf

            <!-- Cliente -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Cliente *</label>
                <select name="id_cliente" id="select_cliente" required 
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="">Selecciona un cliente</option>
                    @foreach($clientes as $cli)
                        <option value="{{ $cli->id_cliente }}" {{ old('id_cliente') == $cli->id_cliente ? 'selected' : '' }}>
                            {{ $cli->usuario->nombre }} (Doc: {{ $cli->documento ?? 'S/D' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Orden de Trabajo Asociada (Opcional) -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Orden de Trabajo (Opcional)</label>
                <select name="id_orden" id="select_orden"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="">Ninguna / Factura Directa</option>
                    @foreach($ordenes as $ord)
                        <option value="{{ $ord->id_orden }}" 
                                data-cliente="{{ $ord->vehiculo?->id_cliente }}"
                                data-total="{{ $ord->total > 0 ? $ord->total : $ord->cuota_reparos }}"
                                {{ old('id_orden') == $ord->id_orden ? 'selected' : '' }}>
                            Orden #{{ $ord->id_orden }} — {{ $ord->vehiculo?->placa }} ({{ $ord->vehiculo?->cliente?->usuario?->nombre }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 mt-1">Si seleccionas una orden, se vincularán los servicios y productos realizados.</p>
            </div>

            <!-- Subtotal -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Monto Subtotal ($) *</label>
                <input type="number" step="0.01" min="0" name="subtotal" id="input_subtotal" value="{{ old('subtotal', 0) }}" required
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 font-mono text-base font-bold">
            </div>

            <!-- Previsualización de Totales -->
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 space-y-2 text-sm">
                <div class="flex justify-between text-slate-600">
                    <span>Subtotal:</span>
                    <span id="preview_subtotal" class="font-semibold font-mono">$0.00</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>IVA (19%):</span>
                    <span id="preview_impuesto" class="font-semibold font-mono">$0.00</span>
                </div>
                <div class="border-t border-slate-200 pt-2 flex justify-between font-bold text-slate-900 text-base">
                    <span>Total a Facturar:</span>
                    <span id="preview_total" class="font-mono text-indigo-600 font-black">$0.00</span>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" 
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition shadow-sm text-sm">
                    Generar y Notificar al Cliente
                </button>
                <a href="{{ route('tecnico.facturas.index') }}" 
                   class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-5 py-3 rounded-xl transition text-sm">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const subtotalInput = document.getElementById('input_subtotal');
    const previewSubtotal = document.getElementById('preview_subtotal');
    const previewImpuesto = document.getElementById('preview_impuesto');
    const previewTotal = document.getElementById('preview_total');
    const selectOrden = document.getElementById('select_orden');
    const selectCliente = document.getElementById('select_cliente');

    function updatePreview() {
        const subtotal = parseFloat(subtotalInput.value) || 0;
        const impuesto = subtotal * 0.19;
        const total = subtotal + impuesto;

        previewSubtotal.innerText = '$' + subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        previewImpuesto.innerText = '$' + impuesto.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        previewTotal.innerText = '$' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    subtotalInput.addEventListener('input', updatePreview);

    selectOrden.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const clienteId = selectedOption.getAttribute('data-cliente');
        const total = parseFloat(selectedOption.getAttribute('data-total')) || 0;

        if (clienteId && selectCliente) {
            selectCliente.value = clienteId;
        }

        if (total > 0) {
            // subtotal aprox sin IVA
            const sub = Math.round((total / 1.19) * 100) / 100;
            subtotalInput.value = sub;
            updatePreview();
        }
    });

    updatePreview();
</script>
@endsection
