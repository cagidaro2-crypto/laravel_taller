@extends('layouts.tecnico')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Vehículos en Taller</h1>
        <p class="text-slate-600 mt-2">Lista de vehículos en reparación o revisión</p>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow p-6 mb-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Buscar por Placa</label>
                <input type="text" name="placa" value="{{ request('placa') }}" placeholder="Ej: ABC-123" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700 font-semibold transition">
                    Buscar
                </button>
                <a href="{{ route('tecnico.vehiculos.index') }}" class="flex-1 text-center bg-slate-300 text-slate-900 px-4 py-2.5 rounded-lg hover:bg-slate-400 font-semibold transition">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    @if($vehiculos->isEmpty())
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-12 text-center">
            <i class="bi bi-car-front text-5xl text-blue-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-900 mb-2">No hay vehículos</h3>
            <p class="text-slate-600">No hay vehículos registrados en este momento</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($vehiculos as $vehiculo)
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                    <!-- Foto -->
                    <div class="relative h-48 bg-slate-200">
                        @if($vehiculo->fotos->first())
                            <img src="{{ asset('storage/' . $vehiculo->fotos->first()->ruta_foto) }}" alt="Foto del vehículo" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="bi bi-car-front text-6xl text-slate-400"></i>
                            </div>
                        @endif
                        
                        <!-- Badge de Estado -->
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                {{ $vehiculo->estado->nombre_estado ?? 'Sin estado' }}
                            </span>
                        </div>
                    </div>

                    <!-- Información -->
                    <div class="p-6">
                        <h3 class="font-bold text-slate-900 text-lg mb-1">{{ $vehiculo->placa }}</h3>
                        <p class="text-slate-600 text-sm mb-4">{{ $vehiculo->marca }} {{ $vehiculo->modelo }} ({{ $vehiculo->anio }})</p>

                        <!-- Cliente -->
                        @if($vehiculo->cliente)
                            <div class="mb-4 p-3 bg-slate-50 rounded-lg">
                                <p class="text-xs text-slate-600 uppercase tracking-wide font-semibold mb-1">Cliente</p>
                                <p class="text-sm font-semibold text-slate-900">{{ $vehiculo->cliente->usuario->nombre ?? 'N/A' }}</p>
                            </div>
                        @endif

                        <!-- Especificaciones -->
                        <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                            <div>
                                <p class="text-xs text-slate-600 uppercase font-semibold">Color</p>
                                <p class="text-slate-900 font-semibold">{{ $vehiculo->color ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-600 uppercase font-semibold">Tipo</p>
                                <p class="text-slate-900 font-semibold">{{ $vehiculo->tipo ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Órdenes Activas -->
                        @php
                            $ordenesActivas = $vehiculo->ordenesTrabajo
                                ->filter(fn($ot) => !in_array($ot->estado->nombre ?? '', ['Terminado', 'Entregado', 'Cancelado']))
                                ->count();
                        @endphp
                        <div class="mb-4 p-3 bg-orange-50 rounded-lg">
                            <p class="text-xs text-slate-600 uppercase tracking-wide font-semibold mb-1">Mis Órdenes Activas</p>
                            <p class="text-lg font-bold {{ $ordenesActivas > 0 ? 'text-orange-600' : 'text-slate-400' }}">
                                {{ $ordenesActivas > 0 ? $ordenesActivas : 'Sin órdenes asignadas' }}
                            </p>
                        </div>

                        <!-- Botones -->
                        <div class="flex gap-2">
                            <a href="{{ route('tecnico.vehiculos.show', $vehiculo) }}" class="flex-1 text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold transition text-sm">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-8">
            {{ $vehiculos->links() }}
        </div>
    @endif
</div>
@endsection
