@extends('layouts.admin')
@section('title','Detalles del Proveedor')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.proveedores.index') }}" class="text-slate-600 hover:text-slate-800 text-sm font-medium">← Volver</a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-2xl">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-slate-800">{{ $proveedor->nombre }}</h2>
        <a href="{{ route('admin.proveedores.edit', $proveedor) }}" class="text-orange-600 border border-orange-200 px-4 py-2 rounded-lg hover:bg-orange-50 transition-colors text-sm font-medium">Editar</a>
    </div>

    <div class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase mb-1">Nombre</p>
                <p class="text-sm font-medium text-slate-800">{{ $proveedor->nombre }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase mb-1">Documento</p>
                <p class="text-sm font-medium text-slate-800">{{ $proveedor->documento ?? '—' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase mb-1">Teléfono</p>
                <p class="text-sm text-slate-700">{{ $proveedor->telefono ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase mb-1">Email</p>
                <p class="text-sm text-slate-700">
                    @if($proveedor->correo)
                        <a href="mailto:{{ $proveedor->correo }}" class="text-orange-600 hover:underline">{{ $proveedor->correo }}</a>
                    @else
                        —
                    @endif
                </p>
            </div>
        </div>

        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase mb-1">Dirección</p>
            <p class="text-sm text-slate-700">{{ $proveedor->direccion ?? '—' }}</p>
        </div>
    </div>

    <div class="mt-6 pt-6 border-t border-slate-100 flex gap-3">
        <a href="{{ route('admin.proveedores.edit', $proveedor) }}" class="bg-orange-500 text-white text-sm font-semibold px-6 py-2.5 rounded-xl hover:bg-orange-600 transition-colors">Editar</a>
        <form method="POST" action="{{ route('admin.proveedores.destroy', $proveedor) }}" style="display:inline;" onsubmit="return confirm('¿Estás seguro?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 text-white text-sm font-semibold px-6 py-2.5 rounded-xl hover:bg-red-600 transition-colors">Eliminar</button>
        </form>
        <a href="{{ route('admin.proveedores.index') }}" class="bg-slate-200 text-slate-700 text-sm font-semibold px-6 py-2.5 rounded-xl hover:bg-slate-300 transition-colors">Volver</a>
    </div>
</div>
@endsection
