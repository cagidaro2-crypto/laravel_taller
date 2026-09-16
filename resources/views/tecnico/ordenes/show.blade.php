@extends('layouts.tecnico')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Orden #{{ $ordene->id_orden }}</h1>
            <p class="text-slate-600 mt-2">Detalles de la orden de trabajo</p>
        </div>
        <a href="{{ route('tecnico.ordenes.index') }}" class="bg-slate-500 text-white px-4 py-2 rounded-lg hover:bg-slate-600">Volver</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Información General -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-4">Información General</h2>
            
            <div class="space-y-3">
                <div>
                    <label class="text-sm text-slate-600">Vehículo</label>
                    <p class="text-slate-900 font-semibold">{{ $ordene->vehiculo->placa ?? 'N/A' }}</p>
                </div>
                
                <div>
                    <label class="text-sm text-slate-600">Cliente</label>
                    <p class="text-slate-900 font-semibold">{{ $ordene->vehiculo->cliente->usuario->nombre ?? 'N/A' }}</p>
                </div>
                
                <div>
                    <label class="text-sm text-slate-600">Estado</label>
                    <p class="text-slate-900 font-semibold">
                        <span class="px-3 py-1 rounded-full text-xs bg-orange-100 text-orange-800">
                            {{ $ordene->estado->nombre ?? 'Pendiente' }}
                        </span>
                    </p>
                </div>
                
                <div>
                    <label class="text-sm text-slate-600">Fecha Ingreso</label>
                    <p class="text-slate-900 font-semibold">{{ $ordene->fecha_ingreso->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Fotos del Vehículo -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-4">Fotos del Vehículo</h2>
            
            @if($ordene->vehiculo->fotos->isEmpty())
                <div class="bg-slate-50 rounded-lg p-4 text-center">
                    <svg class="w-12 h-12 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-slate-600 text-sm">No hay fotos del vehículo</p>
                </div>
            @else
                <div class="grid grid-cols-2 gap-2">
                    @foreach($ordene->vehiculo->fotos as $foto)
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $foto->ruta_foto) }}" 
                                 alt="Foto del vehículo" 
                                 class="w-full h-24 object-cover rounded-lg">
                            @if($foto->descripcion)
                                <p class="text-xs text-slate-600 mt-1">{{ $foto->descripcion }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Descripción del Problema -->
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h2 class="text-xl font-bold text-slate-900 mb-4">Descripción del Problema</h2>
        <p class="text-slate-700">{{ $ordene->descripcion_problema ?? 'Sin descripción' }}</p>
    </div>

    <!-- Servicios y Productos -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- Servicios -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-4">Servicios</h2>
            @if($ordene->servicios->isEmpty())
                <p class="text-slate-600">No hay servicios asignados</p>
            @else
                <div class="space-y-2">
                    @foreach($ordene->servicios as $servicio)
                        <div class="flex justify-between p-2 bg-slate-50 rounded">
                            <span>{{ $servicio->servicio->nombre ?? 'N/A' }}</span>
                            <span class="font-semibold">${{ number_format($servicio->precio_unitario, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Productos -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-4">Productos</h2>
            @if($ordene->productos->isEmpty())
                <p class="text-slate-600">No hay productos asignados</p>
            @else
                <div class="space-y-2">
                    @foreach($ordene->productos as $producto)
                        <div class="flex justify-between p-2 bg-slate-50 rounded">
                            <span>{{ $producto->producto->nombre ?? 'N/A' }}</span>
                            <span class="font-semibold">${{ number_format($producto->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Totales -->
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <div class="grid grid-cols-3 gap-4 text-center">
            <div>
                <p class="text-slate-600 text-sm">Subtotal</p>
                <p class="text-2xl font-bold text-slate-900">${{ number_format($ordene->subtotal, 2) }}</p>
            </div>
            <div>
                <p class="text-slate-600 text-sm">Impuesto</p>
                <p class="text-2xl font-bold text-slate-900">${{ number_format($ordene->impuesto, 2) }}</p>
            </div>
            <div>
                <p class="text-slate-600 text-sm">Total</p>
                <p class="text-2xl font-bold text-orange-600">${{ number_format($ordene->total, 2) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
