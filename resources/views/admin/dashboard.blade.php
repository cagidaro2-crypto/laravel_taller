@extends('layouts.admin')
@section('title','Panel Administrativo')

@section('content')
<div class="space-y-8">

    <!-- ══════════════════════════════════════════════════
         BANNER DE BIENVENIDA ADMINISTRATIVO
    ══════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-slate-950 to-slate-900 border border-slate-800 p-6 sm:p-8 text-white shadow-xl shadow-slate-950/20">
        <!-- Luces ambientales de fondo -->
        <div class="absolute -right-12 -top-12 w-64 h-64 bg-orange-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-12 -bottom-12 w-64 h-64 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-500/20 border border-orange-400/30 text-orange-300 text-xs font-semibold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Sistema en Línea · Administrador General
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Bienvenido, <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 via-amber-300 to-yellow-400">{{ auth()->user()->nombre }}</span> 👑
                </h1>
                <p class="text-slate-300 text-sm max-w-xl leading-relaxed">
                    Control central de operaciones de latonería, asignación a técnicos, facturación y stock de repuestos.
                </p>
            </div>

            <!-- Acciones rápidas en el banner -->
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.ordenes.create') }}" 
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-bold text-xs uppercase tracking-wider px-5 py-3 rounded-2xl shadow-lg shadow-orange-500/25 transition-all hover:scale-105 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"/></svg>
                    <span>Nueva Orden</span>
                </a>
                <a href="{{ route('admin.reportes.index') }}" 
                   class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold text-xs uppercase tracking-wider px-4 py-3 rounded-2xl border border-white/15 backdrop-blur-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Ver Reportes</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         TARJETAS KPI / MÉTRICAS ADMINISTRATIVAS
    ══════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <!-- 1. Órdenes Activas -->
        <a href="{{ route('admin.ordenes.index') }}" 
           class="group bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:border-orange-300 hover:shadow-xl hover:shadow-orange-500/5 transition-all duration-300 relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 group-hover:text-orange-600 transition-colors">Órdenes Activas</span>
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 group-hover:bg-orange-500 group-hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-black text-slate-900 group-hover:text-orange-600 transition-colors">{{ $totalOrdenes }}</p>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                    <span>En proceso de reparación</span> →
                </p>
            </div>
        </a>

        <!-- 2. Clientes Registrados -->
        <a href="{{ route('admin.usuarios.index') }}" 
           class="group bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:border-amber-300 hover:shadow-xl hover:shadow-amber-500/5 transition-all duration-300 relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 group-hover:text-amber-600 transition-colors">Total Clientes</span>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 group-hover:bg-amber-500 group-hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-black text-slate-900 group-hover:text-amber-600 transition-colors">{{ $totalClientes }}</p>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                    <span>Expedientes en el taller</span> →
                </p>
            </div>
        </a>

        <!-- 3. Stock Bajo -->
        <a href="{{ route('admin.inventario.index') }}" 
           class="group bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:border-rose-300 hover:shadow-xl hover:shadow-rose-500/5 transition-all duration-300 relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 group-hover:text-rose-600 transition-colors">Alertas Stock</span>
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 group-hover:bg-rose-500 group-hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-baseline gap-2">
                    <p class="text-3xl font-black text-slate-900 group-hover:text-rose-600 transition-colors">{{ $bajosStock }}</p>
                    @if($bajosStock > 0)
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-rose-100 text-rose-800 animate-pulse">Stock crítico</span>
                    @else
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-800">Óptimo</span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                    <span>Gestionar inventario</span> →
                </p>
            </div>
        </a>

        <!-- 4. Citas de Hoy -->
        <a href="{{ route('admin.dashboard') }}" 
           class="group bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:border-emerald-300 hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300 relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 group-hover:text-emerald-600 transition-colors">Citas Hoy</span>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-black text-slate-900 group-hover:text-emerald-600 transition-colors">{{ $citasHoy }}</p>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                    <span>{{ date('d/m/Y') }}</span>
                </p>
            </div>
        </a>
    </div>

    <!-- ══════════════════════════════════════════════════
         SECCIÓN PRINCIPAL: Órdenes Recientes & Alertas
    ══════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Órdenes de Trabajo Recientes (2 Columnas) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-slate-100 space-y-5">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Órdenes de Trabajo Recientes</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Seguimiento operativo de vehículos en taller</p>
                </div>
                <a href="{{ route('admin.ordenes.index') }}" class="text-xs font-bold text-orange-600 hover:text-orange-800 transition">
                    Ver todas →
                </a>
            </div>

            @if($ultimasOrdenes->isEmpty())
                <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <p class="text-sm font-semibold text-slate-600">No hay órdenes de trabajo registradas recientemente</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-slate-400 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="py-3 px-4">Orden</th>
                                <th class="py-3 px-4">Vehículo</th>
                                <th class="py-3 px-4">Cliente</th>
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
                                    {{ $ord->vehiculo?->cliente?->usuario?->nombre ?? 'N/A' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                        {{ $ord->estado->nombre ?? 'En Proceso' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ route('admin.ordenes.show', $ord) }}" class="text-xs font-bold text-orange-600 hover:text-orange-800">
                                        Detalle →
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Columna Lateral: Stock Bajo y Acciones Rápidas (1 Columna) -->
        <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-slate-100 space-y-6 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Repuestos Críticos</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Stock por debajo del mínimo</p>
                    </div>
                    <a href="{{ route('admin.inventario.index') }}" class="text-xs font-bold text-orange-600 hover:text-orange-800 transition">
                        Inventario →
                    </a>
                </div>

                @if($productosBajoStock->isEmpty())
                    <div class="p-6 text-center bg-slate-50 rounded-2xl border border-slate-100">
                        <svg class="w-8 h-8 text-emerald-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-xs font-bold text-emerald-700">Inventario al día</p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Todos los repuestos tienen existencias suficientes.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($productosBajoStock as $item)
                        <div class="p-3 rounded-2xl bg-rose-50/60 border border-rose-100 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-slate-900">{{ $item->producto->nombre ?? 'Producto' }}</p>
                                <p class="text-[11px] text-rose-600 font-semibold">Quedan {{ $item->cantidad }} (Mín: {{ $item->stock_minimo }})</p>
                            </div>
                            <a href="{{ route('admin.inventario.index') }}" class="text-[11px] font-bold text-rose-700 hover:text-rose-900">
                                Reponer →
                            </a>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Accesos Rápidos de Gestión -->
            <div class="pt-4 border-t border-slate-100 space-y-2">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Accesos Directos</p>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('admin.cotizaciones.create') }}" class="p-2.5 rounded-xl bg-slate-50 hover:bg-orange-50 border border-slate-200/80 hover:border-orange-200 text-slate-700 hover:text-orange-700 text-xs font-bold transition text-center">
                        + Cotización
                    </a>
                    <a href="{{ route('admin.facturas.index') }}" class="p-2.5 rounded-xl bg-slate-50 hover:bg-orange-50 border border-slate-200/80 hover:border-orange-200 text-slate-700 hover:text-orange-700 text-xs font-bold transition text-center">
                        Facturas
                    </a>
                    <a href="{{ route('admin.proveedores.index') }}" class="p-2.5 rounded-xl bg-slate-50 hover:bg-orange-50 border border-slate-200/80 hover:border-orange-200 text-slate-700 hover:text-orange-700 text-xs font-bold transition text-center">
                        Proveedores
                    </a>
                    <a href="{{ route('admin.ventas.create') }}" class="p-2.5 rounded-xl bg-slate-50 hover:bg-orange-50 border border-slate-200/80 hover:border-orange-200 text-slate-700 hover:text-orange-700 text-xs font-bold transition text-center">
                        + Venta Directa
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
