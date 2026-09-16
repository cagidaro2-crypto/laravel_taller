@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-900">Inventario</h1>
        <p class="text-slate-600 mt-2">Gestiona el stock de productos</p>
    </div>

    <!-- ALERTA STOCK MÍNIMO (RF-68) -->
    @if($bajoStock->isNotEmpty())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        ⚠️ {{ $bajoStock->count() }} producto(s) con stock bajo
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($bajoStock as $item)
                                <li>
                                    <strong>{{ $item->producto->nombre }}</strong> 
                                    (Stock: {{ $item->cantidad }}, Mínimo: {{ $item->stock_minimo }})
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Formulario de Filtros -->
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Buscar Producto</label>
                <input 
                    type="text" 
                    name="buscar"
                    value="{{ request('buscar') }}"
                    placeholder="Nombre del producto"
                    class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-400 outline-none"
                >
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Categoría</label>
                <select name="categoria" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-400 outline-none">
                    <option value="">Todas</option>
                    <option value="" {{ request('categoria') === '' ? 'selected' : '' }}>Todas</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Filtros</label>
                <label class="flex items-center">
                    <input type="checkbox" name="bajo_stock" value="1" {{ request('bajo_stock') ? 'checked' : '' }} class="accent-orange-500">
                    <span class="ml-2 text-sm text-slate-700">Solo stock bajo</span>
                </label>
            </div>

            <div class="md:col-span-3 flex gap-2">
                <button type="submit" class="bg-orange-500 text-white px-6 py-2.5 rounded-lg hover:bg-orange-600 font-semibold">
                    Filtrar
                </button>
                <a href="{{ route('admin.inventario.index') }}" class="bg-slate-300 text-slate-900 px-6 py-2.5 rounded-lg hover:bg-slate-400 font-semibold">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla de Inventario -->
    @if($inventario->isEmpty())
        <div class="bg-slate-50 border-2 border-dashed border-slate-300 rounded-xl p-12 text-center">
            <p class="text-slate-600">No hay productos en el inventario</p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-slate-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Producto</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Cantidad</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Stock Mínimo</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Estado</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($inventario as $item)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-sm text-slate-900">
                                <strong>{{ $item->producto->nombre }}</strong>
                                <p class="text-slate-600 text-xs">{{ $item->producto->categoria->nombre ?? 'N/A' }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $item->cantidad }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $item->stock_minimo }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($item->cantidad <= $item->stock_minimo)
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        ⚠️ Stock Bajo
                                    </span>
                                @elseif($item->cantidad <= ($item->stock_minimo * 1.5))
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                        ⚠️ Stock Medio
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        ✓ Normal
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.inventario.edit', $item) }}" class="text-orange-600 hover:text-orange-700 font-semibold">
                                    Actualizar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-6">
            {{ $inventario->links() }}
        </div>
    @endif
</div>
@endsection
