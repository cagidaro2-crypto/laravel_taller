@extends('layouts.cliente')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Mis Citas</h1>
            <p class="text-slate-600 mt-2">Gestiona tus citas de servicio</p>
        </div>
        <a href="{{ route('cliente.citas.create') }}" class="bg-orange-500 text-white px-6 py-2.5 rounded-lg hover:bg-orange-600 font-semibold transition flex items-center gap-2">
            <i class="bi bi-plus-lg"></i>
            Nueva Cita
        </a>
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

    @if($citas->isEmpty())
        <div class="bg-white rounded-xl shadow p-12 text-center">
            <i class="bi bi-calendar2-x text-5xl text-slate-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-900 mb-2">No tienes citas agendadas</h3>
            <p class="text-slate-600 mb-6">Agenda una cita ahora para que nuestros técnicos puedan revisar tu vehículo</p>
            <a href="{{ route('cliente.citas.create') }}" class="inline-block bg-orange-500 text-white px-6 py-2.5 rounded-lg hover:bg-orange-600 font-semibold transition">
                Agendar Cita
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($citas as $cita)
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition border-l-4 {{ $cita->estado === 'Completado' ? 'border-green-500' : ($cita->estado === 'Cancelado' ? 'border-red-500' : 'border-orange-500') }}">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <!-- Información Principal -->
                            <div class="md:col-span-2">
                                <div class="flex gap-4">
                                    <!-- Foto del Vehículo -->
                                    @if($cita->vehiculo->fotos->first())
                                        <img src="{{ asset('storage/' . $cita->vehiculo->fotos->first()->ruta_foto) }}" alt="Foto del vehículo" class="w-24 h-24 object-cover rounded-lg flex-shrink-0">
                                    @else
                                        <div class="w-24 h-24 bg-slate-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="bi bi-car-front text-3xl text-slate-400"></i>
                                        </div>
                                    @endif

                                    <div class="flex-1">
                                        <h3 class="font-bold text-slate-900 text-lg">{{ $cita->vehiculo->placa }}</h3>
                                        <p class="text-slate-600 text-sm">{{ $cita->vehiculo->marca }} {{ $cita->vehiculo->modelo }} ({{ $cita->vehiculo->anio }})</p>
                                        
                                        <div class="mt-3 flex items-center gap-2">
                                            <span class="px-3 py-1 rounded-full text-sm font-semibold
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

                            <!-- Detalles -->
                            <div>
                                <p class="text-xs text-slate-600 uppercase tracking-wide font-semibold mb-1">Fecha y Hora</p>
                                <p class="font-bold text-slate-900 text-base">
                                    {{ $cita->fecha->format('d/m/Y') }}
                                </p>
                                <p class="text-slate-700 font-semibold">
                                    {{ date('h:i A', strtotime($cita->hora)) }}
                                </p>
                                
                                @if($cita->observaciones)
                                    <p class="text-xs text-slate-600 mt-2">
                                        <strong>Ref:</strong> {{ $cita->observaciones }}
                                    </p>
                                @endif
                            </div>

                            <!-- Acciones -->
                            <div class="flex flex-col gap-2 justify-center">
                                <a href="{{ route('cliente.citas.show', $cita) }}" class="text-center bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 font-semibold transition text-sm">
                                    Ver Detalles
                                </a>
                                @if($cita->estado !== 'Completado' && $cita->estado !== 'Cancelado')
                                    <form action="{{ route('cliente.citas.cancelar', $cita) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta cita?');" class="w-full">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full text-center bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 font-semibold transition text-sm">
                                            Cancelar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        @if($cita->motivo)
                            <div class="mt-4 pt-4 border-t border-slate-200">
                                <p class="text-sm text-slate-600 mb-1"><strong>Motivo:</strong></p>
                                <p class="text-slate-700">{{ $cita->motivo }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
