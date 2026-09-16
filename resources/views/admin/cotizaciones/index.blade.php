@extends('layouts.admin')
@section('title','Cotizaciones')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-slate-800">Cotizaciones</h2>
    <a href="{{ route('admin.cotizaciones.create') }}" class="inline-flex items-center gap-2 bg-orange-500 text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-orange-600 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nueva cotización
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100">
    <div class="px-5 py-4 border-b border-slate-100">
        <form method="GET" class="flex gap-3 flex-wrap">
            <select name="estado" class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                <option value="">Todos los estados</option>
                <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected':'' }}>Pendiente</option>
                <option value="Aprobada" {{ request('estado') == 'Aprobada' ? 'selected':'' }}>Aprobada</option>
                <option value="Rechazada" {{ request('estado') == 'Rechazada' ? 'selected':'' }}>Rechazada</option>
                <option value="Vencida" {{ request('estado') == 'Vencida' ? 'selected':'' }}>Vencida</option>
            </select>
            <input type="text" name="cliente" placeholder="Buscar cliente..." class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" value="{{ request('cliente') }}">
            <button class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-xl">Filtrar</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide border-b border-slate-100">
                    <th class="px-5 py-3.5">#</th>
                    <th class="px-5 py-3.5">Cliente</th>
                    <th class="px-5 py-3.5">Vehículo</th>
                    <th class="px-5 py-3.5">Fecha</th>
                    <th class="px-5 py-3.5">Vence</th>
                    <th class="px-5 py-3.5">Total</th>
                    <th class="px-5 py-3.5">Estado</th>
                    <th class="px-5 py-3.5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($cotizaciones as $c)
                @php
                    $estadoColor = match($c->estado ?? '') {
                        'Aprobada'  => 'bg-green-100 text-green-700',
                        'Rechazada' => 'bg-red-100 text-red-700',
                        'Vencida'   => 'bg-slate-100 text-slate-600',
                        'Pendiente' => 'bg-amber-100 text-amber-700',
                        default     => 'bg-slate-100 text-slate-600',
                    };
                @endphp
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5 text-slate-500 font-mono text-xs">{{ $c->id_cotizacion }}</td>
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $c->cliente->usuario->nombre }}</td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $c->vehiculo?->placa ?? '—' }} <span class="text-slate-400 font-normal">{{ $c->vehiculo?->marca ?? '' }}</span></td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $c->fecha->format('d/m/Y') }}</td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $c->fecha_vencimiento?->format('d/m/Y') ?? '—' }}</td>
                    <td class="px-5 py-3.5 font-semibold text-slate-800">${{ number_format($c->total, 2) }}</td>
                    <td class="px-5 py-3.5"><span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $estadoColor }}">{{ $c->estado }}</span></td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.cotizaciones.show',$c) }}" class="text-xs text-slate-600 border border-slate-200 px-3 py-1.5 rounded-lg hover:bg-slate-50 transition-colors">Ver</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-5 py-10 text-center text-slate-400">No hay cotizaciones registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($cotizaciones->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">{{ $cotizaciones->links() }}</div>
    @endif
</div>
@endsection
