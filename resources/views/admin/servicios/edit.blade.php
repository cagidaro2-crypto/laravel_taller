@extends('layouts.admin')
@section('title','Editar Servicio')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.servicios.index') }}" class="text-slate-600 hover:text-slate-800 text-sm font-medium">← Volver</a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-2xl">
    <h2 class="text-xl font-bold text-slate-800 mb-6">Editar Servicio</h2>

    <form action="{{ route('admin.servicios.update', $servicio) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Nombre <span class="text-red-500">*</span></label>
            <input type="text" name="nombre" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 @error('nombre') border-red-500 @enderror" value="{{ old('nombre', $servicio->nombre) }}" required>
            @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Descripción</label>
            <textarea name="descripcion" rows="3" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">{{ old('descripcion', $servicio->descripcion) }}</textarea>
            @error('descripcion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Precio Base <span class="text-red-500">*</span></label>
                <input type="number" name="precio_base" step="0.01" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 @error('precio_base') border-red-500 @enderror" value="{{ old('precio_base', $servicio->precio_base) }}" required>
                @error('precio_base') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Duración Estimada (horas)</label>
                <input type="number" name="duracion_estimada" step="0.5" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" value="{{ old('duracion_estimada', $servicio->duracion_estimada) }}">
                @error('duracion_estimada') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="bg-orange-500 text-white text-sm font-semibold px-6 py-2.5 rounded-xl hover:bg-orange-600 transition-colors">Guardar cambios</button>
            <a href="{{ route('admin.servicios.index') }}" class="bg-slate-200 text-slate-700 text-sm font-semibold px-6 py-2.5 rounded-xl hover:bg-slate-300 transition-colors">Cancelar</a>
        </div>
    </form>
</div>
@endsection
