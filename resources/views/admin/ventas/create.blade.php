@extends('layouts.admin')
@section('title','Nueva Venta')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.ventas.index') }}" class="text-slate-600 hover:text-slate-800 text-sm font-medium">← Volver</a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    <h2 class="text-xl font-bold text-slate-800 mb-6">Registrar Nueva Venta</h2>

    <form action="{{ route('admin.ventas.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Datos generales --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Cliente <span class="text-red-500">*</span></label>
                <select name="id_cliente" id="selectCliente" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 @error('id_cliente') border-red-500 @enderror" required>
                    <option value="">Seleccione cliente...</option>
                    @foreach($clientes as $c)
                        <option value="{{ $c->id_cliente }}" {{ old('id_cliente') == $c->id_cliente ? 'selected' : '' }}>{{ $c->usuario->nombre }}</option>
                    @endforeach
                </select>
                @error('id_cliente') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Fecha <span class="text-red-500">*</span></label>
                <input type="date" name="fecha" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" value="{{ old('fecha', date('Y-m-d')) }}" required>
                @error('fecha') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Tabla de productos --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-3">Productos <span class="text-red-500">*</span></label>
            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-200">
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Producto</th>
                            <th class="px-4 py-3 text-center font-semibold text-slate-700 w-24">Cantidad</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700 w-32">P. Unitario</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700 w-32">Subtotal</th>
                            <th class="px-4 py-3 text-center w-12"></th>
                        </tr>
                    </thead>
                    <tbody id="itemsContainer">
                        <tr class="border-b border-slate-200 item-row">
                            <td class="px-4 py-3">
                                <select name="items[0][id_producto]" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm product-select" required>
                                    <option value="">Seleccione producto...</option>
                                    @foreach($productos as $p)
                                        <option value="{{ $p->id_producto }}" data-precio="{{ $p->precio_venta }}" data-stock="{{ $p->inventario?->cantidad ?? 0 }}">
                                            {{ $p->nombre }} (Stock: {{ $p->inventario?->cantidad ?? 0 }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" name="items[0][cantidad]" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-center cantidad-input" min="1" value="1" required>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <input type="number" name="items[0][precio]" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-right precio-input" step="0.01" readonly>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold">
                                <input type="number" name="items[0][subtotal]" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-right subtotal-input" step="0.01" readonly>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button type="button" class="text-red-600 hover:text-red-800 font-semibold remove-item">×</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button type="button" id="addItem" class="mt-3 text-sm text-orange-600 font-semibold hover:text-orange-700">+ Agregar producto</button>
            @error('items') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
        </div>

        {{-- Totales --}}
        <div class="flex justify-end">
            <div class="w-full md:w-80 space-y-3">
                <div class="flex justify-between py-2 border-b border-slate-200">
                    <span class="text-slate-700 font-medium">Subtotal:</span>
                    <span class="font-semibold">$<span id="totalSubtotal">0.00</span></span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-200">
                    <span class="text-slate-700 font-medium">Impuesto (19%):</span>
                    <span class="font-semibold">$<span id="totalImpuesto">0.00</span></span>
                </div>
                <div class="flex justify-between py-3 bg-orange-50 px-4 rounded-lg">
                    <span class="text-slate-800 font-bold text-lg">Total:</span>
                    <span class="font-bold text-lg text-orange-600">$<span id="totalFinal">0.00</span></span>
                </div>
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="bg-orange-500 text-white text-sm font-semibold px-6 py-2.5 rounded-xl hover:bg-orange-600 transition-colors">Registrar Venta</button>
            <a href="{{ route('admin.ventas.index') }}" class="bg-slate-200 text-slate-700 text-sm font-semibold px-6 py-2.5 rounded-xl hover:bg-slate-300 transition-colors">Cancelar</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
let itemIdx = 1;

document.getElementById('addItem').addEventListener('click', () => {
    const container = document.getElementById('itemsContainer');
    const template = container.querySelector('.item-row').cloneNode(true);
    
    template.querySelectorAll('[name]').forEach(el => {
        el.name = el.name.replace(/\[\d+\]/, `[${itemIdx}]`);
        if (el.type !== 'button') el.value = '';
    });
    
    container.appendChild(template);
    attachItemListeners(template);
    itemIdx++;
});

function attachItemListeners(row) {
    const select = row.querySelector('.product-select');
    const cantidad = row.querySelector('.cantidad-input');
    const precioInput = row.querySelector('.precio-input');
    const removeBtn = row.querySelector('.remove-item');
    
    select.addEventListener('change', () => {
        const option = select.selectedOptions[0];
        precioInput.value = option.dataset.precio || 0;
        calcularSubtotal(row);
    });
    
    cantidad.addEventListener('input', () => calcularSubtotal(row));
    removeBtn.addEventListener('click', () => row.remove() || recalcularTotal());
}

function calcularSubtotal(row) {
    const cantidad = parseFloat(row.querySelector('.cantidad-input').value) || 0;
    const precio = parseFloat(row.querySelector('.precio-input').value) || 0;
    const subtotal = cantidad * precio;
    row.querySelector('.subtotal-input').value = subtotal.toFixed(2);
    recalcularTotal();
}

function recalcularTotal() {
    let subtotal = 0;
    document.querySelectorAll('.subtotal-input').forEach(el => {
        subtotal += parseFloat(el.value) || 0;
    });
    const impuesto = subtotal * 0.19;
    const total = subtotal + impuesto;
    
    document.getElementById('totalSubtotal').textContent = subtotal.toFixed(2);
    document.getElementById('totalImpuesto').textContent = impuesto.toFixed(2);
    document.getElementById('totalFinal').textContent = total.toFixed(2);
}

// Inicializar listeners en la primera fila
document.querySelectorAll('.item-row').forEach(row => attachItemListeners(row));
recalcularTotal();
</script>
@endpush
@endsection
