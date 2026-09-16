@extends('layouts.admin')
@section('title','Venta #' . $venta->id_venta)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.ventas.index') }}" class="text-slate-600 hover:text-slate-800 text-sm font-medium">← Volver</a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-slate-800">Venta #{{ $venta->id_venta }}</h2>
        @if($venta->estado === 'Completada')
            <form method="POST" action="{{ route('admin.ventas.anular', $venta) }}" style="display:inline;">
                @csrf
                <button type="submit" class="bg-red-500 text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-red-600 transition-colors" onclick="return confirm('¿Anular esta venta?')">Anular venta</button>
            </form>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase mb-2">Cliente</p>
            <p class="text-sm font-medium text-slate-800">{{ $venta->cliente->usuario->nombre ?? '—' }}</p>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase mb-2">Vendedor</p>
            <p class="text-sm font-medium text-slate-800">{{ $venta->usuario->nombre }}</p>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase mb-2">Fecha</p>
            <p class="text-sm text-slate-700">{{ $venta->fecha->format('d/m/Y') }}</p>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase mb-2">Estado</p>
            <span class="text-xs font-medium px-3 py-1 rounded-full {{ $venta->estado === 'Anulada' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">{{ $venta->estado }}</span>
        </div>
    </div>

    {{-- Tabla de productos --}}
    <div class="mb-6">
        <h3 class="text-sm font-semibold text-slate-700 mb-3">Productos</h3>
        <div class="overflow-x-auto border border-slate-200 rounded-xl">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200">
                        <th class="px-4 py-3 text-left font-semibold text-slate-700">Producto</th>
                        <th class="px-4 py-3 text-center font-semibold text-slate-700">Cantidad</th>
                        <th class="px-4 py-3 text-right font-semibold text-slate-700">P. Unitario</th>
                        <th class="px-4 py-3 text-right font-semibold text-slate-700">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($venta->detalles as $detalle)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $detalle->producto->nombre }}</td>
                        <td class="px-4 py-3 text-center text-slate-600">{{ $detalle->cantidad }}</td>
                        <td class="px-4 py-3 text-right text-slate-600">${{ number_format($detalle->precio_unitario, 2) }}</td>
                        <td class="px-4 py-3 text-right font-semibold">${{ number_format($detalle->subtotal, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-slate-400">Sin productos.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Totales --}}
    <div class="flex justify-end">
        <div class="w-full md:w-80 space-y-3">
            <div class="flex justify-between py-2 border-b border-slate-200">
                <span class="text-slate-700 font-medium">Subtotal:</span>
                <span class="font-semibold">${{ number_format($venta->subtotal, 2) }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-slate-200">
                <span class="text-slate-700 font-medium">Impuesto (19%):</span>
                <span class="font-semibold">${{ number_format($venta->impuesto, 2) }}</span>
            </div>
            <div class="flex justify-between py-3 bg-orange-50 px-4 rounded-lg">
                <span class="text-slate-800 font-bold text-lg">Total:</span>
                <span class="font-bold text-lg text-orange-600">${{ number_format($venta->total, 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
