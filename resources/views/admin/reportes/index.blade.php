@extends('layouts.admin')
@section('title','Reportes')

@section('content')
<h2 class="text-xl font-bold text-slate-800 mb-6">Reportes</h2>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    {{-- Reporte de Productividad --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Productividad</h3>
                <p class="text-sm text-slate-600">Órdenes completadas por técnico</p>
            </div>
            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>

        <form action="{{ route('admin.reportes.productividad') }}" method="GET" class="space-y-3">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Desde</label>
                <input type="date" name="fecha_desde" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm" value="{{ date('Y-m-01') }}" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Hasta</label>
                <input type="date" name="fecha_hasta" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm" value="{{ date('Y-m-d') }}" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white text-sm font-semibold py-2 rounded-lg hover:bg-blue-600 transition-colors">Ver Reporte</button>
        </form>
    </div>

    {{-- Reporte de Ingresos --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Ingresos</h3>
                <p class="text-sm text-slate-600">Ventas y ganancias por período</p>
            </div>
            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>

        <form action="{{ route('admin.reportes.ingresos') }}" method="GET" class="space-y-3">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Desde</label>
                <input type="date" name="fecha_desde" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm" value="{{ date('Y-m-01') }}" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Hasta</label>
                <input type="date" name="fecha_hasta" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm" value="{{ date('Y-m-d') }}" required>
            </div>
            <button type="submit" class="w-full bg-green-500 text-white text-sm font-semibold py-2 rounded-lg hover:bg-green-600 transition-colors">Ver Reporte</button>
        </form>
    </div>

    {{-- Reporte de Consumo de Materiales --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Consumo de Materiales</h3>
                <p class="text-sm text-slate-600">Productos utilizados por período</p>
            </div>
            <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10L4 17m8-10l8 4"/></svg>
        </div>

        <form action="{{ route('admin.reportes.consumo') }}" method="GET" class="space-y-3">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Desde</label>
                <input type="date" name="fecha_desde" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm" value="{{ date('Y-m-01') }}" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Hasta</label>
                <input type="date" name="fecha_hasta" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm" value="{{ date('Y-m-d') }}" required>
            </div>
            <button type="submit" class="w-full bg-orange-500 text-white text-sm font-semibold py-2 rounded-lg hover:bg-orange-600 transition-colors">Ver Reporte</button>
        </form>
    </div>
</div>
@endsection
