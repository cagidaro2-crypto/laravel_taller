@extends('layouts.admin')
@section('title','Detalles del Servicio')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.servicios.index') }}" class="text-slate-600 hover:text-slate-800 text-sm font-medium">← Volver</a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-2xl">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-slate-800">{{ $servicio->nombre }}</h2>
        <a href="{{ route('admin.servicios.edit', $servicio) }}" class="text-orange-600 border border-orange-200 px-4 py-2 rounded-lg hover:bg-orange-50 transition-colors text-sm font-medium">Editar</a>
    </div>

    <div class="space-y-4">
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase mb-1">Nombre</p>
            <p class="text-sm font-medium text-slate-800">{{ $servicio->nombre }}</p>
        </div>

        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase mb-1">Descripción</p>
            <p class="text-sm text-slate-700">{{ $servicio->descripcion ?? '—' }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase mb-1">Precio Base</p>
                <p class="text-lg font-semibold text-slate-800">${{ number_format($servicio->precio_base, 2) }}</p>
            </div>

            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase mb-1">Duración Estimada</p>
                <p class="text-sm text-slate-700">{{ $servicio->duracion_estimada ?? '—' }} horas</p>
            </div>
        </div>
    </div>

    <div class="mt-6 pt-6 border-t border-slate-100 flex gap-3">
        <a href="{{ route('admin.servicios.edit', $servicio) }}" class="bg-orange-500 text-white text-sm font-semibold px-6 py-2.5 rounded-xl hover:bg-orange-600 transition-colors">Editar</a>
        <form method="POST" action="{{ route('admin.servicios.destroy', $servicio) }}" style="display:inline;" onsubmit="return confirm('¿Estás seguro?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 text-white text-sm font-semibold px-6 py-2.5 rounded-xl hover:bg-red-600 transition-colors">Eliminar</button>
        </form>
        <a href="{{ route('admin.servicios.index') }}" class="bg-slate-200 text-slate-700 text-sm font-semibold px-6 py-2.5 rounded-xl hover:bg-slate-300 transition-colors">Volver</a>
    </div>
</div>
@endsection
