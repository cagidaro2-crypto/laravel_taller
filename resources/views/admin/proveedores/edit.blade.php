@extends('layouts.admin')
@section('title','Editar Proveedor')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.proveedores.index') }}" class="text-slate-600 hover:text-slate-800 text-sm font-medium">← Volver</a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-2xl">
    <h2 class="text-xl font-bold text-slate-800 mb-6">Editar Proveedor</h2>

    <form action="{{ route('admin.proveedores.update', $proveedor) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Nombre *</label>
            <input type="text" name="nombre" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 @error('nombre') border-red-500 @enderror" value="{{ old('nombre', $proveedor->nombre) }}" required>
            @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Documento</label>
            <input type="text" name="documento" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 @error('documento') border-red-500 @enderror" value="{{ old('documento', $proveedor->documento) }}">
            @error('documento') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Teléfono</label>
                <input type="text" name="telefono" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 @error('telefono') border-red-500 @enderror" value="{{ old('telefono', $proveedor->telefono) }}">
                @error('telefono') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                <input type="email" name="correo" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 @error('correo') border-red-500 @enderror" value="{{ old('correo', $proveedor->correo) }}">
                @error('correo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Dirección</label>
            <input type="text" name="direccion" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" value="{{ old('direccion', $proveedor->direccion) }}">
            @error('direccion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="bg-orange-500 text-white text-sm font-semibold px-6 py-2.5 rounded-xl hover:bg-orange-600 transition-colors">Guardar cambios</button>
            <a href="{{ route('admin.proveedores.index') }}" class="bg-slate-200 text-slate-700 text-sm font-semibold px-6 py-2.5 rounded-xl hover:bg-slate-300 transition-colors">Cancelar</a>
        </div>
    </form>
</div>
@endsection
