@extends('layouts.admin')
@section('title','Reporte de Consumo de Materiales')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.reportes.index') }}" class="text-slate-600 hover:text-slate-800 text-sm font-medium">← Volver</a>
    
    @if(!$sinDatos)
        <a href="{{ route('admin.reportes.exportar', ['tipo' => 'consumo', 'fecha_desde' => $request->fecha_desde, 'fecha_hasta' => $request->fecha_hasta]) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 font-semibold text-sm">
            📊 Descargar CSV
        </a>
    @endif
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
    <h2 class="text-xl font-bold text-slate-800 mb-4">Reporte de Consumo de Materiales</h2>
    <div class="text-sm text-slate-600 mb-6">
        <p>Período: <span class="font-semibold">{{ \Carbon\Carbon::parse($request->fecha_desde)->format('d/m/Y') }}</span> - <span class="font-semibold">{{ \Carbon\Carbon::parse($request->fecha_hasta)->format('d/m/Y') }}</span></p>
    </div>
</div>

@if(!$sinDatos)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl border border-orange-200 p-6">
            <p class="text-sm text-orange-700 mb-1">Total de Productos Consumidos</p>
            <p class="text-3xl font-bold text-orange-900">{{ $consumo->count() }}</p>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl border border-blue-200 p-6">
            <p class="text-sm text-blue-700 mb-1">Inversión Total en Materiales</p>
            <p class="text-3xl font-bold text-blue-900">${{ number_format($consumo->sum('total_subtotal'), 2) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200">
                        <th class="px-6 py-3 text-left font-semibold text-slate-700">Producto</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-700">Categoría</th>
                        <th class="px-6 py-3 text-center font-semibold text-slate-700">Cantidad Total</th>
                        <th class="px-6 py-3 text-right font-semibold text-slate-700">Costo Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($consumo as $c)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $c->producto->nombre }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $c->producto->categoria->nombre }}</td>
                        <td class="px-6 py-4 text-center text-slate-600">{{ $c->total_cantidad }}</td>
                        <td class="px-6 py-4 text-right font-semibold text-slate-800">${{ number_format($c->total_subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-100 border-t-2 border-slate-300">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right font-semibold text-slate-800">TOTAL INVERTIDO:</td>
                        <td class="px-6 py-4 text-right font-bold text-orange-600 text-lg">${{ number_format($consumo->sum('total_subtotal'), 2) }}</td>
                    </tr>
                </tfoot>
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
