@extends('layouts.admin')
@section('title','Reporte de Productividad')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.reportes.index') }}" class="text-slate-600 hover:text-slate-800 text-sm font-medium">← Volver</a>
    
    @if(!$sinDatos)
        <a href="{{ route('admin.reportes.exportar', ['tipo' => 'productividad', 'fecha_desde' => $request->fecha_desde, 'fecha_hasta' => $request->fecha_hasta]) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 font-semibold text-sm">
            📊 Descargar CSV
        </a>
    @endif
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
    <h2 class="text-xl font-bold text-slate-800 mb-4">Reporte de Productividad</h2>
    <div class="text-sm text-slate-600 mb-6">
        <p>Período: <span class="font-semibold">{{ \Carbon\Carbon::parse($request->fecha_desde)->format('d/m/Y') }}</span> - <span class="font-semibold">{{ \Carbon\Carbon::parse($request->fecha_hasta)->format('d/m/Y') }}</span></p>
    </div>
</div>

@if(!$sinDatos)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl border border-blue-200 p-6">
            <p class="text-sm text-blue-700 mb-1">Total de Órdenes Completadas</p>
            <p class="text-3xl font-bold text-blue-900">{{ $ordenesCompletadas }}</p>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl border border-green-200 p-6">
            <p class="text-sm text-green-700 mb-1">Total de Técnicos</p>
            <p class="text-3xl font-bold text-green-900">{{ $tecnicos->count() }}</p>
        </div>
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl border border-orange-200 p-6">
            <p class="text-sm text-orange-700 mb-1">Promedio por Técnico</p>
            <p class="text-3xl font-bold text-orange-900">{{ $tecnicos->count() > 0 ? round($ordenesCompletadas / $tecnicos->count(), 1) : 0 }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200">
                        <th class="px-6 py-3 text-left font-semibold text-slate-700">Técnico</th>
                        <th class="px-6 py-3 text-center font-semibold text-slate-700">Órdenes Asignadas</th>
                        <th class="px-6 py-3 text-center font-semibold text-slate-700">Porcentaje</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($tecnicos as $t)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $t->nombre }}</td>
                        <td class="px-6 py-4 text-center text-slate-600">{{ $t->ordenes_periodo }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <div class="w-24 h-2 bg-slate-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-500 rounded-full" style="width: {{ $ordenesCompletadas > 0 ? ($t->ordenes_periodo / $ordenesCompletadas * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-semibold">{{ $ordenesCompletadas > 0 ? round(($t->ordenes_periodo / $ordenesCompletadas * 100), 1) : 0 }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="bg-slate-100 border border-slate-300 rounded-2xl p-12 text-center">
        <svg class="w-16 h-16 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <p class="text-slate-600">No hay datos para el período seleccionado</p>
    </div>
@endif
@endsection
