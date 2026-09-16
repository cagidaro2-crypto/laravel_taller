@extends('layouts.cliente')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">{{ $vehiculo->placa }}</h1>
            <p class="text-slate-600">{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('cliente.vehiculos.edit', $vehiculo) }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 font-semibold">Editar</a>
            <a href="{{ route('cliente.vehiculos.index') }}" class="bg-slate-500 text-white px-4 py-2 rounded-lg hover:bg-slate-600">Volver</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Información del Vehículo -->
        <div class="md:col-span-2 bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-4">Información del Vehículo</h2>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-slate-600 text-sm">Placa</p>
                    <p class="font-semibold text-slate-900">{{ $vehiculo->placa }}</p>
                </div>
                <div>
                    <p class="text-slate-600 text-sm">Marca</p>
                    <p class="font-semibold text-slate-900">{{ $vehiculo->marca }}</p>
                </div>
                <div>
                    <p class="text-slate-600 text-sm">Modelo</p>
                    <p class="font-semibold text-slate-900">{{ $vehiculo->modelo }}</p>
                </div>
                <div>
                    <p class="text-slate-600 text-sm">Año</p>
                    <p class="font-semibold text-slate-900">{{ $vehiculo->anio ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-slate-600 text-sm">Color</p>
                    <p class="font-semibold text-slate-900">{{ $vehiculo->color ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-slate-600 text-sm">Tipo</p>
                    <p class="font-semibold text-slate-900">{{ $vehiculo->tipo ?? 'N/A' }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-slate-600 text-sm">VIN</p>
                    <p class="font-semibold text-slate-900">{{ $vehiculo->vin ?? 'No registrado' }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-slate-600 text-sm">Estado</p>
                    <p class="font-semibold">
                        <span class="px-3 py-1 rounded-full text-xs bg-orange-100 text-orange-800">
                            {{ $vehiculo->estado->nombre ?? 'Activo' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Galería de Fotos -->
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-4">Fotos del Vehículo</h2>
            
            @if($vehiculo->fotos->isEmpty())
                <div class="bg-slate-50 rounded-lg p-4 text-center mb-4">
                    <p class="text-slate-600 text-sm">No hay fotos subidas</p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach($vehiculo->fotos as $foto)
                        <img src="{{ asset('storage/' . $foto->ruta_foto) }}" alt="Foto" class="w-full h-32 object-cover rounded-lg">
                    @endforeach
                </div>
            @endif

            <form action="{{ route('cliente.vehiculos.foto', $vehiculo) }}" method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Subir Foto</label>
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
                <button type="submit" class="w-full mt-2 bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 font-semibold">
                    Subir
                </button>
            </form>
        </div>
    </div>

    <!-- Historial de Órdenes -->
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold text-slate-900 mb-4">Historial de Órdenes</h2>
        
        @if($vehiculo->ordenesTrabajo->isEmpty())
            <p class="text-slate-600">No hay órdenes registradas para este vehículo</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-100 border-b">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900">#Orden</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900">Fecha</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900">Estado</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900">Descripción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($vehiculo->ordenesTrabajo as $orden)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-3 font-semibold text-slate-900">#{{ $orden->id_orden }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $orden->fecha_ingreso->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                        {{ $orden->estado->nombre ?? 'Pendiente' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-600 text-sm">{{ Str::limit($orden->descripcion_problema, 50) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
