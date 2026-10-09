@extends('layouts.admin')
@section('title', 'Cotización #' . $cotizacione->id_cotizacion)

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Cotización #{{ $cotizacione->id_cotizacion }}</h1>
            @php
                $badgeClasses = match($cotizacione->estado) {
                    'Aprobada'  => 'bg-emerald-100 text-emerald-800',
                    'Rechazada' => 'bg-rose-100 text-rose-700',
                    'Vencida'   => 'bg-slate-100 text-slate-600',
                    default     => 'bg-amber-100 text-amber-800',
                };
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClasses }}">
                {{ $cotizacione->estado }}
            </span>
        </div>
        <p class="text-sm text-slate-500 mt-1">Presupuesto detallado para cliente y vehículo</p>
    </div>
    <div class="flex items-center gap-2">
        @if($cotizacione->estado === 'Aprobada')
            <form method="POST" action="{{ route('admin.cotizaciones.factura', $cotizacione) }}">
                @csrf
                <button class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition shadow-sm cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Convertir en factura
                </button>
            </form>
        @endif
        @if($cotizacione->estado === 'Pendiente')
            <form method="POST" action="{{ route('admin.cotizaciones.rechazar', $cotizacione) }}">
                @csrf @method('PATCH')
                <button class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 text-sm font-semibold transition shadow-sm cursor-pointer" onclick="return confirm('¿Marcar como rechazada?')">
                    Rechazar
                </button>
            </form>
        @endif
        <a href="{{ route('admin.cotizaciones.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 transition shadow-sm">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Volver
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-6xl mx-auto mb-6">
    {{-- Datos Generales --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
        <h2 class="text-base font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-orange-500"></span>
            Datos Generales
        </h2>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between py-1.5 border-b border-slate-50">
                <dt class="text-slate-500 font-medium">Cliente</dt>
                <dd class="text-slate-800 font-semibold">{{ $cotizacione->cliente->usuario->nombre }}</dd>
            </div>
            <div class="flex justify-between py-1.5 border-b border-slate-50">
                <dt class="text-slate-500 font-medium">Vehículo</dt>
                <dd class="text-slate-800 font-semibold">{{ $cotizacione->vehiculo?->placa ?? '—' }} <span class="text-xs text-slate-400 font-normal">({{ $cotizacione->vehiculo?->marca ?? '' }} {{ $cotizacione->vehiculo?->modelo ?? '' }})</span></dd>
            </div>
            <div class="flex justify-between py-1.5 border-b border-slate-50">
                <dt class="text-slate-500 font-medium">Creada por</dt>
                <dd class="text-slate-700 font-medium">{{ $cotizacione->usuario->nombre }}</dd>
            </div>
            <div class="flex justify-between py-1.5 border-b border-slate-50">
                <dt class="text-slate-500 font-medium">Fecha Emisión</dt>
                <dd class="text-slate-700 font-medium">{{ $cotizacione->fecha->format('d/m/Y') }}</dd>
            </div>
            <div class="flex justify-between py-1.5 border-b border-slate-50">
                <dt class="text-slate-500 font-medium">Fecha Vencimiento</dt>
                <dd class="text-slate-700 font-medium">{{ $cotizacione->fecha_vencimiento?->format('d/m/Y') ?? '—' }}</dd>
            </div>
            <div class="pt-2">
                <dt class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Observaciones</dt>
                <dd class="text-slate-700 text-xs bg-slate-50 p-3 rounded-xl border border-slate-100">{{ $cotizacione->observaciones ?? 'Sin observaciones registradas.' }}</dd>
            </div>
        </dl>
    </div>

    {{-- Resumen Económico --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 flex flex-col justify-between">
        <div>
            <h2 class="text-base font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Totales y Liquidación
            </h2>
            <div class="space-y-3">
                <div class="flex justify-between text-sm py-2 border-b border-slate-50">
                    <span class="text-slate-500">Subtotal de servicios y partes</span>
                    <span class="font-semibold text-slate-800">${{ number_format($cotizacione->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm py-2 border-b border-slate-50">
                    <span class="text-slate-500">IVA / Impuesto aplicado</span>
                    <span class="font-semibold text-slate-800">${{ number_format($cotizacione->impuesto, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-5 text-white mt-6 shadow-md shadow-emerald-500/20">
            <span class="text-xs uppercase tracking-wider font-semibold text-emerald-100">Total Cotizado</span>
            <div class="text-3xl font-black mt-1">${{ number_format($cotizacione->total, 2) }}</div>
        </div>
    </div>
</div>

{{-- Tabla de Servicios --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 max-w-6xl mx-auto mb-6">
    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        <h2 class="text-base font-bold text-slate-800">Servicios Incluidos</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide border-b border-slate-100 bg-slate-50/70">
                    <th class="px-4 py-3 rounded-l-xl">Servicio</th>
                    <th class="px-4 py-3 text-center">Cantidad</th>
                    <th class="px-4 py-3 text-right">Precio Unitario</th>
                    <th class="px-4 py-3 text-right rounded-r-xl">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($cotizacione->servicios as $s)
                <tr class="hover:bg-slate-50/60 transition">
                    <td class="px-4 py-3.5 font-medium text-slate-800">{{ $s->servicio->nombre }}</td>
                    <td class="px-4 py-3.5 text-center text-slate-600 font-semibold">{{ $s->cantidad }}</td>
                    <td class="px-4 py-3.5 text-right text-slate-600">${{ number_format($s->precio, 2) }}</td>
                    <td class="px-4 py-3.5 text-right font-bold text-slate-800">${{ number_format($s->subtotal, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-slate-400">Sin servicios agregados en esta cotización.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Tabla de Productos --}}
@if($cotizacione->productos && $cotizacione->productos->isNotEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 max-w-6xl mx-auto">
    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        <h2 class="text-base font-bold text-slate-800">Repuestos y Productos Incluidos</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide border-b border-slate-100 bg-slate-50/70">
                    <th class="px-4 py-3 rounded-l-xl">Producto</th>
                    <th class="px-4 py-3 text-center">Cantidad</th>
                    <th class="px-4 py-3 text-right">Precio Unitario</th>
                    <th class="px-4 py-3 text-right rounded-r-xl">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($cotizacione->productos as $p)
                <tr class="hover:bg-slate-50/60 transition">
                    <td class="px-4 py-3.5 font-medium text-slate-800">{{ $p->producto->nombre }}</td>
                    <td class="px-4 py-3.5 text-center text-slate-600 font-semibold">{{ $p->cantidad }}</td>
                    <td class="px-4 py-3.5 text-right text-slate-600">${{ number_format($p->precio_unitario, 2) }}</td>
                    <td class="px-4 py-3.5 text-right font-bold text-slate-800">${{ number_format($p->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
