@extends('layouts.tecnico')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Citas Asignadas</h1>
        <p class="text-slate-600 mt-2">Gestiona tus citas programadas</p>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow p-6 mb-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Fecha</label>
                <input type="date" name="fecha" value="{{ request('fecha') }}" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Estado</label>
                <select name="estado" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Todos</option>
                    <option value="Pendiente" {{ request('estado') === 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="Confirmada" {{ request('estado') === 'Confirmada' ? 'selected' : '' }}>Confirmada</option>
                    <option value="En Progreso" {{ request('estado') === 'En Progreso' ? 'selected' : '' }}>En Progreso</option>
                    <option value="Completado" {{ request('estado') === 'Completado' ? 'selected' : '' }}>Completado</option>
                    <option value="Cancelado" {{ request('estado') === 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700 font-semibold transition">
                    Filtrar
                </button>
                <a href="{{ route('tecnico.citas.index') }}" class="flex-1 text-center bg-slate-300 text-slate-900 px-4 py-2.5 rounded-lg hover:bg-slate-400 font-semibold transition">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    @if($citas->isEmpty())
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-12 text-center">
            <i class="bi bi-calendar-x text-5xl text-blue-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-900 mb-2">No hay citas asignadas</h3>
            <p class="text-slate-600">No tienes citas programadas en este momento</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($citas as $cita)
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition border-l-4 {{ $cita->estado === 'Completado' ? 'border-green-500' : ($cita->estado === 'Cancelado' ? 'border-red-500' : 'border-blue-500') }}">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                            <!-- Información Principal -->
                            <div class="md:col-span-2">
                                <div class="flex gap-4">
                                    @if($cita->vehiculo && $cita->vehiculo->fotos->first())
                                        <img src="{{ asset('storage/' . $cita->vehiculo->fotos->first()->ruta_foto) }}" alt="Foto del vehículo" class="w-20 h-20 object-cover rounded-lg flex-shrink-0">
                                    @else
                                        <div class="w-20 h-20 bg-slate-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="bi bi-car-front text-2xl text-slate-400"></i>
                                        </div>
                                    @endif

                                    <div class="flex-1">
                                        @if($cita->vehiculo)
                                            <h3 class="font-bold text-slate-900 text-lg">{{ $cita->vehiculo->placa }}</h3>
                                            <p class="text-slate-600 text-sm">{{ $cita->vehiculo->marca }} {{ $cita->vehiculo->modelo }}</p>
                                        @endif
                                        
                                        @if($cita->cliente)
                                            <p class="text-slate-600 text-sm mt-2">
                                                <strong>Cliente:</strong> {{ $cita->cliente->usuario->nombre ?? 'N/A' }}
                                            </p>
                                        @endif

                                        <div class="mt-2">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                {{ $cita->estado === 'Completado' ? 'bg-green-100 text-green-700' : 
                                                   ($cita->estado === 'Cancelado' ? 'bg-red-100 text-red-700' : 
                                                   ($cita->estado === 'En Progreso' ? 'bg-blue-100 text-blue-700' : 
                                                    'bg-orange-100 text-orange-700')) }}">
                                                {{ $cita->estado }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fecha y Hora -->
                            <div>
                                <p class="text-xs text-slate-600 uppercase tracking-wide font-semibold mb-1">Fecha y Hora</p>
                                <p class="font-bold text-slate-900 text-base">{{ $cita->fecha->format('d/m/Y') }}</p>
                                <p class="text-slate-700 font-semibold">{{ \Carbon\Carbon::createFromFormat('H:i:s', $cita->hora)->format('H:i') }}</p>
                            </div>

                            <!-- Motivo -->
                            <div>
                                <p class="text-xs text-slate-600 uppercase tracking-wide font-semibold mb-1">Motivo</p>
                                <p class="text-slate-700 text-sm truncate">{{ $cita->motivo ?? 'No especificado' }}</p>
                            </div>

                            <!-- Acciones -->
                            <div class="flex flex-col gap-2 justify-center">
                                <a href="{{ route('tecnico.citas.show', $cita) }}" class="text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold transition text-sm">
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
            {{ $citas->links() }}
        </div>
    @endif
</div>
@endsection
