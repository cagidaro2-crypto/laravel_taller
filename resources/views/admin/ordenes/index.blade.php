@extends('layouts.admin')
@section('title','Órdenes de Trabajo')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-slate-800">Órdenes de trabajo</h2>
    <a href="{{ route('admin.ordenes.create') }}" class="inline-flex items-center gap-2 bg-orange-500 text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-orange-600 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nueva orden
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100">
    <div class="px-5 py-4 border-b border-slate-100">
        <form method="GET" class="flex gap-3 flex-wrap">
            <select name="estado" class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                <option value="">Todos los estados</option>
                @foreach($estados as $e)
                    <option value="{{ $e->id_estado }}" {{ request('estado') == $e->id_estado ? 'selected':'' }}>{{ $e->nombre }}</option>
                @endforeach
            </select>
            <button class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-xl">Filtrar</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide border-b border-slate-100">
                    <th class="px-5 py-3.5">#</th>
                    <th class="px-5 py-3.5">Vehículo</th>
                    <th class="px-5 py-3.5">Cliente</th>
                    <th class="px-5 py-3.5">Técnico</th>
                    <th class="px-5 py-3.5">Estado</th>
                    <th class="px-5 py-3.5">Ingreso</th>
                    <th class="px-5 py-3.5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($ordenes as $o)
                @php
                    $estadoColor = match($o->estado->nombre ?? '') {
                        'Finalizado','Entregado' => 'bg-green-100 text-green-700',
                        'En reparación'          => 'bg-blue-100 text-blue-700',
                        'En espera'              => 'bg-amber-100 text-amber-700',
                        default                  => 'bg-slate-100 text-slate-600',
                    };
                @endphp
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5 text-slate-500 font-mono text-xs">{{ $o->id_orden }}</td>
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $o->vehiculo->placa }} <span class="text-slate-400 font-normal">{{ $o->vehiculo->marca }}</span></td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $o->vehiculo->cliente->usuario->nombre }}</td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $o->usuario->nombre }}</td>
                    <td class="px-5 py-3.5"><span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $estadoColor }}">{{ $o->estado->nombre }}</span></td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $o->fecha_ingreso->format('d/m/Y') }}</td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.ordenes.show',$o) }}" class="text-xs text-slate-600 border border-slate-200 px-3 py-1.5 rounded-lg hover:bg-slate-50 transition-colors">Ver</a>
                            <a href="{{ route('admin.ordenes.edit',$o) }}" class="text-xs text-orange-600 border border-orange-200 px-3 py-1.5 rounded-lg hover:bg-orange-50 transition-colors">Editar</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-slate-400">No hay órdenes registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($ordenes->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">{{ $ordenes->links() }}</div>
    @endif
</div>
@endsection
