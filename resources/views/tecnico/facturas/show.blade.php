@extends('layouts.tecnico')
@section('title', 'Factura ' . $factura->numero_factura)

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('tecnico.facturas.index') }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 flex items-center gap-3">
                    Factura <span class="font-mono text-indigo-600">{{ $factura->numero_factura }}</span>
                </h1>
                <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Fecha: {{ $factura->fecha->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('tecnico.facturas.pdf', $factura) }}" 
               class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Descargar PDF
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 border-b border-slate-100 pb-6">
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Cliente</p>
                <p class="text-lg font-bold text-slate-900 mt-1">{{ $factura->cliente->usuario->nombre ?? 'N/A' }}</p>
                <p class="text-sm text-slate-600 mt-0.5">Doc: {{ $factura->cliente->documento ?? 'S/D' }}</p>
                <p class="text-sm text-slate-600">Correo: {{ $factura->cliente->usuario->correo ?? 'N/A' }}</p>
                <p class="text-sm text-slate-600">Teléfono: {{ $factura->cliente->usuario->telefono ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Estado y Monto</p>
                @php
                    $badge = match($factura->estado){ 'Pagada'=>'bg-emerald-100 text-emerald-700 border-emerald-200', 'Anulada'=>'bg-rose-100 text-rose-700 border-rose-200', default=>'bg-amber-100 text-amber-700 border-amber-200' };
                @endphp
                <div class="mt-1">
                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $badge }}">{{ $factura->estado }}</span>
                </div>
                <p class="text-3xl font-black text-slate-900 mt-2 font-mono">${{ number_format($factura->total, 2) }}</p>
                <p class="text-xs text-slate-500">Subtotal: ${{ number_format($factura->subtotal, 2) }} | IVA 19%: ${{ number_format($factura->impuesto, 2) }}</p>
            </div>
        </div>

        @php
            $vehiculo = $factura->orden->vehiculo ?? ($factura->cotizacion->vehiculo ?? null);
        @endphp
        @if($vehiculo)
            <div class="bg-slate-50 p-4 rounded-xl flex items-center justify-between">
                <div>
                    <span class="text-xs text-slate-400 font-semibold uppercase">Vehículo</span>
                    <p class="font-bold text-slate-800">{{ $vehiculo->marca }} {{ $vehiculo->modelo }} ({{ $vehiculo->anio ?? 'N/A' }})</p>
                </div>
                <div class="font-mono bg-white border border-slate-200 px-3 py-1 rounded-lg text-slate-900 font-bold">
                    {{ $vehiculo->placa }}
                </div>
            </div>
        @endif

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('tecnico.facturas.pdf', $factura) }}" 
               class="bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Descargar Comprobante PDF
            </a>
        </div>
    </div>
</div>
@endsection
