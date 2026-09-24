@extends('layouts.tecnico')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Historial de Servicios</h1>
        <p class="text-slate-600 mt-2">Registro de servicios realizados</p>
    </div>

    @if($historial->isEmpty())
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-12 text-center">
            <i class="bi bi-clock-history text-5xl text-blue-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-900 mb-2">No hay historial de servicios</h3>
            <p class="text-slate-600">Los servicios realizados aparecerán aquí</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($historial as $registro)
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition border-l-4 border-blue-500">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                            <!-- Información Principal -->
                            <div class="md:col-span-2">
                                <div class="flex gap-4">
                                    @if($registro->vehiculo && $registro->vehiculo->fotos->first())
                                        <img src="{{ asset('storage/' . $registro->vehiculo->fotos->first()->ruta_foto) }}" alt="Foto del vehículo" class="w-20 h-20 object-cover rounded-lg flex-shrink-0">
                                    @else
                                        <div class="w-20 h-20 bg-slate-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="bi bi-car-front text-2xl text-slate-400"></i>
                                        </div>
                                    @endif

                                    <div class="flex-1">
                                        @if($registro->vehiculo)
                                            <h3 class="font-bold text-slate-900 text-lg">{{ $registro->vehiculo->placa }}</h3>
                                            <p class="text-slate-600 text-sm">{{ $registro->vehiculo->marca }} {{ $registro->vehiculo->modelo }}</p>
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
                                <p class="font-bold text-slate-900 text-base">{{ $registro->fecha->format('d/m/Y') }}</p>
                                <p class="text-slate-700 font-semibold text-sm">{{ $registro->fecha->format('H:i') }}</p>
                            </div>

                            <!-- Duración -->
                            <div>
                                <p class="text-xs text-slate-600 uppercase tracking-wide font-semibold mb-1">Duración</p>
                                @if($registro->fecha_fin)
                                    <p class="font-bold text-slate-900">{{ $registro->fecha->diffInMinutes($registro->fecha_fin) }} min</p>
                                @else
                                    <p class="text-slate-600 text-sm">En progreso</p>
                                @endif
                            </div>

                            <!-- Técnico -->
                            <div>
                                <p class="text-xs text-slate-600 uppercase tracking-wide font-semibold mb-1">Técnico</p>
                                @if($registro->usuario)
                                    <p class="font-semibold text-slate-900 text-sm">{{ $registro->usuario->nombre }}</p>
                                @else
                                    <p class="text-slate-600 text-sm">No asignado</p>
                                @endif
                            </div>

                            <!-- Acciones -->
                            <div class="flex flex-col gap-2 justify-center">
                                <a href="{{ route('tecnico.historial.show', $registro) }}" class="text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold transition text-sm">
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-8">
            {{ $historial->links() }}
        </div>
    @endif
</div>
@endsection
