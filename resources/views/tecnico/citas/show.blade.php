@extends('layouts.tecnico')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('tecnico.citas.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            Volver
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Detalles de la Cita</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Contenido Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información de la Cita -->
            <div class="bg-white rounded-xl shadow p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-slate-900">Cita Programada</h2>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        {{ $cita->estado === 'Completado' ? 'bg-green-100 text-green-700' : 
                           ($cita->estado === 'Cancelado' ? 'bg-red-100 text-red-700' : 
                           ($cita->estado === 'En Progreso' ? 'bg-blue-100 text-blue-700' : 
                            'bg-orange-100 text-orange-700')) }}">
                        {{ $cita->estado }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Fecha</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $cita->fecha->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Hora</p>
                        <p class="text-2xl font-bold text-slate-900">{{ \Carbon\Carbon::createFromFormat('H:i:s', $cita->hora)->format('H:i') }}</p>
                    </div>
                </div>

                @if($cita->motivo)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Motivo de la Cita</p>
                        <p class="text-slate-900">{{ $cita->motivo }}</p>
                    </div>
                @endif
            </div>

            <!-- Información del Cliente -->
            @if($cita->cliente)
                <div class="bg-white rounded-xl shadow p-8">
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Cliente</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                            {{ substr($cita->cliente->usuario->nombre, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 text-lg">{{ $cita->cliente->usuario->nombre }}</p>
                            <p class="text-slate-600">{{ $cita->cliente->usuario->email }}</p>
                            @if($cita->cliente->usuario->telefono)
                                <p class="text-slate-600 text-sm">{{ $cita->cliente->usuario->telefono }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Panel Lateral -->
        <div class="space-y-6">
            <!-- Vehículo -->
            @if($cita->vehiculo)
                <div class="bg-white rounded-xl shadow p-6 sticky top-8">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Vehículo</h3>
                    
                    @if($cita->vehiculo->fotos->first())
                        <img src="{{ asset('storage/' . $cita->vehiculo->fotos->first()->ruta_foto) }}" alt="Foto del vehículo" class="w-full h-40 object-cover rounded-lg mb-4">
                    @else
                        <div class="w-full h-40 bg-slate-200 rounded-lg flex items-center justify-center mb-4">
                            <i class="bi bi-car-front text-4xl text-slate-400"></i>
                        </div>
                    @endif

                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-1">Placa</p>
                            <p class="font-mono text-lg font-bold text-slate-900">{{ $cita->vehiculo->placa }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-1">Marca y Modelo</p>
                            <p class="text-slate-900 font-semibold">{{ $cita->vehiculo->marca }} {{ $cita->vehiculo->modelo }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-1">Año</p>
                            <p class="text-slate-900 font-semibold">{{ $cita->vehiculo->anio }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-1">Color</p>
                            <p class="text-slate-900 font-semibold">{{ $cita->vehiculo->color }}</p>
                        </div>

                        <a href="{{ route('tecnico.vehiculos.show', $cita->vehiculo) }}" class="block text-center mt-4 text-blue-600 hover:text-blue-700 font-semibold transition text-sm">
                            Ver Vehículo Completo →
                        </a>
                    </div>
                </div>
            @endif

            <!-- Acciones -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Acciones</h3>
                <div class="space-y-2">
                    <a href="{{ route('tecnico.citas.index') }}" class="block text-center bg-slate-300 text-slate-900 px-4 py-2.5 rounded-lg hover:bg-slate-400 font-semibold transition">
                        Volver a Citas
                    </a>
                    @if($cita->vehiculo)
                        <a href="{{ route('tecnico.vehiculos.show', $cita->vehiculo) }}" class="block text-center bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700 font-semibold transition">
                            Ver Órdenes de este Vehículo
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
