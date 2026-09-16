@extends('layouts.cliente')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Mis Vehículos</h1>
            <p class="text-slate-600 mt-2">Gestiona tus vehículos registrados</p>
        </div>
        <a href="{{ route('cliente.vehiculos.create') }}" class="bg-orange-500 text-white px-6 py-2.5 rounded-xl hover:bg-orange-600 font-semibold transition">
            + Nuevo Vehículo
        </a>
    </div>

    @if(session('info'))
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
            <p class="text-blue-800">{{ session('info') }}</p>
        </div>
    @endif

    @if($vehiculos->isEmpty())
        <div class="bg-slate-50 border-2 border-dashed border-slate-300 rounded-xl p-12 text-center">
            <p class="text-slate-600 mb-4">No tienes vehículos registrados</p>
            <a href="{{ route('cliente.vehiculos.create') }}" class="text-orange-600 hover:text-orange-700 font-semibold">Registra tu primer vehículo</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($vehiculos as $vehiculo)
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-6">
                    <!-- Fila superior: Foto pequeña a la derecha y placa a la izquierda -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-slate-900">{{ $vehiculo->placa }}</h3>
                            <p class="text-slate-600">{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</p>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            @if($vehiculo->fotos->isNotEmpty())
                                <img src="{{ asset('storage/' . $vehiculo->fotos->first()->ruta_foto) }}" 
                                     alt="Foto del vehículo" 
                                     class="w-20 h-20 object-cover rounded-lg">
                                <span class="text-xs text-slate-500">{{ $vehiculo->fotos->count() }} foto(s)</span>
                            @else
                                <div class="w-20 h-20 bg-gradient-to-br from-slate-300 to-slate-400 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Badge de estado -->
                    <div class="mb-4 flex justify-end">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                            {{ $vehiculo->estado->nombre ?? 'Activo' }}
                        </span>
                    </div>

                    <!-- Información del vehículo -->
                    <div class="space-y-2 mb-6">
                        <p class="text-sm text-slate-700"><strong>Año:</strong> {{ $vehiculo->anio ?? 'N/A' }}</p>
                        <p class="text-sm text-slate-700"><strong>Color:</strong> {{ $vehiculo->color ?? 'N/A' }}</p>
                        <p class="text-sm text-slate-700"><strong>Tipo:</strong> {{ $vehiculo->tipo ?? 'N/A' }}</p>
                    </div>

                    <!-- Botón Ver Detalles -->
                    <div class="flex gap-2">
                        <a href="{{ route('cliente.vehiculos.show', $vehiculo) }}" class="flex-1 bg-orange-500 text-white text-center py-3 rounded-lg hover:bg-orange-600 font-semibold transition">
                            Ver Detalles
                        </a>
                        <a href="{{ route('cliente.vehiculos.edit', $vehiculo) }}" class="flex-1 bg-slate-200 text-slate-900 text-center py-3 rounded-lg hover:bg-slate-300 font-semibold transition">
                            Editar
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
