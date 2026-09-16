@extends('layouts.cliente')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Editar Vehículo</h1>
            <p class="text-slate-600 mt-2">Actualiza los datos de {{ $vehiculo->placa }}</p>
        </div>
        <a href="{{ route('cliente.vehiculos.show', $vehiculo) }}" class="bg-slate-500 text-white px-4 py-2 rounded-lg hover:bg-slate-600">Volver</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form action="{{ route('cliente.vehiculos.update', $vehiculo) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Placa -->
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Placa *</label>
                    <input 
                        type="text" 
                        name="placa"
                        value="{{ old('placa', $vehiculo->placa) }}"
                        placeholder="ABC-123"
                        class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none @error('placa') border-red-500 @enderror"
                    >
                    @error('placa')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Marca -->
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Marca *</label>
                    <input 
                        type="text"
                        name="marca"
                        value="{{ old('marca', $vehiculo->marca) }}"
                        placeholder="Toyota, Ford, Chevrolet..."
                        class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none @error('marca') border-red-500 @enderror"
                    >
                    @error('marca')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Modelo -->
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Modelo *</label>
                    <input 
                        type="text"
                        name="modelo"
                        value="{{ old('modelo', $vehiculo->modelo) }}"
                        placeholder="Corolla, Focus, Spark..."
                        class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none @error('modelo') border-red-500 @enderror"
                    >
                    @error('modelo')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Año -->
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Año</label>
                    <input 
                        type="number"
                        name="anio"
                        value="{{ old('anio', $vehiculo->anio) }}"
                        placeholder="2023"
                        min="1900"
                        max="{{ date('Y') + 1 }}"
                        class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none @error('anio') border-red-500 @enderror"
                    >
                    @error('anio')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Color -->
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Color</label>
                    <input 
                        type="text"
                        name="color"
                        value="{{ old('color', $vehiculo->color) }}"
                        placeholder="Rojo, Negro, Blanco..."
                        class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none"
                    >
                </div>

                <!-- Tipo -->
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Tipo</label>
                    <select 
                        name="tipo"
                        class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none"
                    >
                        <option value="">Selecciona un tipo</option>
                        <option value="Sedán" {{ old('tipo', $vehiculo->tipo) === 'Sedán' ? 'selected' : '' }}>Sedán</option>
                        <option value="SUV" {{ old('tipo', $vehiculo->tipo) === 'SUV' ? 'selected' : '' }}>SUV</option>
                        <option value="Camioneta" {{ old('tipo', $vehiculo->tipo) === 'Camioneta' ? 'selected' : '' }}>Camioneta</option>
                        <option value="Hatchback" {{ old('tipo', $vehiculo->tipo) === 'Hatchback' ? 'selected' : '' }}>Hatchback</option>
                        <option value="Otro" {{ old('tipo', $vehiculo->tipo) === 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>

                <!-- VIN -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-900 mb-2">VIN (Número de Identificación del Vehículo)</label>
                    <input 
                        type="text"
                        name="vin"
                        value="{{ old('vin', $vehiculo->vin) }}"
                        placeholder="Opcional"
                        class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none"
                    >
                </div>
            </div>

            <div class="flex gap-4 mt-8">
                <button 
                    type="submit"
                    class="bg-orange-500 text-white px-8 py-2.5 rounded-lg hover:bg-orange-600 font-semibold transition"
                >
                    Guardar Cambios
                </button>
                <a 
                    href="{{ route('cliente.vehiculos.show', $vehiculo) }}"
                    class="bg-slate-300 text-slate-900 px-8 py-2.5 rounded-lg hover:bg-slate-400 font-semibold transition"
                >
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
