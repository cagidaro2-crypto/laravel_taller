@extends('layouts.tecnico')
@section('title','Panel Técnico')

@section('content')
<div class="space-y-8">

    <!-- ══════════════════════════════════════════════════
         BANNER DE BIENVENIDA TÉCNICO
    ══════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-slate-950 to-slate-900 border border-slate-800 p-6 sm:p-8 text-white shadow-xl shadow-slate-950/20">
        <!-- Luces ambientales de fondo -->
        <div class="absolute -right-12 -top-12 w-64 h-64 bg-orange-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-12 -bottom-12 w-64 h-64 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-500/20 border border-orange-400/30 text-orange-300 text-xs font-semibold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Turno Activo · Técnico Especialista
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    ¡Buen día, <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 via-amber-300 to-yellow-400">{{ auth()->user()->nombre }}</span>! 🔧
                </h1>
                <p class="text-slate-300 text-sm max-w-xl leading-relaxed">
                    Gestiona tus órdenes de trabajo, registra materiales consumidos, actualiza el estado de los vehículos y genera facturas para los clientes.
                </p>
            </div>

            <!-- Acciones rápidas en el banner -->
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('tecnico.facturas.create') }}" 
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-bold text-xs uppercase tracking-wider px-5 py-3 rounded-2xl shadow-lg shadow-orange-500/25 transition-all hover:scale-105 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    <span>Generar Factura</span>
                </a>
                <a href="{{ route('tecnico.ordenes.index') }}" 
                   class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold text-xs uppercase tracking-wider px-4 py-3 rounded-2xl border border-white/15 backdrop-blur-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <span>Mis Órdenes</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         TARJETAS KPI / MÉTRICAS DEL TÉCNICO
    ══════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

        <!-- 1. Mis Órdenes Activas -->
        <a href="{{ route('tecnico.ordenes.index') }}" 
           class="group bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:border-orange-300 hover:shadow-xl hover:shadow-orange-500/5 transition-all duration-300 relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 group-hover:text-orange-600 transition-colors">Órdenes Asignadas</span>
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 group-hover:bg-orange-500 group-hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-black text-slate-900 group-hover:text-orange-600 transition-colors">{{ $misOrdenes }}</p>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                    <span>En ejecución bajo tu cargo</span> →
                </p>
            </div>
        </a>

        <!-- 2. Citas para Hoy -->
        <a href="{{ route('tecnico.citas.index') }}" 
           class="group bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:border-amber-300 hover:shadow-xl hover:shadow-amber-500/5 transition-all duration-300 relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 group-hover:text-amber-600 transition-colors">Citas Hoy</span>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 group-hover:bg-amber-500 group-hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-black text-slate-900 group-hover:text-amber-600 transition-colors">{{ $citasHoy }}</p>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                    <span>Recepción e inspecciones</span> →
                </p>
            </div>
        </a>

        <!-- 3. Vehículos en Taller -->
        <a href="{{ route('tecnico.vehiculos.index') }}" 
           class="group bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:border-orange-300 hover:shadow-xl hover:shadow-orange-500/5 transition-all duration-300 relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 group-hover:text-orange-600 transition-colors">Vehículos en Taller</span>
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 group-hover:bg-orange-500 group-hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h1m6-9h5l3 3v4h-1"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-black text-slate-900 group-hover:text-orange-600 transition-colors">{{ $vehiculosEnTaller }}</p>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                    <span>En bahías de trabajo</span> →
                </p>
            </div>
        </a>

    </div>

    <!-- ══════════════════════════════════════════════════
         SECCIÓN PRINCIPAL: Órdenes Asignadas & Citas
    ══════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Órdenes Asignadas Recientes (2 Columnas) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-slate-100 space-y-5">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Órdenes de Trabajo Asignadas</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Vehículos que estás interviniendo actualmente</p>
                </div>
                <a href="{{ route('tecnico.ordenes.index') }}" class="text-xs font-bold text-orange-600 hover:text-orange-800 transition">
                    Ver todas →
                </a>
            </div>

            @if($ultimasOrdenes->isEmpty())
                <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <p class="text-sm font-semibold text-slate-600">No tienes órdenes activas asignadas en este momento</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-slate-400 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="py-3 px-4">Orden</th>
                                <th class="py-3 px-4">Placa</th>
                                <th class="py-3 px-4">Vehículo</th>
                                <th class="py-3 px-4">Estado</th>
                                <th class="py-3 px-4 text-right">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($ultimasOrdenes as $ord)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4 font-black font-mono text-slate-900">#{{ $ord->id_orden }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-900 font-mono text-xs font-bold border border-amber-200">
                                        {{ $ord->vehiculo->placa ?? 'S/P' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-700 font-medium">
                                    {{ $ord->vehiculo->marca ?? '' }} {{ $ord->vehiculo->modelo ?? '' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                        {{ $ord->estado->nombre ?? 'En Proceso' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ route('tecnico.ordenes.show', $ord) }}" class="text-xs font-bold text-orange-600 hover:text-orange-800">
                                        Ver orden →
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Columna Lateral: Próximas Citas y Atajos (1 Columna) -->
        <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-slate-100 space-y-6 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Próximas Citas</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Ingresos programados</p>
                    </div>
                    <a href="{{ route('tecnico.citas.index') }}" class="text-xs font-bold text-orange-600 hover:text-orange-800 transition">
                        Ver agenda →
                    </a>
                </div>

                @if($proximasCitas->isEmpty())
                    <div class="p-6 text-center bg-slate-50 rounded-2xl border border-slate-100">
                        <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-xs font-semibold text-slate-600">No hay citas pendientes para hoy</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($proximasCitas as $cita)
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-slate-900">{{ $cita->cliente->usuario->nombre ?? 'Cliente' }}</p>
                                <p class="text-[11px] text-slate-500">{{ date('d/m/Y', strtotime($cita->fecha)) }} - {{ $cita->hora ?? 'Hora s/d' }}</p>
                                @if($cita->vehiculo)
                                    <span class="inline-block mt-1 font-mono text-[10px] bg-slate-200 text-slate-800 px-1.5 py-0.5 rounded font-bold">
                                        {{ $cita->vehiculo->placa }}
                                    </span>
                                @endif
                            </div>
                            <a href="{{ route('tecnico.citas.show', $cita) }}" class="text-xs font-bold text-orange-600 hover:text-orange-800">
                                Ver →
                            </a>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Accesos Rápidos del Técnico -->
            <div class="pt-4 border-t border-slate-100 space-y-2">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Herramientas</p>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('tecnico.facturas.index') }}" class="p-2.5 rounded-xl bg-slate-50 hover:bg-orange-50 border border-slate-200/80 hover:border-orange-200 text-slate-700 hover:text-orange-700 text-xs font-bold transition text-center">
                        Facturas
                    </a>
                    <a href="{{ route('tecnico.ventas.create') }}" class="p-2.5 rounded-xl bg-slate-50 hover:bg-orange-50 border border-slate-200/80 hover:border-orange-200 text-slate-700 hover:text-orange-700 text-xs font-bold transition text-center">
                        + Registrar Venta
                    </a>
                    <a href="{{ route('tecnico.vehiculos.index') }}" class="p-2.5 rounded-xl bg-slate-50 hover:bg-orange-50 border border-slate-200/80 hover:border-orange-200 text-slate-700 hover:text-orange-700 text-xs font-bold transition text-center">
                        Vehículos
                    </a>
                    <a href="{{ route('tecnico.historial.index') }}" class="p-2.5 rounded-xl bg-slate-50 hover:bg-orange-50 border border-slate-200/80 hover:border-orange-200 text-slate-700 hover:text-orange-700 text-xs font-bold transition text-center">
                        Historial
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
