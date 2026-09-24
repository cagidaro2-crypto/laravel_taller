@extends('layouts.cliente')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('cliente.historial.index') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Volver al Historial
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Detalles del Historial</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Detalles del Servicio -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-8">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                    <h2 class="text-2xl font-bold text-slate-900">Registro de Servicio</h2>
                    @if($historialVehiculo->estado)
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                            {{ $historialVehiculo->estado }}
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-1">Fecha del Evento</p>
                        <p class="text-xl font-bold text-slate-900">{{ $historialVehiculo->fecha->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-1">Valor Registrado</p>
                        <p class="text-xl font-bold text-emerald-600">${{ number_format($historialVehiculo->valor ?? 0, 2) }}</p>
                    </div>
                </div>

                @if($historialVehiculo->estado_anterior || $historialVehiculo->estado_nuevo)
                    <div class="bg-slate-50 rounded-xl p-4 mb-6 border border-slate-200">
                        <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-2">Transición de Estado del Vehículo</p>
                        <div class="flex items-center gap-3 text-sm">
                            <span class="px-2.5 py-1 bg-slate-200 text-slate-700 rounded-lg font-medium">{{ $historialVehiculo->estado_anterior ?? 'Inicio' }}</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            <span class="px-2.5 py-1 bg-indigo-100 text-indigo-700 rounded-lg font-medium">{{ $historialVehiculo->estado_nuevo ?? 'Actual' }}</span>
                        </div>
                    </div>
                @endif

                @if($historialVehiculo->descripcion)
                    <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-6">
                        <p class="text-xs text-indigo-600 uppercase tracking-wide font-bold mb-2">Descripción del Servicio / Detalle</p>
                        <p class="text-slate-800 text-base leading-relaxed">{{ $historialVehiculo->descripcion }}</p>
                    </div>
                @endif
            </div>

            <!-- Técnico Asignado -->
            @if($historialVehiculo->usuario)
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-8">
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Técnico / Responsable</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-xl flex items-center justify-center text-white text-xl font-bold shadow-sm">
                            {{ strtoupper(substr($historialVehiculo->usuario->nombre, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 text-lg">{{ $historialVehiculo->usuario->nombre }}</p>
                            <p class="text-slate-500 text-sm">{{ $historialVehiculo->usuario->correo }}</p>
                            @if($historialVehiculo->usuario->telefono)
                                <p class="text-slate-500 text-xs mt-0.5">{{ $historialVehiculo->usuario->telefono }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Panel Lateral -->
        <div class="space-y-6">
            <!-- Vehículo -->
            @if($historialVehiculo->vehiculo)
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 sticky top-8">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Vehículo Asociado</h3>
                    
                    @if($historialVehiculo->vehiculo->fotos->first())
                        <img src="{{ asset('storage/' . $historialVehiculo->vehiculo->fotos->first()->ruta_foto) }}" alt="Foto del vehículo" class="w-full h-40 object-cover rounded-xl mb-4 shadow-sm">
                    @else
                        <div class="w-full h-36 bg-slate-100 rounded-xl flex items-center justify-center mb-4 text-slate-400">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h1m6-9h5l3 3v4h-1"/></svg>
                        </div>
                    @endif

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between border-b border-slate-50 pb-2">
                            <span class="text-slate-500">Placa</span>
                            <span class="font-mono font-bold text-slate-900">{{ $historialVehiculo->vehiculo->placa }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-50 pb-2">
                            <span class="text-slate-500">Marca / Modelo</span>
                            <span class="font-medium text-slate-900">{{ $historialVehiculo->vehiculo->marca }} {{ $historialVehiculo->vehiculo->modelo }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-50 pb-2">
                            <span class="text-slate-500">Año</span>
                            <span class="font-medium text-slate-900">{{ $historialVehiculo->vehiculo->anio ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between pb-2">
                            <span class="text-slate-500">Color</span>
                            <span class="font-medium text-slate-900">{{ $historialVehiculo->vehiculo->color ?? 'N/A' }}</span>
                        </div>

                        <a href="{{ route('cliente.vehiculos.show', $historialVehiculo->vehiculo) }}" class="block text-center mt-4 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-semibold py-2.5 rounded-xl transition text-sm">
                            Ver Vehículo Completo
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
