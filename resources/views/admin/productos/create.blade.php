@extends('layouts.admin')
@section('title', 'Agregar Producto')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Agregar Nuevo Producto</h1>
        <p class="text-sm text-slate-500 mt-1">Registra un nuevo repuesto o artículo en el catálogo de inventario</p>
    </div>
    <a href="{{ route('admin.productos.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 transition shadow-sm self-start sm:self-auto">
        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Volver
    </a>
</div>

<form method="POST" action="{{ route('admin.productos.store') }}" enctype="multipart/form-data" class="space-y-6 max-w-5xl mx-auto">
    @csrf

    {{-- Sección 1: Datos Básicos --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-7">
        <div class="flex items-center gap-2.5 mb-6 pb-3 border-b border-slate-100">
            <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-800">Información del Producto</h2>
                <p class="text-xs text-slate-500">Datos principales para identificación y catálogo</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
            <div class="md:col-span-8">
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                    Nombre del Producto <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="nombre" class="w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-2.5 text-sm text-slate-800 transition outline-none shadow-sm @error('nombre') border-rose-400 bg-rose-50/30 @enderror" placeholder="Ej. Pastillas de freno delanteras" value="{{ old('nombre') }}" required>
                @error('nombre')
                    <p class="text-rose-500 text-xs mt-1.5 font-medium flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="md:col-span-4">
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                    Código / SKU
                </label>
                <input type="text" name="codigo" class="w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-2.5 text-sm text-slate-800 font-mono transition outline-none shadow-sm" placeholder="Ej. PROD-0012" value="{{ old('codigo') }}">
            </div>

            <div class="md:col-span-6">
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                    Categoría <span class="text-rose-500">*</span>
                </label>
                <select name="id_categoria" required class="w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-2.5 text-sm text-slate-800 transition outline-none shadow-sm cursor-pointer @error('id_categoria') border-rose-400 bg-rose-50/30 @enderror">
                    <option value="">Seleccione una categoría...</option>
                    @foreach($categorias as $c)
                        <option value="{{ $c->id_categoria }}" {{ old('id_categoria') == $c->id_categoria ? 'selected' : '' }}>{{ $c->nombre }}</option>
                    @endforeach
                </select>
                @error('id_categoria')
                    <p class="text-rose-500 text-xs mt-1.5 font-medium flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="md:col-span-6">
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                    Proveedor
                </label>
                <select name="id_proveedor" class="w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-2.5 text-sm text-slate-800 transition outline-none shadow-sm cursor-pointer">
                    <option value="">Sin proveedor asignado</option>
                    @foreach($proveedores as $p)
                        <option value="{{ $p->id_proveedor }}" {{ old('id_proveedor') == $p->id_proveedor ? 'selected' : '' }}>{{ $p->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-6">
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                    Marca
                </label>
                <input type="text" name="marca" class="w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-2.5 text-sm text-slate-800 transition outline-none shadow-sm" placeholder="Ej. Bosch, ACDelco, Brembo" value="{{ old('marca') }}">
            </div>

            <div class="md:col-span-6">
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                    Unidad de Medida
                </label>
                <input type="text" name="unidad_medida" class="w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-2.5 text-sm text-slate-800 transition outline-none shadow-sm" placeholder="unidad, litro, kit..." value="{{ old('unidad_medida', 'unidad') }}">
            </div>
        </div>
    </div>

    {{-- Sección 2: Inventario y Precios --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-7">
        <div class="flex items-center gap-2.5 mb-6 pb-3 border-b border-slate-100">
            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-800">Inventario y Precios</h2>
                <p class="text-xs text-slate-500">Valores de compra, venta y niveles de existencias</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-slate-50/70 p-4 rounded-xl border border-slate-100">
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                    Stock Mínimo <span class="text-rose-500">*</span>
                </label>
                <input type="number" name="stock_minimo" class="w-full bg-white border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 transition outline-none shadow-sm" value="{{ old('stock_minimo', 5) }}" min="0" required>
                <p class="text-[11px] text-slate-400 mt-1.5">Alerta cuando baje de esta cantidad</p>
            </div>

            <div class="bg-slate-50/70 p-4 rounded-xl border border-slate-100">
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                    Stock Inicial
                </label>
                <input type="number" name="stock_inicial" class="w-full bg-white border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 transition outline-none shadow-sm" value="{{ old('stock_inicial', 0) }}" min="0">
                <p class="text-[11px] text-slate-400 mt-1.5">Existencias físicas de arranque</p>
            </div>

            <div class="bg-slate-50/70 p-4 rounded-xl border border-slate-100">
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                    Precio Compra
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-2.5 text-slate-400 text-sm font-semibold">$</span>
                    <input type="number" name="precio_compra" class="w-full bg-white border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl pl-8 pr-4 py-2.5 text-sm font-semibold text-slate-800 transition outline-none shadow-sm" placeholder="0.00" value="{{ old('precio_compra') }}" step="0.01" min="0">
                </div>
                <p class="text-[11px] text-slate-400 mt-1.5">Costo adquisición al proveedor</p>
            </div>

            <div class="bg-amber-50/60 p-4 rounded-xl border border-amber-200/80">
                <label class="block text-xs font-bold text-amber-800 uppercase tracking-wider mb-2">
                    Precio Venta <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-2.5 text-amber-500 text-sm font-bold">$</span>
                    <input type="number" name="precio_venta" class="w-full bg-white border border-amber-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 rounded-xl pl-8 pr-4 py-2.5 text-sm font-bold text-slate-900 transition outline-none shadow-sm @error('precio_venta') border-rose-400 @enderror" placeholder="0.00" value="{{ old('precio_venta') }}" step="0.01" min="0" required>
                </div>
                @error('precio_venta')
                    <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
                <p class="text-[11px] text-amber-700/80 mt-1.5">Precio final para clientes</p>
            </div>
        </div>
    </div>

    {{-- Sección 3: Descripción e Imagen --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-7">
        <div class="flex items-center gap-2.5 mb-6 pb-3 border-b border-slate-100">
            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-800">Detalles Adicionales e Imagen</h2>
                <p class="text-xs text-slate-500">Notas descriptivas y foto de referencia</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                    Descripción del Producto
                </label>
                <textarea name="descripcion" rows="4" class="w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-3 text-sm text-slate-800 transition outline-none shadow-sm resize-none" placeholder="Especificaciones técnicas, compatibilidad con modelos, etc...">{{ old('descripcion') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                    Foto del Producto
                </label>
                <div class="relative border-2 border-dashed border-slate-200 hover:border-orange-400 rounded-2xl p-4 text-center transition bg-slate-50/50 hover:bg-orange-50/20">
                    <svg class="w-10 h-10 mx-auto text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <p class="text-xs text-slate-600 font-medium mb-1">Subir archivo JPG, JPEG o PNG</p>
                    <p class="text-[11px] text-slate-400 mb-3">Imágenes de alta resolución recomendadas</p>
                    <input type="file" name="foto" id="fotoInput" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 file:cursor-pointer cursor-pointer @error('foto') border-rose-400 @enderror" accept="image/jpg,image/jpeg,image/png">
                </div>
                @error('foto')
                    <p class="text-rose-500 text-xs mt-1.5 font-medium flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Botones de Acción --}}
    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-bold px-7 py-3 rounded-xl shadow-lg shadow-orange-500/25 transition cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Guardar Producto
        </button>
        <a href="{{ route('admin.productos.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
            Cancelar
        </a>
    </div>
</form>
@endsection
