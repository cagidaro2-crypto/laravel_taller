@extends('layouts.tecnico')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('tecnico.historial.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            Volver
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Detalles del Servicio</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Contenido Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información del Servicio -->
            <div class="bg-white rounded-xl shadow p-8">
                <h2 class="text-2xl font-bold text-slate-900 mb-6">Información del Servicio</h2>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Fecha del Servicio</p>
                        <p class="text-xl font-bold text-slate-900">{{ $historialVehiculo->fecha->format('d/m/Y') }}</p>
                        <p class="text-slate-700 font-semibold">{{ $historialVehiculo->fecha->format('H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Duración</p>
                        @if($historialVehiculo->fecha_fin)
                            <p class="text-slate-900 font-semibold">
                                {{ $historialVehiculo->fecha->diffInMinutes($historialVehiculo->fecha_fin) }} minutos
                            </p>
                        @else
                            <p class="text-slate-600">Aún en progreso</p>
                        @endif
                    </div>
                </div>

                @if($historialVehiculo->descripcion)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Descripción del Servicio</p>
                        <p class="text-slate-900 text-lg leading-relaxed">{{ $historialVehiculo->descripcion }}</p>
                    </div>
                @endif

                @if($historialVehiculo->observaciones)
                    <div class="bg-slate-100 border border-slate-300 rounded-lg p-6">
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Observaciones Generales</p>
                        <p class="text-slate-900">{{ $historialVehiculo->observaciones }}</p>
                    </div>
                @endif
            </div>

            <!-- Información Técnica -->
            <div class="bg-white rounded-xl shadow p-8">
                <h3 class="text-xl font-bold text-slate-900 mb-6">Información Técnica</h3>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Kilometraje</p>
                        <p class="text-lg font-bold text-slate-900">
                            {{ number_format($historialVehiculo->kilometraje ?? 0, 0, '.', ',') }} km
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Estado General</p>
                        <p class="text-slate-900 font-semibold">{{ $historialVehiculo->estado_general ?? 'No registrado' }}</p>
                    </div>
                </div>

                @if($historialVehiculo->observaciones_tecnicas)
                    <div class="p-4 bg-slate-50 rounded-lg">
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Notas Técnicas</p>
                        <p class="text-slate-900">{{ $historialVehiculo->observaciones_tecnicas }}</p>
                    </div>
                @endif
            </div>

            <!-- Técnico -->
            @if($historialVehiculo->usuario)
                <div class="bg-white rounded-xl shadow p-8">
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Técnico Responsable</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                            {{ substr($historialVehiculo->usuario->nombre, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 text-lg">{{ $historialVehiculo->usuario->nombre }}</p>
                            <p class="text-slate-600">{{ $historialVehiculo->usuario->email }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Panel Lateral -->
        <div class="space-y-6">
            <!-- Vehículo -->
            @if($historialVehiculo->vehiculo)
                <div class="bg-white rounded-xl shadow p-6 sticky top-8">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Vehículo</h3>
                    
                    @if($historialVehiculo->vehiculo->fotos->first())
                        <img src="{{ asset('storage/' . $historialVehiculo->vehiculo->fotos->first()->ruta_foto) }}" alt="Foto del vehículo" class="w-full h-40 object-cover rounded-lg mb-4">
                    @else
                        <div class="w-full h-40 bg-slate-200 rounded-lg flex items-center justify-center mb-4">
                            <i class="bi bi-car-front text-4xl text-slate-400"></i>
                        </div>
                    @endif

                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-1">Placa</p>
                            <p class="font-mono text-lg font-bold text-slate-900">{{ $historialVehiculo->vehiculo->placa }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-1">Marca y Modelo</p>
                            <p class="text-slate-900 font-semibold">{{ $historialVehiculo->vehiculo->marca }} {{ $historialVehiculo->vehiculo->modelo }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-1">Año</p>
                            <p class="text-slate-900 font-semibold">{{ $historialVehiculo->vehiculo->anio }}</p>
                        </div>

                        <a href="{{ route('tecnico.vehiculos.show', $historialVehiculo->vehiculo) }}" class="block text-center mt-4 text-blue-600 hover:text-blue-700 font-semibold transition text-sm">
                            Ver Vehículo Completo →
                        </a>
                    </div>
                </div>
            @endif

            <!-- Acciones -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Acciones</h3>
                <a href="{{ route('tecnico.historial.index') }}" class="block text-center bg-slate-300 text-slate-900 px-4 py-2.5 rounded-lg hover:bg-slate-400 font-semibold transition">
                    Volver al Historial
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
