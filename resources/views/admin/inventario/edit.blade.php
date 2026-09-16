@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-900">Actualizar Inventario</h1>
        <p class="text-slate-600 mt-2">{{ $inventario->producto->nombre }}</p>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form action="{{ route('admin.inventario.update', $inventario) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="space-y-6">
                <!-- Información del Producto -->
                <div class="bg-slate-50 rounded-lg p-4">
                    <h3 class="font-semibold text-slate-900 mb-3">Información del Producto</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-slate-600">Nombre</p>
                            <p class="font-semibold text-slate-900">{{ $inventario->producto->nombre }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-600">Categoría</p>
                            <p class="font-semibold text-slate-900">{{ $inventario->producto->categoria->nombre ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-600">Stock Actual</p>
                            <p class="font-semibold text-slate-900">{{ $inventario->cantidad }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-600">Stock Mínimo</p>
                            <p class="font-semibold text-slate-900">{{ $inventario->stock_minimo }}</p>
                        </div>
                    </div>
                </div>

                <!-- Nueva Cantidad -->
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Nueva Cantidad *</label>
                    <input 
                        type="number" 
                        name="cantidad"
                        value="{{ old('cantidad', $inventario->cantidad) }}"
                        min="0"
                        required
                        class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-400 outline-none @error('cantidad') border-red-500 @enderror"
                    >
                    @error('cantidad')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-slate-600 text-sm mt-2">
                        <strong>Cambio:</strong> {{ old('cantidad', $inventario->cantidad) - $inventario->cantidad }} unidades
                    </p>
                </div>

                <!-- Motivo -->
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Motivo del Ajuste *</label>
                    <select 
                        name="motivo"
                        required
                        class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-400 outline-none @error('motivo') border-red-500 @enderror"
                    >
                        <option value="">Selecciona un motivo</option>
                        <option value="Compra de nuevas unidades" {{ old('motivo') === 'Compra de nuevas unidades' ? 'selected' : '' }}>Compra de nuevas unidades</option>
                        <option value="Ajuste por rotura" {{ old('motivo') === 'Ajuste por rotura' ? 'selected' : '' }}>Ajuste por rotura</option>
                        <option value="Ajuste por pérdida" {{ old('motivo') === 'Ajuste por pérdida' ? 'selected' : '' }}>Ajuste por pérdida</option>
                        <option value="Devolución de proveedor" {{ old('motivo') === 'Devolución de proveedor' ? 'selected' : '' }}>Devolución de proveedor</option>
                        <option value="Corrección de inventario" {{ old('motivo') === 'Corrección de inventario' ? 'selected' : '' }}>Corrección de inventario</option>
                        <option value="Otro" {{ old('motivo') === 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                    @error('motivo')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-4 mt-8">
                <button 
                    type="submit"
                    class="bg-orange-500 text-white px-8 py-2.5 rounded-lg hover:bg-orange-600 font-semibold transition"
                >
                    Actualizar Inventario
                </button>
                <a 
                    href="{{ route('admin.inventario.index') }}"
                    class="bg-slate-300 text-slate-900 px-8 py-2.5 rounded-lg hover:bg-slate-400 font-semibold transition"
                >
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
