@extends('layouts.cliente')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Mis Notificaciones</h1>
        <p class="text-slate-600 mt-2">Seguimiento de tu(s) vehículo(s) en taller</p>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-4 mb-6 flex gap-4">
        <form action="{{ route('cliente.notificaciones') }}" method="GET" class="flex gap-4">
            <select name="filtro" class="border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todas las notificaciones</option>
                <option value="no-leidas" {{ request('filtro') === 'no-leidas' ? 'selected' : '' }}>Sin leer</option>
                <option value="leidas" {{ request('filtro') === 'leidas' ? 'selected' : '' }}>Leídas</option>
                <option value="estado_vehiculo_cambio" {{ request('filtro') === 'estado_vehiculo_cambio' ? 'selected' : '' }}>Cambios de vehículo</option>
                <option value="estado_orden_cambio" {{ request('filtro') === 'estado_orden_cambio' ? 'selected' : '' }}>Cambios de orden</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold">Filtrar</button>
        </form>

        @if($notificacionesSinLeer > 0)
            <form action="{{ route('cliente.notificaciones.marcar-leidas') }}" method="POST" class="ml-auto">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-semibold">
                    Marcar todo como leído
                </button>
            </form>
        @endif
    </div>

    <!-- Lista de notificaciones -->
    @if($notificaciones->isEmpty())
        <div class="bg-slate-50 rounded-lg p-12 text-center">
            <svg class="w-16 h-16 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <p class="text-slate-600 text-lg">No hay notificaciones</p>
            <p class="text-slate-500 text-sm mt-2">Se mostrará el progreso de tu(s) vehículo(s) aquí</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($notificaciones as $notificacion)
                <div class="bg-white rounded-lg shadow p-6 {{ !$notificacion->leida ? 'border-l-4 border-blue-500 bg-blue-50' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-bold text-slate-900">{{ $notificacion->titulo }}</h3>
                                @if(!$notificacion->leida)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Nuevo</span>
                                @endif
                            </div>
                            
                            <p class="text-slate-700 mb-3">{{ $notificacion->descripcion }}</p>

                            <!-- Detalles de cambio de estado -->
                            @if($notificacion->estado_anterior && $notificacion->estado_nuevo)
                                <div class="bg-slate-50 rounded p-3 mb-3 flex items-center gap-2">
                                    <span class="text-slate-600">{{ $notificacion->estado_anterior }}</span>
                                    <span class="text-slate-400">→</span>
                                    <span class="font-semibold text-slate-900">{{ $notificacion->estado_nuevo }}</span>
                                </div>
                            @endif

                            <!-- Link a la orden si existe -->
                            @if($notificacion->id_orden)
                                <a href="{{ route('cliente.ordenes.show', $notificacion->id_orden) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                    Ver orden #{{ $notificacion->id_orden }} →
                                </a>
                            @endif

                            <p class="text-slate-500 text-xs mt-3">
                                {{ $notificacion->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <div class="ml-4 flex gap-2">
                            @if(!$notificacion->leida)
                                <form action="{{ route('cliente.notificaciones.marcar-leida', $notificacion->id_notificacion) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                        Marcar como leído
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-6">
            {{ $notificaciones->links() }}
        </div>
    @endif
</div>
@endsection
