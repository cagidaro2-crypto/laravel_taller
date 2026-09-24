@extends('layouts.cliente')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('cliente.citas.index') }}" class="text-orange-500 hover:text-orange-600 font-semibold flex items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            Volver
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Detalles de la Cita</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Cita -->
            <div class="bg-white rounded-xl shadow p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-slate-900">Cita Agendada</h2>
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
                        <p class="text-2xl font-bold text-slate-900">{{ date('h:i A', strtotime($cita->hora)) }}</p>
                    </div>
                </div>

                @if($cita->motivo)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Motivo de la Cita</p>
                        <p class="text-slate-900">{{ $cita->motivo }}</p>
                    </div>
                @endif

                @if($cita->observaciones)
                    <div class="bg-slate-100 border border-slate-300 rounded-lg p-4">
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-1">Número de Referencia</p>
                        <p class="font-mono text-lg font-bold text-slate-900">{{ $cita->observaciones }}</p>
                    </div>
                @endif
            </div>

            <!-- Información del Técnico -->
            @if($cita->usuario)
                <div class="bg-white rounded-xl shadow p-8">
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Técnico Asignado</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                            {{ substr($cita->usuario->nombre, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 text-lg">{{ $cita->usuario->nombre }}</p>
                            <p class="text-slate-600">{{ $cita->usuario->correo }}</p>
                            @if($cita->usuario->telefono)
                                <p class="text-slate-600 text-sm">{{ $cita->usuario->telefono }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Panel Lateral -->
        <div class="space-y-6">
            <!-- Vehículo -->
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

                    <a href="{{ route('cliente.vehiculos.show', $cita->vehiculo) }}" class="block text-center mt-4 text-orange-500 hover:text-orange-600 font-semibold transition text-sm">
                        Ver Historial del Vehículo →
                    </a>
                </div>
            </div>

            <!-- Acciones -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Acciones</h3>
                <div class="space-y-2">
                    <a href="{{ route('cliente.citas.index') }}" class="block text-center bg-slate-300 text-slate-900 px-4 py-2.5 rounded-lg hover:bg-slate-400 font-semibold transition">
                        Volver a Citas
                    </a>
                    @if($cita->estado !== 'Completado' && $cita->estado !== 'Cancelado')
                        <form action="{{ route('cliente.citas.cancelar', $cita) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta cita?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full text-center bg-red-500 text-white px-4 py-2.5 rounded-lg hover:bg-red-600 font-semibold transition">
                                Cancelar Cita
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
