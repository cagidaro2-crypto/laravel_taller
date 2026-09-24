@extends('layouts.tecnico')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Materiales - Orden #{{ $ordene->id_orden }}</h1>
            <p class="text-slate-600 mt-2">Gestionar consumo de materiales en esta orden</p>
        </div>
        <a href="{{ route('tecnico.ordenes.show', $ordene) }}" class="bg-slate-500 text-white px-4 py-2 rounded-lg hover:bg-slate-600">Volver</a>
    </div>

    <!-- Información General de la Orden -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-slate-600 text-sm">Vehículo</p>
            <p class="text-lg font-bold text-slate-900">{{ $ordene->vehiculo->placa ?? 'N/A' }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-slate-600 text-sm">Cliente</p>
            <p class="text-lg font-bold text-slate-900">{{ $ordene->vehiculo->cliente->usuario->nombre ?? 'N/A' }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-slate-600 text-sm">Estado</p>
            <p class="text-lg font-bold text-slate-900">
                <span class="px-3 py-1 rounded-full text-xs bg-orange-100 text-orange-800">
                    {{ $ordene->estado->nombre ?? 'Pendiente' }}
                </span>
            </p>
        </div>
    </div>

    <!-- Alertas -->
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-800 font-semibold mb-2">Errores:</p>
            <ul class="text-red-700 text-sm list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Formulario Agregar Material -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold text-slate-900 mb-4">Agregar Material Consumido</h2>
        
        <form action="{{ route('tecnico.consumo-materiales.store', $ordene->id_orden) }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Producto -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Producto *</label>
                    <select name="id_producto" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                        <option value="">-- Selecciona un producto --</option>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id_producto }}" {{ old('id_producto') == $producto->id_producto ? 'selected' : '' }}>
                                {{ $producto->nombre }} 
                                ({{ $producto->inventario?->cantidad ?? 0 }} disponibles)
                            </option>
                        @endforeach
                    </select>
                    @error('id_producto')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cantidad -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Cantidad *</label>
                    <input type="number" name="cantidad_usada" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-orange-500" min="1" required value="{{ old('cantidad_usada', 1) }}">
                    @error('cantidad_usada')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Observaciones -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Observaciones</label>
                    <input type="text" name="observaciones" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Ej: Defecto encontrado" value="{{ old('observaciones') }}">
                </div>

                <!-- Botón Submit -->
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 font-semibold">
                        Agregar Material
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabla de Materiales Consumidos -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold text-slate-900 mb-4">Materiales Consumidos</h2>
        
        @if($ordene->consumoMateriales->isEmpty())
            <div class="bg-slate-50 rounded-lg p-6 text-center">
                <svg class="w-12 h-12 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-slate-600">No hay materiales registrados</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b-2 border-slate-300">
                            <th class="text-left px-4 py-3 font-bold text-slate-900">Producto</th>
                            <th class="text-center px-4 py-3 font-bold text-slate-900">Cantidad</th>
                            <th class="text-right px-4 py-3 font-bold text-slate-900">Precio Unit.</th>
                            <th class="text-right px-4 py-3 font-bold text-slate-900">Subtotal</th>
                            <th class="text-center px-4 py-3 font-bold text-slate-900">Fecha</th>
                            <th class="text-center px-4 py-3 font-bold text-slate-900">Obs.</th>
                            <th class="text-center px-4 py-3 font-bold text-slate-900">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ordene->consumoMateriales as $consumo)
                            <tr class="border-b border-slate-200 hover:bg-slate-50">
                                <td class="px-4 py-3">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $consumo->producto->nombre }}</p>
                                        <p class="text-xs text-slate-600">{{ $consumo->producto->codigo ?? 'N/A' }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">{{ $consumo->cantidad_usada }}</td>
                                <td class="px-4 py-3 text-right">${{ number_format($consumo->precio_unitario, 2) }}</td>
                                <td class="px-4 py-3 text-right font-semibold">${{ number_format($consumo->subtotal, 2) }}</td>
                                <td class="px-4 py-3 text-center text-xs text-slate-600">
                                    {{ $consumo->fecha_consumo->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($consumo->observaciones)
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">✓</span>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <form action="{{ route('tecnico.consumo-materiales.destroy', $consumo->id_consumo) }}" method="POST" class="inline" onsubmit="return confirm('¿Confirmas la eliminación? El stock será devuelto al inventario.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-xs">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50 border-t-2 border-slate-300 font-bold">
                            <td colspan="3" class="px-4 py-3 text-right">Total Materiales:</td>
                            <td class="px-4 py-3 text-right text-lg text-orange-600">
                                ${{ number_format($ordene->consumoMateriales->sum('subtotal'), 2) }}
                            </td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>

    <!-- Resumen de Costos -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Desglose -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Desglose de Costos</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-slate-700">Servicios:</span>
                    <span class="font-semibold text-slate-900">${{ number_format($ordene->servicios->sum('valor_unitario') ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-700">Productos:</span>
                    <span class="font-semibold text-slate-900">${{ number_format($ordene->productos->sum('subtotal') ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between border-b pb-3">
                    <span class="text-slate-700">Materiales Consumidos:</span>
                    <span class="font-semibold text-slate-900">${{ number_format($ordene->consumoMateriales->sum('subtotal') ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-700">Subtotal:</span>
                    <span class="font-semibold text-slate-900">${{ number_format($ordene->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-700">Impuesto (19%):</span>
                    <span class="font-semibold text-slate-900">${{ number_format($ordene->impuesto, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Información de Stock -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Información de Stock</h3>
            
            @if($ordene->consumoMateriales->isEmpty())
                <p class="text-slate-600 text-sm">Sin materiales registrados aún</p>
            @else
                <div class="space-y-3">
                    @foreach($ordene->consumoMateriales as $consumo)
                        <div class="border-b pb-2">
                            <p class="text-sm font-semibold text-slate-900">{{ $consumo->producto->nombre }}</p>
                            <div class="flex justify-between text-xs text-slate-600 mt-1">
                                <span>Consumido: {{ $consumo->cantidad_usada }}</span>
                                <span>Disponible: {{ $consumo->producto->inventario?->cantidad ?? 0 }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Cuota a Reparación -->
    <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-slate-600 text-sm font-medium">Cuota Total a Reparar</p>
                <p class="text-4xl font-bold text-orange-600 mt-2">
                    ${{ number_format($ordene->cuota_reparos, 2) }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate-600 mb-2">Incluye:</p>
                <ul class="text-xs text-slate-700 space-y-1">
                    <li>✓ Servicios</li>
                    <li>✓ Productos</li>
                    <li>✓ Materiales</li>
                    <li>✓ Impuesto (19%)</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    input[type="text"],
    input[type="number"],
    select {
        transition: all 0.3s ease;
    }
    
    input[type="text"]:focus,
    input[type="number"]:focus,
    select:focus {
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
    }
</style>
@endsection
