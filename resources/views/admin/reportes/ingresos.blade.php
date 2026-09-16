@extends('layouts.admin')
@section('title','Reporte de Ingresos')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.reportes.index') }}" class="text-slate-600 hover:text-slate-800 text-sm font-medium">← Volver</a>
    
    @if(!$sinDatos)
        <a href="{{ route('admin.reportes.exportar', ['tipo' => 'ingresos', 'fecha_desde' => $request->fecha_desde, 'fecha_hasta' => $request->fecha_hasta]) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 font-semibold text-sm">
            📊 Descargar CSV
        </a>
    @endif
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
    <h2 class="text-xl font-bold text-slate-800 mb-4">Reporte de Ingresos</h2>
    <div class="text-sm text-slate-600 mb-6">
        <p>Período: <span class="font-semibold">{{ \Carbon\Carbon::parse($request->fecha_desde)->format('d/m/Y') }}</span> - <span class="font-semibold">{{ \Carbon\Carbon::parse($request->fecha_hasta)->format('d/m/Y') }}</span></p>
    </div>
</div>

@if(!$sinDatos)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl border border-green-200 p-6">
            <p class="text-sm text-green-700 mb-1">Total de Ventas</p>
            <p class="text-3xl font-bold text-green-900">{{ $ventas->count() }}</p>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl border border-blue-200 p-6">
            <p class="text-sm text-blue-700 mb-1">Subtotal</p>
            <p class="text-3xl font-bold text-blue-900">${{ number_format($totalIngresos - $totalImpuesto, 2) }}</p>
        </div>
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl border border-orange-200 p-6">
            <p class="text-sm text-orange-700 mb-1">Total Ingresos</p>
            <p class="text-3xl font-bold text-orange-900">${{ number_format($totalIngresos, 2) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200">
                        <th class="px-6 py-3 text-left font-semibold text-slate-700">#</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-700">Cliente</th>
                        <th class="px-6 py-3 text-center font-semibold text-slate-700">Fecha</th>
                        <th class="px-6 py-3 text-right font-semibold text-slate-700">Subtotal</th>
                        <th class="px-6 py-3 text-right font-semibold text-slate-700">Impuesto</th>
                        <th class="px-6 py-3 text-right font-semibold text-slate-700">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($ventas as $v)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $v->id_venta }}</td>
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $v->cliente->usuario->nombre ?? '—' }}</td>
                        <td class="px-6 py-4 text-center text-slate-600">{{ $v->fecha->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-right text-slate-600">${{ number_format($v->subtotal, 2) }}</td>
                        <td class="px-6 py-4 text-right text-slate-600">${{ number_format($v->impuesto, 2) }}</td>
                        <td class="px-6 py-4 text-right font-semibold text-slate-800">${{ number_format($v->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-100 border-t-2 border-slate-300">
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-right font-semibold text-slate-800">TOTAL:</td>
                        <td class="px-6 py-4 text-right font-bold text-slate-800">${{ number_format($totalImpuesto, 2) }}</td>
                        <td class="px-6 py-4 text-right font-bold text-orange-600 text-lg">${{ number_format($totalIngresos, 2) }}</td>
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
