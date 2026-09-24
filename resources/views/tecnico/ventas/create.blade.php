@extends('layouts.tecnico')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('tecnico.ventas.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            Volver
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Registrar Nueva Venta</h1>
    </div>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('tecnico.ventas.store') }}" method="POST" id="ventaForm">
            @csrf

            <!-- Cliente -->
            <div class="bg-white rounded-xl shadow p-8 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Cliente *</label>
                        <select name="id_cliente" required class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <option value="">Selecciona un cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id_cliente }}" {{ old('id_cliente') == $cliente->id_cliente ? 'selected' : '' }}>
                                    {{ $cliente->usuario->nombre }} ({{ $cliente->usuario->correo ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_cliente')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Fecha de Venta *</label>
                        <input type="date" name="fecha" required class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none" value="{{ old('fecha', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}">
                        @error('fecha')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Productos & Resumen Entrelazados -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Productos (columna ancha) -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow p-8">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Productos</h3>

                    <div id="itemsContainer" class="space-y-4 mb-4">
                        @if(old('items'))
                            @foreach(old('items') as $index => $item)
                                <div class="item-row flex gap-3 bg-slate-50 p-4 rounded-lg">
                                    <select name="items[{{ $index }}][id_producto]" onchange="updateResumen()" class="flex-1 border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                                        <option value="">Selecciona producto</option>
                                        @foreach($productos as $producto)
                                            <option value="{{ $producto->id_producto }}" {{ $item['id_producto'] == $producto->id_producto ? 'selected' : '' }} data-precio="{{ $producto->precio_venta }}" data-nombre="{{ $producto->nombre }}">
                                                {{ $producto->nombre }} - ${{ number_format($producto->precio_venta, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="items[{{ $index }}][cantidad]" value="{{ $item['cantidad'] }}" min="1" placeholder="Cantidad" onchange="updateResumen()" class="w-24 border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                                    <button type="button" onclick="removeItem(this)" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition font-semibold">
                                        ✕
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="item-row flex gap-3 bg-slate-50 p-4 rounded-lg">
                                <select name="items[0][id_producto]" onchange="updateResumen()" class="flex-1 border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                                    <option value="">Selecciona producto</option>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->id_producto }}" data-precio="{{ $producto->precio_venta }}" data-nombre="{{ $producto->nombre }}">
                                            {{ $producto->nombre }} - ${{ number_format($producto->precio_venta, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="number" name="items[0][cantidad]" value="1" min="1" placeholder="Cantidad" onchange="updateResumen()" class="w-24 border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                                <button type="button" onclick="removeItem(this)" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition font-semibold">
                                    ✕
                                </button>
                            </div>
                        @endif
                    </div>

                    <button type="button" onclick="addItem()" class="w-full bg-slate-500 text-white px-4 py-2.5 rounded-lg hover:bg-slate-600 transition font-semibold">
                        + Agregar Producto
                    </button>

                    @error('items')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Resumen (columna derecha) -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow p-6 h-fit">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Resumen de Venta</h3>

                    <div id="resumenContainer" class="space-y-2 mb-4 pb-4 border-b border-blue-200">
                        <p class="text-slate-600 text-sm italic">Agregue productos para ver el resumen</p>
                    </div>

                    <div class="space-y-3 bg-white rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-700">Subtotal:</span>
                            <span class="font-semibold text-lg text-slate-900">$<span id="subtotal">0.00</span></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-700">IVA (19%):</span>
                            <span class="font-semibold text-lg text-slate-900">$<span id="impuesto">0.00</span></span>
                        </div>
                        <div class="border-t border-slate-300 pt-3 flex justify-between items-center">
                            <span class="font-bold text-slate-900">Total:</span>
                            <span class="text-3xl font-bold text-blue-600">$<span id="total">0.00</span></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-bold transition text-lg">
                    Registrar Venta
                </button>
                <a href="{{ route('tecnico.ventas.index') }}" class="bg-slate-300 text-slate-900 px-8 py-3 rounded-lg hover:bg-slate-400 font-bold transition text-lg">
                    Cancelar
                </a>
            </div>
        </form>

        @if($errors->any())
            <div class="mt-6 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-lg">
                <p class="font-bold mb-3">Por favor revisa los errores:</p>
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-sm">• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>

<script>
let itemCount = {{ old('items') ? count(old('items')) : 1 }};

function addItem() {
    const container = document.getElementById('itemsContainer');
    const newRow = document.createElement('div');
    newRow.className = 'item-row flex gap-3';
    newRow.innerHTML = `
        <select name="items[${itemCount}][id_producto]" class="flex-1 border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none" onchange="updateResumen()">
            <option value="">Selecciona producto</option>
            @foreach($productos as $producto)
                <option value="{{ $producto->id_producto }}" data-precio="{{ $producto->precio_venta }}" data-nombre="{{ $producto->nombre }}">
                    {{ $producto->nombre }} - ${{ number_format($producto->precio_venta, 2) }}
                </option>
            @endforeach
        </select>
        <input type="number" name="items[${itemCount}][cantidad]" value="1" min="1" placeholder="Cantidad" class="w-24 border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none" onchange="updateResumen()">
        <button type="button" onclick="removeItem(this)" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
            Quitar
        </button>
    `;
    container.appendChild(newRow);
    itemCount++;
    updateResumen();
}

function removeItem(btn) {
    btn.closest('.item-row').remove();
    updateResumen();
}

function updateResumen() {
    let subtotal = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const select = row.querySelector('select');
        const cantidad = row.querySelector('input[type="number"]');
        if (select.value && cantidad.value) {
            const option = select.querySelector(`option[value="${select.value}"]`);
            const precio = parseFloat(option.dataset.precio) || 0;
            const cant = parseInt(cantidad.value) || 0;
            if (precio > 0 && cant > 0) {
                subtotal += precio * cant;
            }
        }
    });

    // BUG #3: Usar tasa IVA consistente (19%)
    const IVA_RATE = 0.19;
    const impuesto = Math.round(subtotal * IVA_RATE * 100) / 100;
    const total = Math.round((subtotal + impuesto) * 100) / 100;

    document.getElementById('subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('impuesto').textContent = impuesto.toFixed(2);
    document.getElementById('total').textContent = total.toFixed(2);
}

// Inicial
updateResumen();
</script>
@endsection
