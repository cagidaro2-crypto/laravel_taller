@extends('layouts.cliente')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Historial de Servicios</h1>
        <p class="text-slate-600 mt-2">Servicios realizados en tus vehículos</p>
    </div>

    @if($historial->isEmpty())
        <div class="bg-white rounded-xl shadow p-12 text-center">
            <i class="bi bi-clock-history text-5xl text-slate-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-900 mb-2">No tienes historial de servicios</h3>
            <p class="text-slate-600">Los servicios realizados en tus vehículos aparecerán aquí</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($historial as $registro)
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition border-l-4 border-blue-500">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <!-- Información Principal -->
                            <div class="md:col-span-2">
                                <div class="flex gap-4">
                                    <!-- Foto del Vehículo -->
                                    @if($registro->vehiculo && $registro->vehiculo->fotos->first())
                                        <img src="{{ asset('storage/' . $registro->vehiculo->fotos->first()->ruta_foto) }}" alt="Foto del vehículo" class="w-24 h-24 object-cover rounded-lg flex-shrink-0">
                                    @else
                                        <div class="w-24 h-24 bg-slate-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="bi bi-car-front text-3xl text-slate-400"></i>
                                        </div>
                                    @endif

                                    <div class="flex-1">
                                        @if($registro->vehiculo)
                                            <h3 class="font-bold text-slate-900 text-lg">{{ $registro->vehiculo->placa }}</h3>
                                            <p class="text-slate-600 text-sm">{{ $registro->vehiculo->marca }} {{ $registro->vehiculo->modelo }}</p>
                                        @else
                                            <h3 class="font-bold text-slate-900 text-lg">Vehículo No Disponible</h3>
                                        @endif
                                        
                                        @if($registro->descripcion)
                                            <p class="text-slate-700 text-sm mt-2">{{ $registro->descripcion }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Fecha -->
                            <div>
                                <p class="text-xs text-slate-600 uppercase tracking-wide font-semibold mb-1">Fecha del Servicio</p>
                                <p class="font-bold text-slate-900 text-base">
                                    {{ $registro->fecha->format('d/m/Y') }}
                                </p>
                                <p class="text-slate-700 font-semibold text-sm">
                                    {{ $registro->fecha->format('H:i') }}
                                </p>
                            </div>

                            <!-- Observaciones -->
                            <div>
                                <p class="text-xs text-slate-600 uppercase tracking-wide font-semibold mb-1">Técnico</p>
                                @if($registro->usuario)
                                    <p class="font-semibold text-slate-900">{{ $registro->usuario->nombre }}</p>
                                    <p class="text-slate-600 text-sm">{{ $registro->usuario->email }}</p>
                                @else
                                    <p class="text-slate-600">No asignado</p>
                                @endif
                            </div>

                            <!-- Acciones -->
                            <div class="flex flex-col gap-2 justify-center">
                                <a href="{{ route('cliente.historial.show', $registro) }}" class="text-center bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 font-semibold transition text-sm">
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
