@extends('layouts.admin')
@section('title','Proveedores')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-slate-800">Proveedores</h2>
    <a href="{{ route('admin.proveedores.create') }}" class="inline-flex items-center gap-2 bg-orange-500 text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-orange-600 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nuevo proveedor
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100">
    <div class="px-5 py-4 border-b border-slate-100">
        <form method="GET" class="flex gap-3 flex-wrap">
            <input type="text" name="buscar" placeholder="Nombre o documento..." class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" value="{{ request('buscar') }}">
            <button class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-xl">Filtrar</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide border-b border-slate-100">
                    <th class="px-5 py-3.5">#</th>
                    <th class="px-5 py-3.5">Nombre</th>
                    <th class="px-5 py-3.5">Documento</th>
                    <th class="px-5 py-3.5">Teléfono</th>
                    <th class="px-5 py-3.5">Email</th>
                    <th class="px-5 py-3.5">Dirección</th>
                    <th class="px-5 py-3.5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($proveedores as $p)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5 text-slate-500 font-mono text-xs">{{ $p->id_proveedor }}</td>
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $p->nombre }}</td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $p->documento ?? '—' }}</td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $p->telefono ?? '—' }}</td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $p->correo ?? '—' }}</td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $p->direccion ?? '—' }}</td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.proveedores.show',$p) }}" class="text-xs text-slate-600 border border-slate-200 px-3 py-1.5 rounded-lg hover:bg-slate-50 transition-colors">Ver</a>
                            <a href="{{ route('admin.proveedores.edit',$p) }}" class="text-xs text-orange-600 border border-orange-200 px-3 py-1.5 rounded-lg hover:bg-orange-50 transition-colors">Editar</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-slate-400">No hay proveedores registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($proveedores->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">{{ $proveedores->links() }}</div>
    @endif
</div>
@endsection
