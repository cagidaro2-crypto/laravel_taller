@extends('layouts.admin')
@section('title','Facturas')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-slate-800">Facturas</h2>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100">
    <div class="px-5 py-4 border-b border-slate-100">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="cliente" value="{{ request('cliente') }}" placeholder="Buscar cliente..."
                   class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 w-52">
            <select name="estado" class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                <option value="">Todos los estados</option>
                @foreach(['Pendiente','Pagada','Anulada'] as $e)
                    <option value="{{ $e }}" {{ request('estado') == $e ? 'selected':'' }}>{{ $e }}</option>
                @endforeach
            </select>
            <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                   class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
            <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                   class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
            <button class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-xl">Filtrar</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide border-b border-slate-100">
                    <th class="px-5 py-3.5">N° Factura</th>
                    <th class="px-5 py-3.5">Cliente</th>
                    <th class="px-5 py-3.5">Fecha</th>
                    <th class="px-5 py-3.5">Total</th>
                    <th class="px-5 py-3.5">Estado</th>
                    <th class="px-5 py-3.5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($facturas as $f)
                @php
                    $badge = match($f->estado) {
                        'Pagada'  => 'bg-green-100 text-green-700',
                        'Anulada' => 'bg-red-100 text-red-700',
                        default   => 'bg-amber-100 text-amber-700',
                    };
                @endphp
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5 font-mono text-xs text-slate-600">{{ $f->numero_factura }}</td>
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $f->cliente->usuario->nombre }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $f->fecha->format('d/m/Y') }}</td>
                    <td class="px-5 py-3.5 font-semibold text-slate-800">${{ number_format($f->total, 2) }}</td>
                    <td class="px-5 py-3.5"><span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $badge }}">{{ $f->estado }}</span></td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.facturas.show', $f) }}" class="text-xs text-slate-600 border border-slate-200 px-3 py-1.5 rounded-lg hover:bg-slate-50 transition-colors">Ver</a>
                            <a href="{{ route('admin.facturas.pdf', $f) }}" target="_blank" class="text-xs text-orange-600 border border-orange-200 px-3 py-1.5 rounded-lg hover:bg-orange-50 transition-colors">PDF</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-slate-400">No hay facturas registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($facturas->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">{{ $facturas->links() }}</div>
    @endif
</div>
@endsection
