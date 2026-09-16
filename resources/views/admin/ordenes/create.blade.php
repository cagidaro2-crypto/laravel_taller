@extends('layouts.admin')
@section('title', 'Nueva Orden de Trabajo')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.ordenes.index') }}" class="text-slate-600 hover:text-slate-800 text-sm font-medium">← Volver</a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-3xl">
    <h2 class="text-xl font-bold text-slate-800 mb-6">Crear orden de trabajo</h2>

    <form method="POST" action="{{ route('admin.ordenes.store') }}" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Vehículo <span class="text-red-500">*</span></label>
                <select name="id_vehiculo" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 @error('id_vehiculo') border-red-500 @enderror" required>
                    <option value="">Seleccione vehículo...</option>
                    @foreach($vehiculos as $v)
                        <option value="{{ $v->id_vehiculo }}" {{ old('id_vehiculo') == $v->id_vehiculo ? 'selected' : '' }}>
                            {{ $v->placa }} — {{ $v->marca }} {{ $v->modelo }} ({{ $v->cliente->usuario->nombre }})
                        </option>
                    @endforeach
                </select>
                @error('id_vehiculo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Técnico asignado <span class="text-red-500">*</span></label>
                <select name="id_usuario" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 @error('id_usuario') border-red-500 @enderror" required>
                    <option value="">Seleccione técnico...</option>
                    @foreach($tecnicos as $t)
                        <option value="{{ $t->id_usuario }}" {{ old('id_usuario') == $t->id_usuario ? 'selected' : '' }}>{{ $t->nombre }}</option>
                    @endforeach
                </select>
                @error('id_usuario')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Estado <span class="text-red-500">*</span></label>
                <select name="id_estado" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" required>
                    <option value="">Seleccione estado...</option>
                    @foreach($estados as $e)
                        <option value="{{ $e->id_estado }}" {{ old('id_estado') == $e->id_estado ? 'selected' : '' }}>{{ $e->nombre }}</option>
                    @endforeach
                </select>
                @error('id_estado')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Fecha de ingreso <span class="text-red-500">*</span></label>
                <input type="date" name="fecha_ingreso" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" value="{{ old('fecha_ingreso', date('Y-m-d')) }}" required>
                @error('fecha_ingreso')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Descripción del problema</label>
            <textarea name="descripcion_problema" rows="3" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" placeholder="Describe el problema del vehículo...">{{ old('descripcion_problema') }}</textarea>
            @error('descripcion_problema')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Observaciones</label>
            <textarea name="observaciones" rows="2" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" placeholder="Observaciones adicionales...">{{ old('observaciones') }}</textarea>
            @error('observaciones')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="bg-orange-500 text-white text-sm font-semibold px-6 py-2.5 rounded-xl hover:bg-orange-600 transition-colors">Crear orden</button>
            <a href="{{ route('admin.ordenes.index') }}" class="bg-slate-200 text-slate-700 text-sm font-semibold px-6 py-2.5 rounded-xl hover:bg-slate-300 transition-colors">Cancelar</a>
        </div>
    </form>
</div>
@endsection
