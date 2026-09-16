@extends('layouts.admin')
@section('title', 'Editar Orden #' . $ordene->id_orden)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.ordenes.show', $ordene) }}" class="text-slate-600 hover:text-slate-800 text-sm font-medium">← Volver</a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-3xl">
    <h2 class="text-xl font-bold text-slate-800 mb-6">Editar orden #{{ $ordene->id_orden }}</h2>

    <form method="POST" action="{{ route('admin.ordenes.update', $ordene) }}" class="space-y-5">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Estado</label>
                <select name="id_estado" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" required>
                    @foreach($estados as $e)
                        <option value="{{ $e->id_estado }}" {{ $ordene->id_estado == $e->id_estado ? 'selected' : '' }}>{{ $e->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Técnico asignado</label>
                <select name="id_usuario" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                    @foreach($tecnicos as $t)
                        <option value="{{ $t->id_usuario }}" {{ $ordene->id_usuario == $t->id_usuario ? 'selected' : '' }}>{{ $t->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Fecha de salida</label>
            <input type="date" name="fecha_salida" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" value="{{ old('fecha_salida', $ordene->fecha_salida?->format('Y-m-d')) }}">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Diagnóstico</label>
            <textarea name="diagnostico" rows="3" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">{{ old('diagnostico', $ordene->diagnostico) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Observaciones</label>
            <textarea name="observaciones" rows="2" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">{{ old('observaciones', $ordene->observaciones) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Subtotal</label>
                <input type="number" name="subtotal" step="0.01" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" value="{{ old('subtotal', $ordene->subtotal) }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Impuesto</label>
                <input type="number" name="impuesto" step="0.01" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" value="{{ old('impuesto', $ordene->impuesto) }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Total</label>
                <input type="number" name="total" step="0.01" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" value="{{ old('total', $ordene->total) }}">
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="bg-orange-500 text-white text-sm font-semibold px-6 py-2.5 rounded-xl hover:bg-orange-600 transition-colors">Guardar cambios</button>
            <a href="{{ route('admin.ordenes.show', $ordene) }}" class="bg-slate-200 text-slate-700 text-sm font-semibold px-6 py-2.5 rounded-xl hover:bg-slate-300 transition-colors">Cancelar</a>
        </div>
    </form>
</div>
@endsection
