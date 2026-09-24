@extends('layouts.tecnico')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('tecnico.vehiculos.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            Volver
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Detalles del Vehículo</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Contenido Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información General -->
            <div class="bg-white rounded-xl shadow p-8">
                <h2 class="text-2xl font-bold text-slate-900 mb-6">Información General</h2>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Placa</p>
                        <p class="text-2xl font-bold text-slate-900 font-mono">{{ $vehiculo->placa }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Estado</p>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-700">
                            {{ $vehiculo->estado->nombre_estado ?? 'Sin estado' }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Marca</p>
                        <p class="text-slate-900 font-semibold">{{ $vehiculo->marca }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Modelo</p>
                        <p class="text-slate-900 font-semibold">{{ $vehiculo->modelo }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Año</p>
                        <p class="text-slate-900 font-semibold">{{ $vehiculo->anio }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Color</p>
                        <p class="text-slate-900 font-semibold">{{ $vehiculo->color }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Tipo</p>
                        <p class="text-slate-900 font-semibold">{{ $vehiculo->tipo }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">VIN</p>
                        <p class="text-slate-900 font-semibold font-mono">{{ $vehiculo->vin ?? 'No registrado' }}</p>
                    </div>
                </div>

                @if($vehiculo->observaciones)
                    <div class="mt-6 pt-6 border-t border-slate-200">
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Observaciones</p>
                        <p class="text-slate-900">{{ $vehiculo->observaciones }}</p>
                    </div>
                @endif
            </div>

            <!-- Cliente -->
            @if($vehiculo->cliente)
                <div class="bg-white rounded-xl shadow p-8">
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Cliente Propietario</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                            {{ substr($vehiculo->cliente->usuario->nombre, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 text-lg">{{ $vehiculo->cliente->usuario->nombre }}</p>
                            <p class="text-slate-600">{{ $vehiculo->cliente->usuario->correo }}</p>
                            @if($vehiculo->cliente->usuario->telefono)
                                <p class="text-slate-600 text-sm">{{ $vehiculo->cliente->usuario->telefono }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Fotos del Vehículo -->
            <div class="bg-white rounded-xl shadow p-8">
                <h3 class="text-xl font-bold text-slate-900 mb-4">Fotos del Vehículo</h3>

                @if($vehiculo->fotos->isEmpty())
                    <div class="bg-slate-50 rounded-lg p-8 text-center">
                        <i class="bi bi-image text-4xl text-slate-400 mb-3"></i>
                        <p class="text-slate-600">No hay fotos del vehículo</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($vehiculo->fotos as $foto)
                            <div class="group cursor-pointer">
                                <div class="relative overflow-hidden rounded-lg h-40">
                                    <img src="{{ asset('storage/' . $foto->ruta_foto) }}" alt="Foto del vehículo" class="w-full h-full object-cover group-hover:scale-110 transition">
                                </div>
                                @if($foto->descripcion)
                                    <p class="text-xs text-slate-600 mt-2">{{ $foto->descripcion }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="space-y-6">
            <!-- Órdenes de Trabajo -->
            <div class="bg-white rounded-xl shadow p-6 sticky top-8">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Órdenes de Trabajo</h3>

                @php
                    $ordenes = $vehiculo->ordenesTrabajo()->with('estado')->orderByDesc('fecha_ingreso')->get();
                @endphp

                @if($ordenes->isEmpty())
                    <p class="text-slate-600 text-sm">No hay órdenes registradas</p>
                @else
                    <div class="space-y-3">
                        @foreach($ordenes as $orden)
                            <div class="p-3 border border-slate-200 rounded-lg hover:border-blue-400 transition">
                                <div class="flex items-center justify-between mb-2">
                                    <a href="{{ route('tecnico.ordenes.show', $orden) }}" class="font-semibold text-blue-600 hover:text-blue-700">
                                        OT #{{ $orden->id_orden }}
                                    </a>
                                </div>
                                <p class="text-xs text-slate-600">{{ $orden->fecha_ingreso->format('d/m/Y H:i') }}</p>
                                <span class="inline-block mt-2 px-2 py-1 rounded text-xs font-semibold
                                    {{ $orden->estado->nombre === 'Terminado' ? 'bg-green-100 text-green-700' : 
                                       ($orden->estado->nombre === 'Cancelado' ? 'bg-red-100 text-red-700' : 
                                        'bg-orange-100 text-orange-700') }}">
                                    {{ $orden->estado->nombre ?? 'Pendiente' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Ventas Asociadas -->
            @php
                $ventas = $vehiculo->ventas()->with('usuario', 'detalles')->orderByDesc('fecha')->get();
            @endphp
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Ventas Registradas</h3>

                @if($ventas->isEmpty())
                    <p class="text-slate-600 text-sm">No hay ventas registradas</p>
                @else
                    <div class="space-y-3">
                        @foreach($ventas as $venta)
                            <div class="p-3 border border-slate-200 rounded-lg hover:border-green-400 transition bg-green-50">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-semibold text-slate-900">Venta #{{ $venta->id_venta }}</span>
                                    <span class="font-bold text-green-600">${{ number_format($venta->total, 2, '.', ',') }}</span>
                                </div>
                                <p class="text-xs text-slate-600">{{ $venta->fecha->format('d/m/Y') }}</p>
                                <p class="text-xs text-slate-600 mt-1">{{ $venta->detalles->count() }} producto(s) - {{ $venta->usuario->nombre ?? 'N/A' }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Acciones -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Acciones</h3>
                <a href="{{ route('tecnico.vehiculos.index') }}" class="block text-center bg-slate-300 text-slate-900 px-4 py-2.5 rounded-lg hover:bg-slate-400 font-semibold transition mb-2">
                    Volver a Vehículos
                </a>
                <a href="{{ route('tecnico.ordenes.index') }}" class="block text-center bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700 font-semibold transition">
                    Ver Todas las Órdenes
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
