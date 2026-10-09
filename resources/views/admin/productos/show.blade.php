@extends('layouts.admin')
@section('title', $producto->nombre)

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $producto->nombre }}</h1>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $producto->activo ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                {{ $producto->activo ? 'Activo' : 'Inactivo' }}
            </span>
        </div>
        <p class="text-sm text-slate-500 mt-1">Detalle de producto y estado de existencias en tiempo real</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.productos.edit', $producto) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            Editar
        </a>
        @if($producto->activo)
        <form method="POST" action="{{ route('admin.productos.desactivar', $producto) }}">
            @csrf @method('PATCH')
            <button class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 text-sm font-semibold transition shadow-sm" onclick="return confirm('¿Desactivar este producto?')">
                Desactivar
            </button>
        </form>
        @endif
        <a href="{{ route('admin.productos.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 transition shadow-sm">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Volver
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 max-w-6xl mx-auto">
    {{-- Columna Izquierda: Imagen / Galería --}}
    <div class="lg:col-span-4 space-y-4">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 overflow-hidden">
            @if($producto->fotos->isNotEmpty())
                <div class="space-y-3">
                    <div class="rounded-xl overflow-hidden bg-slate-100 aspect-square flex items-center justify-center">
                        <img src="{{ asset('storage/' . $producto->fotos->first()->ruta_foto) }}" class="w-full h-full object-cover" alt="{{ $producto->nombre }}">
                    </div>
                    @if($producto->fotos->count() > 1)
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($producto->fotos->skip(1) as $foto)
                                <div class="rounded-lg overflow-hidden bg-slate-100 aspect-square">
                                    <img src="{{ asset('storage/' . $foto->ruta_foto) }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <div class="rounded-xl bg-slate-50 border border-slate-100 aspect-square flex flex-col items-center justify-center text-slate-400">
                    <svg class="w-16 h-16 mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span class="text-xs font-medium">Sin fotografía asignada</span>
                </div>
            @endif
        </div>

        {{-- Mini Card de Stock --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Resumen de Existencias</h3>
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <span class="text-sm text-slate-600 font-medium">Stock Actual</span>
                <div class="flex items-center gap-2">
                    <span class="text-xl font-black text-slate-800">{{ $producto->inventario?->cantidad ?? 0 }}</span>
                    @if($producto->inventario?->tieneStockBajo())
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">Bajo Stock</span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">Disponible</span>
                    @endif
                </div>
            </div>
            <div class="flex items-center justify-between pt-3">
                <span class="text-sm text-slate-600 font-medium">Stock Mínimo Requerido</span>
                <span class="text-sm font-bold text-slate-700">{{ $producto->stock_minimo }}</span>
            </div>
        </div>
    </div>

    {{-- Columna Derecha: Ficha Técnica --}}
    <div class="lg:col-span-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-7">
            <h2 class="text-base font-bold text-slate-800 mb-6 pb-3 border-b border-slate-100">Ficha Técnica del Repuesto</h2>

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5 text-sm">
                <div class="bg-slate-50/70 p-3.5 rounded-xl border border-slate-100">
                    <dt class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Código / SKU</dt>
                    <dd class="text-slate-800 font-mono font-semibold">{{ $producto->codigo ?? '—' }}</dd>
                </div>

                <div class="bg-slate-50/70 p-3.5 rounded-xl border border-slate-100">
                    <dt class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Categoría</dt>
                    <dd class="text-slate-800 font-semibold">{{ $producto->categoria->nombre }}</dd>
                </div>

                <div class="bg-slate-50/70 p-3.5 rounded-xl border border-slate-100">
                    <dt class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Marca</dt>
                    <dd class="text-slate-800 font-semibold">{{ $producto->marca ?? '—' }}</dd>
                </div>

                <div class="bg-slate-50/70 p-3.5 rounded-xl border border-slate-100">
                    <dt class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Proveedor Asignado</dt>
                    <dd class="text-slate-800 font-semibold">{{ $producto->proveedor?->nombre ?? '—' }}</dd>
                </div>

                <div class="bg-emerald-50/50 p-3.5 rounded-xl border border-emerald-100">
                    <dt class="text-xs font-bold text-emerald-700 uppercase tracking-wider mb-1">Precio de Venta</dt>
                    <dd class="text-2xl font-black text-emerald-800">${{ number_format($producto->precio_venta, 2) }}</dd>
                </div>

                <div class="bg-slate-50/70 p-3.5 rounded-xl border border-slate-100">
                    <dt class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Precio de Compra</dt>
                    <dd class="text-xl font-bold text-slate-700">${{ $producto->precio_compra ? number_format($producto->precio_compra, 2) : '—' }}</dd>
                </div>

                <div class="sm:col-span-2 bg-slate-50/70 p-4 rounded-xl border border-slate-100 mt-2">
                    <dt class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Descripción y Compatibilidad</dt>
                    <dd class="text-slate-700 leading-relaxed">{{ $producto->descripcion ?? 'Sin descripción registrada para este producto.' }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
