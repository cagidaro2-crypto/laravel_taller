@extends('layouts.tecnico')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('tecnico.ventas.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            Volver
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Venta #{{ $venta->id_venta }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Contenido Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información General -->
            <div class="bg-white rounded-xl shadow p-8">
                <h2 class="text-2xl font-bold text-slate-900 mb-6">Información General</h2>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Fecha de Venta</p>
                        <p class="text-lg font-bold text-slate-900">{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Estado</p>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                            {{ $venta->estado }}
                        </span>
                    </div>
                </div>

                @if($venta->cliente)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Cliente</p>
                        <p class="text-lg font-semibold text-slate-900">{{ $venta->cliente->usuario->nombre }}</p>
                        <p class="text-slate-600">{{ $venta->cliente->usuario->correo ?? 'N/A' }}</p>
                    </div>
                @endif

                @if($venta->vehiculo)
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Vehículo Asociado</p>
                        <p class="text-lg font-semibold text-slate-900">{{ $venta->vehiculo->placa }}</p>
                        <p class="text-slate-600">{{ $venta->vehiculo->marca }} {{ $venta->vehiculo->modelo }} ({{ $venta->vehiculo->anio }})</p>
                    </div>
                @endif
            </div>

            <!-- Productos -->
            <div class="bg-white rounded-xl shadow p-8">
                <h3 class="text-xl font-bold text-slate-900 mb-6">Productos Vendidos</h3>

                <div class="space-y-3">
                    @foreach($venta->detalles as $detalle)
                        <div class="flex items-center justify-between border-b border-slate-200 pb-4 last:border-b-0">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $detalle->producto->nombre }}</p>
                                <p class="text-slate-600 text-sm">Cantidad: {{ $detalle->cantidad }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-slate-900">${{ number_format($detalle->precio_unitario, 2, '.', ',') }} c/u</p>
                                <p class="text-slate-600 text-sm">Subtotal: ${{ number_format($detalle->subtotal, 2, '.', ',') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Resumen de Costos -->
            <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl shadow p-8 border border-green-200">
                <h3 class="text-lg font-bold text-slate-900 mb-6">Resumen de Costos</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-slate-700">Subtotal:</p>
                        <p class="font-semibold text-slate-900">${{ number_format($venta->subtotal, 2, '.', ',') }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-slate-700">IVA (19%):</p>
                        <p class="font-semibold text-slate-900">${{ number_format($venta->impuesto, 2, '.', ',') }}</p>
                    </div>
                    <div class="border-t-2 border-green-300 pt-3 flex items-center justify-between">
                        <p class="text-lg font-bold text-slate-900">Total:</p>
                        <p class="text-2xl font-bold text-green-600">${{ number_format($venta->total, 2, '.', ',') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="space-y-6">
            <!-- Información del Técnico -->
            <div class="bg-white rounded-xl shadow p-6 sticky top-8">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Técnico</h3>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                        {{ substr($usuario->nombre ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-slate-900">{{ $usuario->nombre ?? 'Usuario' }}</p>
                        <p class="text-slate-600 text-sm">{{ $usuario->correo ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Acciones</h3>
                <a href="{{ route('tecnico.ventas.index') }}" class="block text-center bg-slate-300 text-slate-900 px-4 py-2.5 rounded-lg hover:bg-slate-400 font-semibold transition">
                    Volver a Ventas
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
