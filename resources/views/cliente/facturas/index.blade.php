@extends('layouts.cliente')
@section('title', 'Mis Facturas')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Mis Facturas</h1>
            <p class="text-slate-600 mt-1 text-sm">Consulta y descarga los comprobantes y facturas emitidos por el taller</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Resumen de Facturación -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Total Facturas</p>
                <p class="text-3xl font-black text-slate-900 mt-1">{{ $totales['total'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-amber-600 text-xs font-semibold uppercase tracking-wider">Pendientes de Pago</p>
                <p class="text-3xl font-black text-amber-600 mt-1">{{ $totales['pendientes'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-emerald-600 text-xs font-semibold uppercase tracking-wider">Facturas Pagadas</p>
                <p class="text-3xl font-black text-emerald-600 mt-1">{{ $totales['pagadas'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- Filtros de Estado -->
    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('cliente.facturas.index') }}" 
           class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ !request('estado') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
            Todas
        </a>
        <a href="{{ route('cliente.facturas.index', ['estado' => 'Pendiente']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ request('estado') === 'Pendiente' ? 'bg-amber-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
            Pendientes
        </a>
        <a href="{{ route('cliente.facturas.index', ['estado' => 'Pagada']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ request('estado') === 'Pagada' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
            Pagadas
        </a>
    </div>

    @if($facturas->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center">
            <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-1">No tienes facturas disponibles</h3>
            <p class="text-slate-500 text-sm">Cuando el taller genere facturas por tus reparaciones o servicios, aparecerán aquí para tu consulta y descarga en PDF.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($facturas as $factura)
                @php
                    $vehiculo = $factura->orden->vehiculo ?? ($factura->cotizacion->vehiculo ?? null);
                    $saldo = $factura->saldo_pendiente;
                @endphp
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 hover:border-indigo-200 transition-all p-6">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 items-center">
                        <!-- Columna 1: Factura & Fecha -->
                        <div class="md:col-span-2 flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-lg font-mono font-bold text-slate-900">{{ $factura->numero_factura }}</h3>
                                    @php
                                        $badgeColor = match($factura->estado) {
                                            'Pagada'   => 'bg-emerald-100 text-emerald-700',
                                            'Anulada'  => 'bg-rose-100 text-rose-700',
                                            default    => 'bg-amber-100 text-amber-700',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $badgeColor }}">
                                        {{ $factura->estado }}
                                    </span>
                                </div>
                                <p class="text-slate-500 text-xs mt-1">Emisión: {{ $factura->fecha->format('d/m/Y') }}</p>
                                @if($vehiculo)
                                    <p class="text-slate-700 text-xs font-semibold mt-1">
                                        Vehículo: <span class="font-mono bg-slate-100 px-1.5 py-0.5 rounded text-slate-800">{{ $vehiculo->placa }}</span> ({{ $vehiculo->marca }} {{ $vehiculo->modelo }})
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Columna 2: Origen -->
                        <div>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Concepto / Origen</p>
                            @if($factura->orden)
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg mt-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Orden #{{ $factura->orden->id_orden }}
                                </span>
                            @elseif($factura->cotizacion)
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg mt-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Cotización #{{ $factura->cotizacion->id_cotizacion }}
                                </span>
                            @else
                                <span class="text-xs text-slate-500 mt-1 block">Servicio Directo</span>
                            @endif
                        </div>

                        <!-- Columna 3: Montos -->
                        <div>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Factura</p>
                            <p class="text-xl font-black text-slate-900 mt-0.5">${{ number_format($factura->total, 2) }}</p>
                            @if($saldo > 0)
                                <p class="text-xs text-amber-600 font-semibold mt-0.5">Saldo: ${{ number_format($saldo, 2) }}</p>
                            @else
                                <p class="text-xs text-emerald-600 font-semibold mt-0.5">Pagada al 100%</p>
                            @endif
                        </div>

                        <!-- Columna 4: Botones -->
                        <div class="flex flex-col sm:flex-row md:flex-col gap-2 justify-end">
                            <a href="{{ route('cliente.facturas.show', $factura) }}" 
                               class="inline-flex items-center justify-center gap-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-semibold px-4 py-2.5 rounded-xl transition text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Ver Detalle
                            </a>
                            <a href="{{ route('cliente.facturas.pdf', $factura) }}" 
                               class="inline-flex items-center justify-center gap-2 bg-orange-600 text-white hover:bg-orange-700 font-semibold px-4 py-2.5 rounded-xl transition text-xs shadow-sm shadow-orange-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Descargar PDF
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="mt-6">
                {{ $facturas->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
