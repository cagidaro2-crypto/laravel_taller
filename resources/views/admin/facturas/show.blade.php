@extends('layouts.admin')
@section('title', 'Factura ' . $factura->numero_factura)

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-slate-800">Factura {{ $factura->numero_factura }}</h2>
    <div class="flex gap-2">
        <a href="{{ route('admin.facturas.pdf', $factura) }}" target="_blank"
           class="inline-flex items-center gap-2 bg-orange-500 text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-orange-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Descargar PDF
        </a>
        <a href="{{ route('admin.facturas.index') }}" class="text-sm text-slate-500 hover:text-slate-800 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Volver
        </a>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 lg:col-span-2">
        <h3 class="font-semibold text-slate-700 mb-4 text-sm uppercase tracking-wide">Información de la factura</h3>
        <dl class="grid grid-cols-2 gap-3 text-sm">
            <div><dt class="text-slate-400 text-xs">N° Factura</dt><dd class="font-mono font-semibold">{{ $factura->numero_factura }}</dd></div>
            <div><dt class="text-slate-400 text-xs">Fecha</dt><dd>{{ $factura->fecha->format('d/m/Y') }}</dd></div>
            <div><dt class="text-slate-400 text-xs">Cliente</dt><dd class="font-medium">{{ $factura->cliente->usuario->nombre }}</dd></div>
            <div><dt class="text-slate-400 text-xs">Estado</dt>
                <dd>
                    @php $badge = match($factura->estado){ 'Pagada'=>'bg-green-100 text-green-700','Anulada'=>'bg-red-100 text-red-700',default=>'bg-amber-100 text-amber-700' }; @endphp
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $badge }}">{{ $factura->estado }}</span>
                </dd>
            </div>
            <div><dt class="text-slate-400 text-xs">Subtotal</dt><dd>${{ number_format($factura->subtotal, 2) }}</dd></div>
            <div><dt class="text-slate-400 text-xs">Impuesto (19%)</dt><dd>${{ number_format($factura->impuesto, 2) }}</dd></div>
            <div class="col-span-2 border-t border-slate-100 pt-3 mt-1">
                <dt class="text-slate-400 text-xs">Total</dt>
                <dd class="text-2xl font-extrabold text-slate-800">${{ number_format($factura->total, 2) }}</dd>
            </div>
        </dl>
    </div>

    {{-- Pagos --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <h3 class="font-semibold text-slate-700 mb-3 text-sm uppercase tracking-wide">Pagos registrados</h3>
        @forelse($factura->pagos as $pago)
            <div class="flex justify-between items-center py-2 border-b border-slate-50 last:border-0 text-sm">
                <div>
                    <p class="font-medium text-slate-800">${{ number_format($pago->monto, 2) }}</p>
                    <p class="text-xs text-slate-400">{{ $pago->metodo_pago }} — {{ $pago->fecha_pago->format('d/m/Y') }}</p>
                </div>
            </div>
        @empty
            <p class="text-slate-400 text-sm">Sin pagos registrados.</p>
        @endforelse

        @if($factura->estado !== 'Pagada' && $factura->estado !== 'Anulada')
        <form method="POST" action="{{ route('admin.facturas.pago', $factura) }}" class="mt-4 space-y-2">
            @csrf
            <input type="number" name="monto" step="0.01" placeholder="Monto" required
                   class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
            <select name="metodo_pago" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                <option value="Efectivo">Efectivo</option>
                <option value="Transferencia">Transferencia</option>
                <option value="Tarjeta">Tarjeta</option>
            </select>
            <input type="date" name="fecha_pago" value="{{ date('Y-m-d') }}" required
                   class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
            <input type="text" name="referencia" placeholder="Referencia (opcional)"
                   class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
            <button type="submit" class="w-full bg-orange-500 text-white text-sm font-semibold py-2.5 rounded-xl hover:bg-orange-600 transition-colors">
                Registrar pago
            </button>
        </form>
        @endif
    </div>
</div>

{{-- Detalle de orden --}}
@if($factura->orden)
<div class="bg-white rounded-2xl shadow-sm border border-slate-100">
    <div class="px-5 py-4 border-b border-slate-100">
        <h3 class="font-semibold text-slate-700 text-sm">Detalle de servicios y productos</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide border-b border-slate-100">
                <th class="px-5 py-3">Descripción</th><th class="px-5 py-3">Tipo</th><th class="px-5 py-3">Cant.</th><th class="px-5 py-3">Precio</th><th class="px-5 py-3">Subtotal</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($factura->orden->servicios as $s)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3 font-medium">{{ $s->servicio->nombre }}</td>
                    <td class="px-5 py-3"><span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">Servicio</span></td>
                    <td class="px-5 py-3">{{ $s->cantidad }}</td>
                    <td class="px-5 py-3">${{ number_format($s->precio, 2) }}</td>
                    <td class="px-5 py-3 font-semibold">${{ number_format($s->subtotal, 2) }}</td>
                </tr>
                @endforeach
                @foreach($factura->orden->productos as $p)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3 font-medium">{{ $p->producto->nombre }}</td>
                    <td class="px-5 py-3"><span class="text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full">Producto</span></td>
                    <td class="px-5 py-3">{{ $p->cantidad }}</td>
                    <td class="px-5 py-3">${{ number_format($p->precio_unitario, 2) }}</td>
                    <td class="px-5 py-3 font-semibold">${{ number_format($p->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
