@extends('layouts.tecnico')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Mis Ventas Registradas</h1>
            <p class="text-slate-600 mt-2">Ventas de productos y servicios a clientes</p>
        </div>
        <a href="{{ route('tecnico.ventas.create') }}" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 font-semibold transition flex items-center gap-2">
            <i class="bi bi-plus-lg"></i>
            Nueva Venta
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-start gap-3">
            <i class="bi bi-check-circle mt-0.5"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-start gap-3">
            <i class="bi bi-exclamation-circle mt-0.5"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    @if($ventas->isEmpty())
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-12 text-center">
            <i class="bi bi-bag text-5xl text-blue-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-900 mb-2">No hay ventas registradas</h3>
            <p class="text-slate-600 mb-6">Comienza a registrar ventas ahora</p>
            <a href="{{ route('tecnico.ventas.create') }}" class="inline-block bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 font-semibold transition">
                Registrar Venta
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($ventas as $venta)
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition border-l-4 border-green-500">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                            <!-- Información Principal -->
                            <div class="md:col-span-2">
                                <div>
                                    <h3 class="font-bold text-slate-900 text-lg">Venta #{{ $venta->id_venta }}</h3>
                                    
                                    @if($venta->cliente)
                                        <p class="text-slate-600 text-sm mt-2">
                                            <strong>Cliente:</strong> {{ $venta->cliente->usuario->nombre ?? 'N/A' }}
                                        </p>
                                    @endif

                                    @if($venta->vehiculo)
                                        <p class="text-slate-600 text-sm mt-1">
                                            <strong>Vehículo:</strong> {{ $venta->vehiculo->placa }} - {{ $venta->vehiculo->marca }} {{ $venta->vehiculo->modelo }}
                                        </p>
                                    @endif

                                    <div class="mt-3">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            {{ $venta->estado }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Cantidad de Productos -->
                            <div>
                                <p class="text-xs text-slate-600 uppercase tracking-wide font-semibold mb-1">Productos</p>
                                <p class="font-bold text-slate-900 text-lg">{{ $venta->detalles->count() }}</p>
                                <p class="text-slate-600 text-xs">{{ $venta->detalles->sum('cantidad') }} unidades</p>
                            </div>

                            <!-- Fecha -->
                            <div>
                                <p class="text-xs text-slate-600 uppercase tracking-wide font-semibold mb-1">Fecha</p>
                                <p class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</p>
                            </div>

                            <!-- Total -->
                            <div>
                                <p class="text-xs text-slate-600 uppercase tracking-wide font-semibold mb-1">Total</p>
                                <p class="text-2xl font-bold text-green-600">${{ number_format($venta->total, 2, '.', ',') }}</p>
                            </div>

                            <!-- Acciones -->
                            <div class="flex flex-col gap-2 justify-center">
                                <a href="{{ route('tecnico.ventas.show', $venta) }}" class="text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold transition text-sm">
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-8">
            {{ $ventas->links() }}
        </div>
    @endif
</div>
@endsection
