@extends('layouts.cliente')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Mis Cotizaciones</h1>
        <p class="text-slate-600 mt-2">Cotizaciones de servicios para tus vehículos</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-start gap-3">
            <i class="bi bi-check-circle mt-0.5"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-start gap-3">
            <i class="bi bi-exclamation-circle mt-0.5"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <!-- Resumen de Cotizaciones -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-orange-50 border border-orange-200 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-orange-600 uppercase tracking-wide font-semibold">Pendientes</p>
                    <p class="text-3xl font-bold text-orange-700">{{ $pendientes }}</p>
                </div>
                <i class="bi bi-file-earmark text-4xl text-orange-300"></i>
            </div>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-600 uppercase tracking-wide font-semibold">Aprobadas</p>
                    <p class="text-3xl font-bold text-blue-700">{{ $cotizaciones->where('estado', 'Aprobada')->count() }}</p>
                </div>
                <i class="bi bi-check-circle text-4xl text-blue-300"></i>
            </div>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-red-600 uppercase tracking-wide font-semibold">Rechazadas</p>
                    <p class="text-3xl font-bold text-red-700">{{ $cotizaciones->where('estado', 'Rechazada')->count() }}</p>
                </div>
                <i class="bi bi-x-circle text-4xl text-red-300"></i>
            </div>
        </div>
    </div>

    @if($cotizaciones->isEmpty())
        <div class="bg-white rounded-xl shadow p-12 text-center">
            <i class="bi bi-file-earmark-x text-5xl text-slate-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-900 mb-2">No tienes cotizaciones</h3>
            <p class="text-slate-600">Cuando el taller cree cotizaciones para tus vehículos, aparecerán aquí</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($cotizaciones as $cotizacion)
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition border-l-4 {{ $cotizacion->estado === 'Aprobada' ? 'border-green-500' : ($cotizacion->estado === 'Rechazada' ? 'border-red-500' : 'border-orange-500') }}">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                            <!-- Información Principal -->
                            <div class="md:col-span-2">
                                <div class="flex gap-4">
                                    <!-- Foto del Vehículo -->
                                    @if($cotizacion->vehiculo && $cotizacion->vehiculo->fotos->first())
                                        <img src="{{ asset('storage/' . $cotizacion->vehiculo->fotos->first()->ruta_foto) }}" alt="Foto del vehículo" class="w-24 h-24 object-cover rounded-lg flex-shrink-0">
                                    @else
                                        <div class="w-24 h-24 bg-slate-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="bi bi-car-front text-3xl text-slate-400"></i>
                                        </div>
                                    @endif

                                    <div class="flex-1">
                                        @if($cotizacion->vehiculo)
                                            <h3 class="font-bold text-slate-900 text-lg">{{ $cotizacion->vehiculo->placa }}</h3>
                                            <p class="text-slate-600 text-sm">{{ $cotizacion->vehiculo->marca }} {{ $cotizacion->vehiculo->modelo }}</p>
                                        @else
                                            <h3 class="font-bold text-slate-900 text-lg">Vehículo No Disponible</h3>
                                        @endif
                                        
                                        <div class="mt-3 flex items-center gap-2">
                                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                                {{ $cotizacion->estado === 'Aprobada' ? 'bg-green-100 text-green-700' : 
                                                   ($cotizacion->estado === 'Rechazada' ? 'bg-red-100 text-red-700' : 
                                                    'bg-orange-100 text-orange-700') }}">
                                                {{ $cotizacion->estado }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fecha -->
                            <div>
                                <p class="text-xs text-slate-600 uppercase tracking-wide font-semibold mb-1">Fecha</p>
                                <p class="font-bold text-slate-900 text-base">
                                    {{ $cotizacion->fecha->format('d/m/Y') }}
                                </p>
                                @if($cotizacion->fecha_vencimiento)
                                    <p class="text-xs text-slate-600 mt-2">
                                        <strong>Vencimiento:</strong> {{ $cotizacion->fecha_vencimiento->format('d/m/Y') }}
                                    </p>
                                @endif
                            </div>

                            <!-- Monto -->
                            <div>
                                <p class="text-xs text-slate-600 uppercase tracking-wide font-semibold mb-1">Monto Total</p>
                                <p class="font-bold text-slate-900 text-lg">
                                    ${{ number_format($cotizacion->total ?? 0, 2, '.', ',') }}
                                </p>
                            </div>

                            <!-- Acciones -->
                            <div class="flex flex-col gap-2 justify-center">
                                <a href="{{ route('cliente.cotizaciones.show', $cotizacion) }}" class="text-center bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 font-semibold transition text-sm">
                                    Ver Detalles
                                </a>
                                @if($cotizacion->estado === 'Pendiente')
                                    <form action="{{ route('cliente.cotizaciones.aprobar', $cotizacion) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas aprobar esta cotización?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full text-center bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 font-semibold transition text-sm">
                                            Aprobar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        @if($cotizacion->observaciones)
                            <div class="mt-4 pt-4 border-t border-slate-200">
                                <p class="text-sm text-slate-600 mb-1"><strong>Observaciones:</strong></p>
                                <p class="text-slate-700 text-sm">{{ $cotizacion->observaciones }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
