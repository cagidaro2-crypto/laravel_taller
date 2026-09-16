@extends('layouts.admin')
@section('title','Ventas')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-slate-800">Ventas</h2>
    <a href="{{ route('admin.ventas.create') }}" class="inline-flex items-center gap-2 bg-orange-500 text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-orange-600 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nueva venta
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100">
    <div class="px-5 py-4 border-b border-slate-100">
        <form method="GET" class="flex gap-3 flex-wrap">
            <input type="text" name="cliente" placeholder="Nombre del cliente..." class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" value="{{ request('cliente') }}">
            <select name="estado" class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                <option value="">Todos los estados</option>
                <option value="Completada" {{ request('estado') == 'Completada' ? 'selected':'' }}>Completada</option>
                <option value="Anulada" {{ request('estado') == 'Anulada' ? 'selected':'' }}>Anulada</option>
            </select>
            <input type="date" name="fecha_desde" class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" value="{{ request('fecha_desde') }}">
            <input type="date" name="fecha_hasta" class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" value="{{ request('fecha_hasta') }}">
            <button class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-xl">Filtrar</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide border-b border-slate-100">
                    <th class="px-5 py-3.5">#</th>
                    <th class="px-5 py-3.5">Cliente</th>
                    <th class="px-5 py-3.5">Vendedor</th>
                    <th class="px-5 py-3.5">Fecha</th>
                    <th class="px-5 py-3.5">Subtotal</th>
                    <th class="px-5 py-3.5">Total</th>
                    <th class="px-5 py-3.5">Estado</th>
                    <th class="px-5 py-3.5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($ventas as $v)
                @php
                    $estadoColor = $v->estado === 'Anulada' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700';
                @endphp
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5 text-slate-500 font-mono text-xs">{{ $v->id_venta }}</td>
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $v->cliente->usuario->nombre ?? '—' }}</td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $v->usuario->nombre }}</td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $v->fecha->format('d/m/Y') }}</td>
                    <td class="px-5 py-3.5 text-slate-600">${{ number_format($v->subtotal, 2) }}</td>
                    <td class="px-5 py-3.5 font-semibold text-slate-800">${{ number_format($v->total, 2) }}</td>
                    <td class="px-5 py-3.5"><span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $estadoColor }}">{{ $v->estado }}</span></td>
                    <td class="px-5 py-3.5 text-right">
                        <a href="{{ route('admin.ventas.show',$v) }}" class="text-xs text-slate-600 border border-slate-200 px-3 py-1.5 rounded-lg hover:bg-slate-50 transition-colors">Ver</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-5 py-10 text-center text-slate-400">No hay ventas registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($ventas->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">{{ $ventas->links() }}</div>
    @endif
</div>
@endsection
