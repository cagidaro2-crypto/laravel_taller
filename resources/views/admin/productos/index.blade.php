@extends('layouts.admin')
@section('title','Productos')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-slate-800">Catálogo de productos</h2>
    <a href="{{ route('admin.productos.create') }}" class="inline-flex items-center gap-2 bg-orange-500 text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-orange-600 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Agregar producto
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100">
    <div class="px-5 py-4 border-b border-slate-100">
        <form method="GET" class="flex gap-3 flex-wrap items-end">
            <div class="flex-1 min-w-xs">
                <label class="block text-xs font-semibold text-slate-600 mb-2">Búsqueda</label>
                <input type="text" name="buscar" placeholder="Nombre o código..." class="w-full border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" value="{{ request('buscar') }}">
            </div>
            <div class="flex-1 min-w-xs">
                <label class="block text-xs font-semibold text-slate-600 mb-2">Categoría</label>
                <select name="categoria" class="w-full border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                    <option value="">✓ Todas las categorías ({{ $categorias->count() }})</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id_categoria }}" {{ request('categoria') == $cat->id_categoria ? 'selected':'' }}>{{ $cat->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-xs">
                <label class="block text-xs font-semibold text-slate-600 mb-2">Estado</label>
                <select name="activo" class="w-full border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                    <option value="">Todos</option>
                    <option value="1" {{ request('activo') === '1' ? 'selected':'' }}>Activos</option>
                    <option value="0" {{ request('activo') === '0' ? 'selected':'' }}>Inactivos</option>
                </select>
            </div>
            <div>
                <button class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition">Filtrar</button>
                <a href="{{ route('admin.productos.index') }}" class="inline-block ml-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-4 py-2.5 rounded-xl transition">Limpiar</a>
            </div>
        </form>
    </div>

    <div class="p-5">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($productos as $p)
            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow">
                <!-- Imagen -->
                <div class="relative bg-slate-100 h-48 flex items-center justify-center overflow-hidden">
                    @if($p->fotos->isNotEmpty())
                        <img src="{{ asset('storage/' . $p->fotos->first()->ruta_foto) }}" class="w-full h-full object-cover hover:scale-105 transition-transform" alt="{{ $p->nombre }}">
                    @else
                        <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10l8-4"/></svg>
                    @endif
                </div>

                <!-- Contenido -->
                <div class="p-4">
                    <h3 class="font-semibold text-slate-800 text-sm mb-1 line-clamp-2">{{ $p->nombre }}</h3>
                    <p class="text-xs text-slate-500 mb-3">{{ $p->categoria->nombre }} <span class="text-slate-400">— {{ $p->marca }}</span></p>

                    <!-- Precio -->
                    <div class="flex items-baseline justify-between mb-3">
                        <span class="text-lg font-bold text-slate-800">${{ number_format($p->precio_venta, 2) }}</span>
                        <span class="text-xs text-slate-500">por unidad</span>
                    </div>

                    <!-- Estados -->
                    <div class="flex gap-2 mb-3 flex-wrap">
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $p->activo ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ $p->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                        @if($p->inventario && $p->inventario->tieneStockBajo())
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-amber-100 text-amber-700">Bajo stock</span>
                        @endif
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-2">
                        <a href="{{ route('admin.productos.show', $p) }}" class="flex-1 text-xs text-slate-600 border border-slate-200 px-3 py-2 rounded-lg hover:bg-slate-50 transition-colors text-center font-medium">Ver</a>
                        <a href="{{ route('admin.productos.edit', $p) }}" class="flex-1 text-xs text-orange-600 border border-orange-200 px-3 py-2 rounded-lg hover:bg-orange-50 transition-colors text-center font-medium">Editar</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10l8-4"/></svg>
                <p class="text-slate-400 text-sm">No hay productos registrados.</p>
            </div>
            @endforelse
        </div>

        @if($productos->hasPages())
        <div class="mt-6 pt-4 border-t border-slate-100">{{ $productos->links() }}</div>
        @endif
    </div>
</div>
@endsection
