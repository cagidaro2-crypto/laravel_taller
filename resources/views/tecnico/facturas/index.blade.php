@extends('layouts.tecnico')
@section('title', 'Facturación')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Facturación</h1>
            <p class="text-slate-600 mt-1 text-sm">Gestiona y genera facturas para los clientes del taller</p>
        </div>
        <a href="{{ route('tecnico.facturas.create') }}" 
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Generar Factura a Cliente
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Buscador y Filtros -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 mb-6">
        <form method="GET" action="{{ route('tecnico.facturas.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por N° factura, cliente, placa..."
                       class="w-full border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            <select name="estado" class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                <option value="">Todos los estados</option>
                <option value="Pendiente" {{ request('estado') === 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="Pagada" {{ request('estado') === 'Pagada' ? 'selected' : '' }}>Pagada</option>
            </select>
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
                Filtrar
            </button>
            @if(request()->hasAny(['buscar', 'estado']))
                <a href="{{ route('tecnico.facturas.index') }}" class="text-slate-500 hover:text-slate-800 text-sm">Limpiar</a>
            @endif
        </form>
    </div>

    <!-- Listado -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        @if($facturas->isEmpty())
            <div class="p-12 text-center text-slate-500">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                <p class="font-semibold text-slate-700">No se encontraron facturas</p>
                <p class="text-xs text-slate-400 mt-1">Genera una factura desde una orden de trabajo o usando el botón superior.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3 text-left">N° Factura</th>
                            <th class="px-5 py-3 text-left">Fecha</th>
                            <th class="px-5 py-3 text-left">Cliente</th>
                            <th class="px-5 py-3 text-left">Vehículo / Orden</th>
                            <th class="px-5 py-3 text-right">Total</th>
                            <th class="px-5 py-3 text-center">Estado</th>
                            <th class="px-5 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($facturas as $factura)
                            @php
                                $vehiculo = $factura->orden->vehiculo ?? ($factura->cotizacion->vehiculo ?? null);
                            @endphp
                            <tr class="hover:bg-slate-50/60">
                                <td class="px-5 py-3.5 font-mono font-bold text-slate-900">{{ $factura->numero_factura }}</td>
                                <td class="px-5 py-3.5">{{ $factura->fecha->format('d/m/Y') }}</td>
                                <td class="px-5 py-3.5 font-medium text-slate-900">{{ $factura->cliente->usuario->nombre ?? 'N/A' }}</td>
                                <td class="px-5 py-3.5">
                                    @if($vehiculo)
                                        <span class="font-mono bg-slate-100 px-2 py-0.5 rounded text-xs text-slate-800">{{ $vehiculo->placa }}</span>
                                    @endif
                                    @if($factura->orden)
                                        <span class="text-xs text-slate-400 ml-1">Orden #{{ $factura->orden->id_orden }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right font-black text-slate-900">${{ number_format($factura->total, 2) }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    @php
                                        $badge = match($factura->estado){ 'Pagada'=>'bg-emerald-100 text-emerald-700','Anulada'=>'bg-rose-100 text-rose-700',default=>'bg-amber-100 text-amber-700' };
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $badge }}">{{ $factura->estado }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('tecnico.facturas.show', $factura) }}" 
                                           class="text-indigo-600 hover:text-indigo-800 font-semibold text-xs px-2.5 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 transition">
                                            Ver
                                        </a>
                                        <a href="{{ route('tecnico.facturas.pdf', $factura) }}" 
                                           class="text-orange-600 hover:text-orange-800 font-semibold text-xs px-2.5 py-1.5 rounded-lg bg-orange-50 hover:bg-orange-100 transition flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            PDF
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $facturas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
