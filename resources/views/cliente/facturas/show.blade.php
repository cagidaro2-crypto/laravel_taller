@extends('layouts.cliente')
@section('title', 'Factura ' . $factura->numero_factura)

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('cliente.facturas.index') }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 flex items-center gap-3">
                    Factura <span class="font-mono text-indigo-600">{{ $factura->numero_factura }}</span>
                </h1>
                <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Emitida el {{ $factura->fecha->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto">
            <a href="{{ route('cliente.facturas.pdf', $factura) }}" 
               class="inline-flex items-center justify-center gap-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition shadow-sm shadow-orange-200 w-full sm:w-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Descargar en PDF
            </a>
        </div>
    </div>

    <!-- Contenedor Principal Estilo Factura -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- Barra de Estado -->
        <div class="p-6 bg-slate-50 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="text-xs uppercase tracking-wider font-semibold text-slate-400">Estado de Pago:</span>
                @php
                    $badgeStyle = match($factura->estado) {
                        'Pagada'  => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'Anulada' => 'bg-rose-100 text-rose-800 border-rose-200',
                        default   => 'bg-amber-100 text-amber-800 border-amber-200',
                    };
                @endphp
                <span id="estado-badge" class="px-3 py-1 rounded-full text-xs font-bold border {{ $badgeStyle }}">
                    {{ $factura->estado }}
                </span>
            </div>

            @if($factura->orden)
                <div class="text-xs text-slate-600">
                    Asociada a: <span class="font-semibold text-slate-900">Orden de Trabajo #{{ $factura->orden->id_orden }}</span>
                </div>
            @elseif($factura->cotizacion)
                <div class="text-xs text-slate-600">
                    Asociada a: <span class="font-semibold text-slate-900">Cotización #{{ $factura->cotizacion->id_cotizacion }}</span>
                </div>
            @endif

            {{-- Botón Marcar Como Pagada --}}
            @if($factura->estado !== 'Pagada' && $factura->estado !== 'Anulada')
                <button onclick="marcarPagada()" id="btn-pago" class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition shadow-sm shadow-emerald-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Marcar como Pagada</span>
                </button>
            @endif
        </div>

        <div class="p-6 sm:p-8 space-y-8">
            <!-- Emisor y Receptor -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 border-b border-slate-100 pb-8">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Emisor</h3>
                    <p class="font-bold text-slate-900 text-base">TALLERPRO</p>
                    <p class="text-sm text-slate-600 mt-1">NIT: 900.123.456-7</p>
                    <p class="text-sm text-slate-600">PBX: (601) 765-4321</p>
                    <p class="text-sm text-slate-600">Calle Principal # 45-67, Bogotá D.C.</p>
                    <p class="text-sm text-slate-600">contacto@tallerpro.com</p>
                </div>

                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Cliente / Receptor</h3>
                    <p class="font-bold text-slate-900 text-base">{{ $factura->cliente->usuario->nombre ?? 'N/A' }}</p>
                    <p class="text-sm text-slate-600 mt-1">Doc / Identificación: {{ $factura->cliente->documento ?? 'No registrado' }}</p>
                    <p class="text-sm text-slate-600">Correo: {{ $factura->cliente->usuario->correo ?? 'N/A' }}</p>
                    <p class="text-sm text-slate-600">Teléfono: {{ $factura->cliente->usuario->telefono ?? 'N/A' }}</p>
                    @if($factura->cliente->direccion)
                        <p class="text-sm text-slate-600">Dirección: {{ $factura->cliente->direccion }}</p>
                    @endif
                </div>
            </div>

            <!-- Información del Vehículo -->
            @php
                $vehiculo = $factura->orden->vehiculo ?? ($factura->cotizacion->vehiculo ?? null);
            @endphp
            @if($vehiculo)
                <div class="bg-indigo-50/50 rounded-2xl p-5 border border-indigo-100 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-indigo-700">Vehículo Atendido</p>
                        <p class="font-bold text-slate-900 text-lg mt-0.5">{{ $vehiculo->marca }} {{ $vehiculo->modelo }} ({{ $vehiculo->anio ?? 'N/A' }})</p>
                        <p class="text-xs text-slate-500 mt-0.5">Color: {{ $vehiculo->color ?? 'N/A' }} {{ $vehiculo->tipo ? '• Tipo: ' . $vehiculo->tipo : '' }}</p>
                    </div>
                    <div class="bg-white px-4 py-2 rounded-xl border border-indigo-200 shadow-sm">
                        <span class="text-xs text-slate-400 block font-semibold">PLACA</span>
                        <span class="font-mono text-xl font-black text-indigo-600">{{ $vehiculo->placa }}</span>
                    </div>
                </div>
            @endif

            <!-- Detalle de Conceptos / Ítems -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 mb-4">Detalle de Conceptos y Servicios</h3>
                <div class="overflow-x-auto rounded-xl border border-slate-100">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3 text-left">Concepto</th>
                                <th class="px-4 py-3 text-center">Tipo</th>
                                <th class="px-4 py-3 text-center">Cant.</th>
                                <th class="px-4 py-3 text-right">Vr. Unitario</th>
                                <th class="px-4 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-800">
                            @php $hasItems = false; @endphp

                            {{-- Desde Orden de Trabajo --}}
                            @if($factura->orden)
                                @foreach($factura->orden->servicios as $s)
                                    @php $hasItems = true; @endphp
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-4 py-3">
                                            <p class="font-semibold text-slate-900">{{ $s->servicio->nombre ?? 'Servicio' }}</p>
                                            @if($s->servicio && $s->servicio->descripcion)
                                                <p class="text-xs text-slate-500 mt-0.5">{{ $s->servicio->descripcion }}</p>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Servicio</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">{{ $s->cantidad }}</td>
                                        <td class="px-4 py-3 text-right">${{ number_format($s->precio, 2) }}</td>
                                        <td class="px-4 py-3 text-right font-semibold">${{ number_format($s->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach

                                @foreach($factura->orden->productos as $p)
                                    @php $hasItems = true; @endphp
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-4 py-3">
                                            <p class="font-semibold text-slate-900">{{ $p->producto->nombre ?? 'Repuesto' }}</p>
                                            @if($p->producto && $p->producto->marca)
                                                <p class="text-xs text-slate-500 mt-0.5">Marca: {{ $p->producto->marca }}</p>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Repuesto</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">{{ $p->cantidad }}</td>
                                        <td class="px-4 py-3 text-right">${{ number_format($p->precio_unitario, 2) }}</td>
                                        <td class="px-4 py-3 text-right font-semibold">${{ number_format($p->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach

                                @foreach($factura->orden->consumoMateriales as $m)
                                    @php $hasItems = true; @endphp
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-4 py-3 font-semibold text-slate-900">{{ $m->producto->nombre ?? 'Material Consumido' }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">Material</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">{{ $m->cantidad }}</td>
                                        <td class="px-4 py-3 text-right">${{ number_format($m->precio_unitario, 2) }}</td>
                                        <td class="px-4 py-3 text-right font-semibold">${{ number_format($m->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach

                            {{-- Desde Cotización --}}
                            @elseif($factura->cotizacion)
                                @foreach($factura->cotizacion->servicios as $s)
                                    @php $hasItems = true; @endphp
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-4 py-3 font-semibold text-slate-900">{{ $s->servicio->nombre ?? 'Servicio' }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Servicio</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">{{ $s->cantidad }}</td>
                                        <td class="px-4 py-3 text-right">${{ number_format($s->precio, 2) }}</td>
                                        <td class="px-4 py-3 text-right font-semibold">${{ number_format($s->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach

                                @foreach($factura->cotizacion->productos as $p)
                                    @php $hasItems = true; @endphp
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-4 py-3 font-semibold text-slate-900">{{ $p->producto->nombre ?? 'Producto' }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Repuesto</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">{{ $p->cantidad }}</td>
                                        <td class="px-4 py-3 text-right">${{ number_format($p->precio_unitario, 2) }}</td>
                                        <td class="px-4 py-3 text-right font-semibold">${{ number_format($p->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            @if(!$hasItems)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-slate-900">Servicios y Reparaciones Realizadas</td>
                                    <td class="px-4 py-3 text-center">General</td>
                                    <td class="px-4 py-3 text-center">1</td>
                                    <td class="px-4 py-3 text-right">${{ number_format($factura->subtotal, 2) }}</td>
                                    <td class="px-4 py-3 text-right font-semibold">${{ number_format($factura->subtotal, 2) }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Resumen Financiero -->
            <div class="flex justify-end pt-4">
                <div class="w-full sm:w-80 space-y-3 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <div class="flex justify-between text-sm text-slate-600">
                        <span>Subtotal:</span>
                        <span class="font-semibold text-slate-900">${{ number_format($factura->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-slate-600">
                        <span>Impuesto (19% IVA):</span>
                        <span class="font-semibold text-slate-900">${{ number_format($factura->impuesto, 2) }}</span>
                    </div>
                    <div class="border-t border-slate-200 pt-3 flex justify-between items-center">
                        <span class="text-base font-bold text-slate-900">Total Factura:</span>
                        <span class="text-2xl font-black text-indigo-600">${{ number_format($factura->total, 2) }}</span>
                    </div>

                    @php
                        $totalPagado = $factura->total_pagado;
                        $saldo = $factura->saldo_pendiente;
                    @endphp

                    @if($totalPagado > 0 || $factura->estado === 'Pagada')
                        <div class="flex justify-between text-xs text-emerald-700 font-semibold pt-1">
                            <span>Total Pagado:</span>
                            <span id="total-pagado">-${{ number_format($totalPagado > 0 ? $totalPagado : $factura->total, 2) }}</span>
                        </div>
                        <div id="saldo-section" class="flex justify-between text-sm font-bold pt-2 border-t border-dashed border-slate-200 {{ $saldo > 0 ? 'text-amber-700' : 'text-emerald-700' }}">
                            <span>Saldo Pendiente:</span>
                            <span id="saldo-pendiente">${{ number_format($saldo, 2) }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Registro de Pagos Realizados -->
            @if($factura->pagos->isNotEmpty())
                <div class="border-t border-slate-100 pt-8">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 mb-3">Historial de Pagos</h3>
                    <div class="overflow-x-auto rounded-xl border border-slate-100">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider border-b border-slate-100">
                                <tr>
                                    <th class="px-4 py-2.5 text-left">Fecha</th>
                                    <th class="px-4 py-2.5 text-left">Método</th>
                                    <th class="px-4 py-2.5 text-left">Referencia</th>
                                    <th class="px-4 py-2.5 text-right">Monto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($factura->pagos as $pago)
                                    <tr>
                                        <td class="px-4 py-2.5">{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2.5 font-medium">{{ $pago->metodo_pago }}</td>
                                        <td class="px-4 py-2.5 text-slate-500 text-xs">{{ $pago->referencia ?? 'N/A' }}</td>
                                        <td class="px-4 py-2.5 text-right font-bold text-emerald-600">${{ number_format($pago->monto, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function marcarPagada() {
        const btn = document.getElementById('btn-pago');
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        if (!confirm('¿Confirmas que esta factura ha sido pagada?')) {
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Procesando...';

        fetch('{{ route("cliente.facturas.marcar-pagada", $factura) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar badge de estado
                const badge = document.getElementById('estado-badge');
                badge.textContent = 'Pagada';
                badge.className = 'px-3 py-1 rounded-full text-xs font-bold border bg-emerald-100 text-emerald-800 border-emerald-200';

                // Actualizar total pagado
                document.getElementById('total-pagado').textContent = `-${{ $factura->total }}`;

                // Actualizar saldo pendiente
                document.getElementById('saldo-pendiente').textContent = '$0.00';
                document.getElementById('saldo-section').className = 'flex justify-between text-sm font-bold pt-2 border-t border-dashed border-slate-200 text-emerald-700';

                // Ocultar botón
                btn.remove();

                // Mostrar mensaje de éxito
                showNotification('✓ Factura marcada como pagada', 'success');

                // Recargar la página después de 2 segundos
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                showNotification(data.message || 'Error al procesar el pago', 'error');
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>Marcar como Pagada</span>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error de conexión', 'error');
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>Marcar como Pagada</span>';
        });
    }

    function showNotification(message, type) {
        const notification = document.createElement('div');
        const bgColor = type === 'success' ? 'bg-emerald-100 border-emerald-200 text-emerald-800' : 'bg-rose-100 border-rose-200 text-rose-800';
        
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg border ${bgColor} shadow-lg z-50 animate-fade-in`;
        notification.innerHTML = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
</script>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.3s ease-in-out;
    }
</style>
@endsection
