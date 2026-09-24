@extends('layouts.cliente')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('cliente.cotizaciones.index') }}" class="text-orange-500 hover:text-orange-600 font-semibold flex items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            Volver
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Detalles de la Cotización</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Contenido Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Encabezado -->
            <div class="bg-white rounded-xl shadow p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">Cotización #{{ $cotizacione->id_cotizacion }}</h2>
                        <p class="text-slate-600 mt-1">Creada el {{ $cotizacione->fecha->format('d/m/Y') }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        {{ $cotizacione->estado === 'Aprobada' ? 'bg-green-100 text-green-700' : 
                           ($cotizacione->estado === 'Rechazada' ? 'bg-red-100 text-red-700' : 
                            'bg-orange-100 text-orange-700') }}">
                        {{ $cotizacione->estado }}
                    </span>
                </div>

                @if($cotizacione->descripcion)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-2">Descripción</p>
                        <p class="text-slate-900">{{ $cotizacione->descripcion }}</p>
                    </div>
                @endif

                @if($cotizacione->fecha_vencimiento)
                    <div class="mt-4 bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-1">Vigencia de la Cotización</p>
                        <p class="font-semibold text-orange-700">
                            Vence el {{ $cotizacione->fecha_vencimiento->format('d/m/Y') }}
                            @if($cotizacione->fecha_vencimiento->isPast())
                                <span class="text-red-600 ml-2">(Expirada)</span>
                            @endif
                        </p>
                    </div>
                @endif
            </div>

            <!-- Servicios -->
            @if($cotizacione->servicios->isNotEmpty())
                <div class="bg-white rounded-xl shadow p-8">
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Servicios</h3>
                    <div class="space-y-3">
                        @foreach($cotizacione->servicios as $item)
                            <div class="flex items-center justify-between border-b border-slate-200 pb-3 last:border-b-0">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $item->servicio->nombre }}</p>
                                    @if($item->servicio->descripcion)
                                        <p class="text-sm text-slate-600">{{ $item->servicio->descripcion }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-slate-900">${{ number_format($item->valor_unitario, 2, '.', ',') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Productos -->
            @if($cotizacione->productos->isNotEmpty())
                <div class="bg-white rounded-xl shadow p-8">
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Productos</h3>
                    <div class="space-y-4">
                        @foreach($cotizacione->productos as $item)
                            <div class="flex items-start justify-between border-b border-slate-200 pb-4 last:border-b-0">
                                <div class="flex-1">
                                    <p class="font-semibold text-slate-900">{{ $item->producto->nombre }}</p>
                                    <p class="text-sm text-slate-600">Cantidad: {{ $item->cantidad }}</p>
                                    @if($item->producto->descripcion)
                                        <p class="text-sm text-slate-600 mt-1">{{ $item->producto->descripcion }}</p>
                                    @endif
                                </div>
                                <div class="text-right ml-4">
                                    <p class="font-semibold text-slate-900">${{ number_format($item->valor_unitario, 2, '.', ',') }} c/u</p>
                                    <p class="text-slate-600 text-sm">Subtotal: ${{ number_format($item->valor_unitario * $item->cantidad, 2, '.', ',') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Resumen de Costos -->
            <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl shadow p-8 border border-orange-200">
                <h3 class="text-lg font-bold text-slate-900 mb-6">Resumen de Costos</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-slate-700">Subtotal:</p>
                        <p class="font-semibold text-slate-900">${{ number_format(($cotizacione->monto_total ?? 0) * 0.9, 2, '.', ',') }}</p>
                    </div>
                    @if($cotizacione->descuento)
                        <div class="flex items-center justify-between text-green-700">
                            <p>Descuento:</p>
                            <p class="font-semibold">-${{ number_format($cotizacione->descuento, 2, '.', ',') }}</p>
                        </div>
                    @endif
                    <div class="border-t-2 border-orange-300 pt-3 flex items-center justify-between">
                        <p class="text-lg font-bold text-slate-900">Total:</p>
                        <p class="text-2xl font-bold text-orange-700">${{ number_format($cotizacione->monto_total ?? 0, 2, '.', ',') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="space-y-6">
            <!-- Vehículo -->
            @if($cotizacione->vehiculo)
                <div class="bg-white rounded-xl shadow p-6 sticky top-8">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Vehículo</h3>
                    
                    @if($cotizacione->vehiculo->fotos->first())
                        <img src="{{ asset('storage/' . $cotizacione->vehiculo->fotos->first()->ruta_foto) }}" alt="Foto del vehículo" class="w-full h-40 object-cover rounded-lg mb-4">
                    @else
                        <div class="w-full h-40 bg-slate-200 rounded-lg flex items-center justify-center mb-4">
                            <i class="bi bi-car-front text-4xl text-slate-400"></i>
                        </div>
                    @endif

                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-1">Placa</p>
                            <p class="font-mono text-lg font-bold text-slate-900">{{ $cotizacione->vehiculo->placa }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-1">Marca y Modelo</p>
                            <p class="text-slate-900 font-semibold">{{ $cotizacione->vehiculo->marca }} {{ $cotizacione->vehiculo->modelo }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-1">Año</p>
                            <p class="text-slate-900 font-semibold">{{ $cotizacione->vehiculo->anio }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-600 uppercase tracking-wide font-semibold mb-1">Color</p>
                            <p class="text-slate-900 font-semibold">{{ $cotizacione->vehiculo->color }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Acciones -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Acciones</h3>
                <div class="space-y-2">
                    @if($cotizacione->estado === 'Pendiente')
                        <form action="{{ route('cliente.cotizaciones.aprobar', $cotizacione) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="w-full text-center bg-green-500 text-white px-4 py-2.5 rounded-lg hover:bg-green-600 font-semibold transition">
                                Aprobar Cotización
                            </button>
                        </form>
                        <form action="{{ route('cliente.cotizaciones.rechazar', $cotizacione) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="w-full text-center bg-red-500 text-white px-4 py-2.5 rounded-lg hover:bg-red-600 font-semibold transition" onclick="return confirm('¿Estás seguro de que deseas rechazar esta cotización?');">
                                Rechazar Cotización
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('cliente.cotizaciones.index') }}" class="block text-center bg-slate-300 text-slate-900 px-4 py-2.5 rounded-lg hover:bg-slate-400 font-semibold transition">
                        Volver a Cotizaciones
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
