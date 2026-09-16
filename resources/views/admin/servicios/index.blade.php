@extends('layouts.admin')
@section('title','Servicios')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-slate-800">Servicios</h2>
    <a href="{{ route('admin.servicios.create') }}" class="inline-flex items-center gap-2 bg-orange-500 text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-orange-600 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nuevo servicio
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100">
    <div class="px-5 py-4 border-b border-slate-100">
        <form method="GET" class="flex gap-3 flex-wrap">
            <input type="text" name="buscar" placeholder="Nombre del servicio..." class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" value="{{ request('buscar') }}">
            <button class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-xl">Filtrar</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide border-b border-slate-100">
                    <th class="px-5 py-3.5">#</th>
                    <th class="px-5 py-3.5">Nombre</th>
                    <th class="px-5 py-3.5">Descripción</th>
                    <th class="px-5 py-3.5">Precio Base</th>
                    <th class="px-5 py-3.5">Duración Est.</th>
                    <th class="px-5 py-3.5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($servicios as $s)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5 text-slate-500 font-mono text-xs">{{ $s->id_servicio }}</td>
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $s->nombre }}</td>
                    <td class="px-5 py-3.5 text-slate-600 max-w-xs truncate">{{ $s->descripcion ?? '—' }}</td>
                    <td class="px-5 py-3.5 font-semibold text-slate-800">${{ number_format($s->precio_base, 2) }}</td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $s->duracion_estimada ?? '—' }} hrs</td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.servicios.show',$s) }}" class="text-xs text-slate-600 border border-slate-200 px-3 py-1.5 rounded-lg hover:bg-slate-50 transition-colors">Ver</a>
                            <a href="{{ route('admin.servicios.edit',$s) }}" class="text-xs text-orange-600 border border-orange-200 px-3 py-1.5 rounded-lg hover:bg-orange-50 transition-colors">Editar</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-slate-400">No hay servicios registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($servicios->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">{{ $servicios->links() }}</div>
    @endif
</div>
@endsection
