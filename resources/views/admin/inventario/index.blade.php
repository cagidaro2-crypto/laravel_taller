@extends('layouts.admin')
@section('title','Inventario')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-slate-800">Inventario</h2>
</div>

@if($bajoStock->isNotEmpty())
<div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-sm text-amber-800 flex items-start gap-3 mb-5">
    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
    <div><strong>Productos con bajo stock:</strong> {{ $bajoStock->pluck('producto.nombre')->join(', ') }}</div>
</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-slate-100">
    <div class="px-5 py-4 border-b border-slate-100">
        <form method="GET" class="flex gap-3 flex-wrap">
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar producto..."
                   class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 w-64">
            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                <input type="checkbox" name="bajo_stock" value="1" class="rounded accent-orange-500" {{ request('bajo_stock') ? 'checked' : '' }}>
                Solo bajo stock
            </label>
            <button class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-xl">Filtrar</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide border-b border-slate-100">
                    <th class="px-5 py-3.5">Producto</th>
                    <th class="px-5 py-3.5">Categoría</th>
                    <th class="px-5 py-3.5">Stock actual</th>
                    <th class="px-5 py-3.5">Stock mínimo</th>
                    <th class="px-5 py-3.5">Actualizado</th>
                    <th class="px-5 py-3.5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($inventario as $inv)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $inv->producto->nombre }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $inv->producto->categoria->nombre }}</td>
                    <td class="px-5 py-3.5">
                        <span class="font-semibold {{ $inv->tieneStockBajo() ? 'text-red-600':'text-slate-800' }}">{{ $inv->cantidad }}</span>
                        @if($inv->tieneStockBajo())
                            <span class="ml-2 text-xs font-medium px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Bajo stock</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $inv->stock_minimo }}</td>
                    <td class="px-5 py-3.5 text-slate-400 text-xs">{{ $inv->ultima_actualizacion?->format('d/m/Y H:i') ?? '—' }}</td>
                    <td class="px-5 py-3.5 text-right">
                        <button onclick="document.getElementById('modal-{{ $inv->id_inventario }}').classList.remove('hidden')"
                                class="text-xs text-orange-600 border border-orange-200 px-3 py-1.5 rounded-lg hover:bg-orange-50 transition-colors">
                            Actualizar
                        </button>
                    </td>
                </tr>

                {{-- Modal --}}
                <div id="modal-{{ $inv->id_inventario }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
                    <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4">
                        <h3 class="font-bold text-slate-800 mb-1">Actualizar stock</h3>
                        <p class="text-slate-500 text-sm mb-4">{{ $inv->producto->nombre }}</p>
                        <form method="POST" action="{{ route('admin.inventario.update',$inv) }}">
                            @csrf @method('PATCH')
                            <div class="mb-3">
                                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Nueva cantidad *</label>
                                <input type="number" name="cantidad" value="{{ $inv->cantidad }}" min="0" required
                                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                            </div>
                            <div class="mb-4">
                                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Motivo *</label>
                                <input type="text" name="motivo" placeholder="Ej: Recepción de compra" required
                                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="flex-1 bg-orange-500 text-white text-sm font-semibold py-2.5 rounded-xl hover:bg-orange-600 transition-colors">Guardar</button>
                                <button type="button" onclick="document.getElementById('modal-{{ $inv->id_inventario }}').classList.add('hidden')"
                                        class="flex-1 bg-slate-100 text-slate-700 text-sm font-medium py-2.5 rounded-xl hover:bg-slate-200 transition-colors">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-slate-400">No hay registros en inventario.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($inventario->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">{{ $inventario->links() }}</div>
    @endif
</div>
@endsection
