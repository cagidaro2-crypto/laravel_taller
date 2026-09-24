@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.vehiculos.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            Volver
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Detalles del Vehículo</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Contenido Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información General -->
            <div class="bg-white rounded-xl shadow p-8">
                <h2 class="text-2xl font-bold text-slate-900 mb-6">Información General</h2>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Placa</p>
                        <p class="text-2xl font-bold text-slate-900 font-mono">{{ $vehiculo->placa }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Estado</p>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-700">
                            {{ $vehiculo->estado?->nombre ?? $vehiculo->estado?->nombre_estado ?? 'Sin estado' }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Marca</p>
                        <p class="text-slate-900 font-semibold">{{ $vehiculo->marca }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Modelo</p>
                        <p class="text-slate-900 font-semibold">{{ $vehiculo->modelo }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Año</p>
                        <p class="text-slate-900 font-semibold">{{ $vehiculo->anio }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Color</p>
                        <p class="text-slate-900 font-semibold">{{ $vehiculo->color }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Tipo</p>
                        <p class="text-slate-900 font-semibold">{{ $vehiculo->tipo }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">VIN</p>
                        <p class="text-slate-900 font-semibold font-mono">{{ $vehiculo->vin ?? 'No registrado' }}</p>
                    </div>
                </div>

                @if($vehiculo->observaciones)
                    <div class="mt-6 pt-6 border-t border-slate-200">
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Observaciones</p>
                        <p class="text-slate-900">{{ $vehiculo->observaciones }}</p>
                    </div>
                @endif
            </div>

            <!-- Cliente -->
            @if($vehiculo->cliente)
                <div class="bg-white rounded-xl shadow p-8">
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Cliente Propietario</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                            {{ substr($vehiculo->cliente->usuario->nombre, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 text-lg">{{ $vehiculo->cliente->usuario->nombre }}</p>
                            <p class="text-slate-600">{{ $vehiculo->cliente->usuario->correo ?? 'N/A' }}</p>
                            @if($vehiculo->cliente->usuario->telefono)
                                <p class="text-slate-600 text-sm">{{ $vehiculo->cliente->usuario->telefono }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Órdenes de Trabajo -->
            <div class="bg-white rounded-xl shadow p-8">
                <h3 class="text-xl font-bold text-slate-900 mb-4">Órdenes de Trabajo</h3>

                @if($vehiculo->ordenesTrabajo->isEmpty())
                    <p class="text-slate-600">No hay órdenes registradas</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-100 border-b">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900">#Orden</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900">Fecha</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900">Estado</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900">Técnico</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach($vehiculo->ordenesTrabajo as $orden)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-4 py-3 font-semibold text-slate-900">#{{ $orden->id_orden }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $orden->fecha_ingreso->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                                {{ $orden->estado?->nombre ?? 'Pendiente' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-slate-600 text-sm">{{ $orden->usuario?->nombre ?? 'Sin asignar' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Ventas Asociadas -->
            <div class="bg-white rounded-xl shadow p-8">
                <h3 class="text-xl font-bold text-slate-900 mb-4">Productos Vendidos</h3>

                @if($vehiculo->ventas->isEmpty())
                    <p class="text-slate-600">No hay ventas registradas para este vehículo</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-100 border-b">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900">#Venta</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900">Fecha</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900">Productos</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900">Subtotal</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900">IVA</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900">Total</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900">Técnico</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach($vehiculo->ventas as $venta)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-4 py-3 font-semibold text-slate-900">#{{ $venta->id_venta }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $venta->fecha->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 text-slate-600 text-sm">{{ $venta->detalles->count() }} producto(s)</td>
                                        <td class="px-4 py-3 font-semibold text-slate-900">${{ number_format($venta->subtotal, 2, '.', ',') }}</td>
                                        <td class="px-4 py-3 font-semibold text-slate-900">${{ number_format($venta->impuesto, 2, '.', ',') }}</td>
                                        <td class="px-4 py-3 font-bold text-green-600">${{ number_format($venta->total, 2, '.', ',') }}</td>
                                        <td class="px-4 py-3 text-slate-600 text-sm">{{ $venta->usuario->nombre ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Resumen de Ventas -->
                    @php
                        $totalVentas = $vehiculo->ventas->sum('total');
                        $totalSubtotal = $vehiculo->ventas->sum('subtotal');
                        $totalIva = $vehiculo->ventas->sum('impuesto');
                    @endphp
                    <div class="mt-6 pt-6 border-t border-slate-200 bg-green-50 rounded-lg p-4">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Total Subtotal</p>
                                <p class="text-2xl font-bold text-slate-900">${{ number_format($totalSubtotal, 2, '.', ',') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Total IVA</p>
                                <p class="text-2xl font-bold text-slate-900">${{ number_format($totalIva, 2, '.', ',') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Total Ventas</p>
                                <p class="text-2xl font-bold text-green-600">${{ number_format($totalVentas, 2, '.', ',') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="space-y-6">
            <!-- Estadísticas -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Estadísticas</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                        <div>
                            <p class="text-sm text-slate-600 font-semibold">Órdenes de Trabajo</p>
                            <p class="text-2xl font-bold text-blue-700">{{ $vehiculo->ordenesTrabajo->count() }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                        <div>
                            <p class="text-sm text-slate-600 font-semibold">Ventas Registradas</p>
                            <p class="text-2xl font-bold text-green-700">{{ $vehiculo->ventas->count() }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg">
                        <div>
                            <p class="text-sm text-slate-600 font-semibold">Fotos</p>
                            <p class="text-2xl font-bold text-purple-700">{{ $vehiculo->fotos->count() }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg">
                        <div>
                            <p class="text-sm text-slate-600 font-semibold">Historial</p>
                            <p class="text-2xl font-bold text-yellow-700">{{ $vehiculo->historial->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Acciones</h3>
                <a href="{{ route('admin.vehiculos.index') }}" class="block text-center bg-slate-300 text-slate-900 px-4 py-2.5 rounded-lg hover:bg-slate-400 font-semibold transition">
                    Volver a Vehículos
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
