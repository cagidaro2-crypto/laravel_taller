@extends('layouts.cliente')
@section('title','Mi Portal de Cliente')

@section('content')
<div class="space-y-8">

    <!-- ══════════════════════════════════════════════════
         BANNER DE BIENVENIDA MODERNO
    ══════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 border border-indigo-900/40 p-6 sm:p-8 text-white shadow-xl shadow-slate-950/20">
        <!-- Luces ambientales de fondo -->
        <div class="absolute -right-12 -top-12 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-12 -bottom-12 w-64 h-64 bg-orange-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 text-xs font-semibold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Portal Activo · Cliente VIP
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    ¡Hola de nuevo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 via-purple-300 to-orange-300">{{ auth()->user()->nombre }}</span>! 👋
                </h1>
                <p class="text-slate-300 text-sm max-w-xl leading-relaxed">
                    Sigue en tiempo real la reparación, pintura y cotizaciones de tus vehículos con total trazabilidad y descarga tus comprobantes en un clic.
                </p>
            </div>

            <!-- Acciones rápidas en el banner -->
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('cliente.citas.create') }}" 
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-bold text-xs uppercase tracking-wider px-5 py-3 rounded-2xl shadow-lg shadow-orange-500/25 transition-all hover:scale-105 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"/></svg>
                    <span>Agendar Cita</span>
                </a>
                <a href="{{ route('cliente.vehiculos.create') }}" 
                   class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold text-xs uppercase tracking-wider px-4 py-3 rounded-2xl border border-white/15 backdrop-blur-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h1m6-9h5l3 3v4h-1"/></svg>
                    <span>Registrar Vehículo</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         TARJETAS KPI / MÉTRICAS PRINCIPALES
    ══════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <!-- 1. Mis Vehículos -->
        <a href="{{ route('cliente.vehiculos.index') }}" 
           class="group bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:border-indigo-300 hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300 relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 group-hover:text-indigo-600 transition-colors">Mis Vehículos</span>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h1m6-9h5l3 3v4h-1"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-black text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $misVehiculos }}</p>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                    <span>En garaje o taller</span> →
                </p>
            </div>
        </a>

        <!-- 2. Citas Activas -->
        <a href="{{ route('cliente.citas.index') }}" 
           class="group bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:border-amber-300 hover:shadow-xl hover:shadow-amber-500/5 transition-all duration-300 relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 group-hover:text-amber-600 transition-colors">Citas de Taller</span>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 group-hover:bg-amber-500 group-hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-black text-slate-900 group-hover:text-amber-600 transition-colors">{{ $misCitas }}</p>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                    <span>Agendadas y activas</span> →
                </p>
            </div>
        </a>

        <!-- 3. Cotizaciones -->
        <a href="{{ route('cliente.cotizaciones.index') }}" 
           class="group bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:border-emerald-300 hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300 relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 group-hover:text-emerald-600 transition-colors">Cotizaciones</span>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-baseline gap-2">
                    <p class="text-3xl font-black text-slate-900 group-hover:text-emerald-600 transition-colors">{{ $cotizacionesPendientes }}</p>
                    @if($cotizacionesPendientes > 0)
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-amber-100 text-amber-800 animate-pulse">Requiere acción</span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                    <span>Por revisar o aprobar</span> →
                </p>
            </div>
        </a>

        <!-- 4. Mis Facturas -->
        <a href="{{ route('cliente.facturas.index') }}" 
           class="group bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:border-rose-300 hover:shadow-xl hover:shadow-rose-500/5 transition-all duration-300 relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 group-hover:text-rose-600 transition-colors">Mis Facturas</span>
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 group-hover:bg-rose-600 group-hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-baseline gap-2">
                    <p class="text-3xl font-black text-slate-900 group-hover:text-rose-600 transition-colors">{{ $misFacturas }}</p>
                    @if($facturasPendientes > 0)
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-rose-100 text-rose-800">{{ $facturasPendientes }} por pagar</span>
                    @else
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-800">Al día ✓</span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                    <span>Descarga tus comprobantes</span> →
                </p>
            </div>
        </a>
    </div>

    <!-- ══════════════════════════════════════════════════
         SECCIÓN SECUNDARIA: Vehículos & Actividad Reciente
    ══════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Lista de Vehículos Recientes (2 Columnas) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-slate-100 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Mis Vehículos Registrados</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Vehículos asociados a tu expediente en el taller</p>
                </div>
                <a href="{{ route('cliente.vehiculos.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
                    Ver todos ({{ $misVehiculos }}) →
                </a>
            </div>

            @if($ultimosVehiculos->isEmpty())
                <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <p class="text-sm font-bold text-slate-800">Aún no tienes vehículos registrados</p>
                    <p class="text-xs text-slate-500 mt-1 mb-4">Registra tu placa para solicitar cotizaciones y seguimiento fotográfico.</p>
                    <a href="{{ route('cliente.vehiculos.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition">
                        <span>Registrar mi primer vehículo</span>
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach($ultimosVehiculos as $veh)
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-indigo-300 transition-all group">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2.5 py-1 rounded-lg bg-amber-100 text-amber-900 font-mono text-xs font-black tracking-wider border border-amber-300/80">
                                {{ $veh->placa }}
                            </span>
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-slate-200 text-slate-700">
                                {{ $veh->estado->nombre ?? 'Activo' }}
                            </span>
                        </div>
                        <p class="font-bold text-slate-900 text-sm truncate">{{ $veh->marca }} {{ $veh->modelo }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $veh->color ?? 'Color no reg.' }} · Año {{ $veh->anio ?? 'N/A' }}</p>
                        
                        <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-between">
                            <a href="{{ route('cliente.vehiculos.show', $veh) }}" class="text-xs font-bold text-indigo-600 group-hover:text-indigo-800 transition flex items-center gap-1">
                                <span>Ver detalle</span> →
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Columna Lateral: Facturas Recientes / Acceso Rápido (1 Columna) -->
        <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-slate-100 space-y-6 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Facturación</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Tus comprobantes emitidos</p>
                    </div>
                    <a href="{{ route('cliente.facturas.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
                        Ver todas →
                    </a>
                </div>

                @if($ultimasFacturas->isEmpty())
                    <div class="p-6 text-center bg-slate-50 rounded-2xl border border-slate-100">
                        <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-xs font-semibold text-slate-600">No hay facturas emitidas aún</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($ultimasFacturas as $fac)
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-black text-slate-900 font-mono">{{ $fac->numero_factura }}</p>
                                <p class="text-[11px] text-slate-500">{{ date('d/m/Y', strtotime($fac->fecha)) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-black text-slate-900">${{ number_format($fac->total, 2) }}</p>
                                <a href="{{ route('cliente.facturas.pdf', $fac) }}" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-0.5 justify-end mt-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    <span>Descargar PDF</span>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="pt-4 border-t border-slate-100">
                <a href="{{ route('cliente.notificaciones') }}" class="w-full py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span>Centro de Notificaciones</span>
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
