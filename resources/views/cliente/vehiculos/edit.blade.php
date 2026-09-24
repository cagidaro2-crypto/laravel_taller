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

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
            <p class="text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
            <p class="text-red-800 font-semibold">Por favor corrige los errores:</p>
            <ul class="text-red-700 text-sm mt-2">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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

            <!-- Fotos del Vehículo -->
            <div class="mt-8 pt-8 border-t border-slate-200">
                <h3 class="text-xl font-bold text-slate-900 mb-6">Fotos del Vehículo</h3>

                <!-- Galería de fotos existentes -->
                @if($vehiculo->fotos->isNotEmpty())
                    <div class="mb-6">
                        <p class="text-sm font-semibold text-slate-900 mb-3">Fotos Actuales</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($vehiculo->fotos as $foto)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $foto->ruta_foto) }}" 
                                         alt="Foto del vehículo" 
                                         class="w-full h-24 object-cover rounded-lg">
                                    @if($foto->descripcion)
                                        <p class="text-xs text-slate-600 mt-1">{{ $foto->descripcion }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="mb-6 bg-slate-50 rounded-lg p-4 text-center">
                        <p class="text-slate-600 text-sm">No hay fotos subidas aún</p>
                    </div>
                @endif

                <!-- Formulario para subir nueva foto -->
                <div class="bg-slate-50 rounded-lg p-6">
                    <p class="text-sm font-semibold text-slate-900 mb-4">Subir Nueva Foto</p>
                    <form action="{{ route('cliente.vehiculos.foto', $vehiculo) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-2">Seleccionar Foto</label>
                                <input 
                                    type="file" 
                                    name="foto"
                                    accept="image/*"
                                    class="w-full text-sm text-slate-600 file:mr-2 file:py-2 file:px-4 file:rounded file:bg-orange-500 file:text-white file:font-semibold hover:file:bg-orange-600"
                                >
                                @error('foto')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-2">Descripción (Opcional)</label>
                                <input 
                                    type="text"
                                    name="descripcion"
                                    placeholder="Ej: Frente del vehículo, Lateral derecho..."
                                    class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none"
                                >
                            </div>

                            <button type="submit" class="w-full bg-orange-500 text-white py-2.5 rounded-lg hover:bg-orange-600 font-semibold transition">
                                Subir Foto
                            </button>
                        </div>
                    </form>
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
