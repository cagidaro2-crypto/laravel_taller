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

    <!-- Sección Materiales Consumidos -->
    <div class="bg-orange-50 rounded-lg shadow p-6 mt-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Materiales Consumidos</h2>
                <p class="text-sm text-slate-600 mt-1">
                    @if($ordene->consumoMateriales->isEmpty())
                        No hay materiales registrados aún
                    @else
                        {{ $ordene->consumoMateriales->count() }} material(es) registrado(s)
                        • Total: <span class="font-bold text-orange-600">${{ number_format($ordene->consumoMateriales->sum('subtotal'), 2) }}</span>
                    @endif
                </p>
            </div>
            <a href="{{ route('tecnico.consumo-materiales.show', $ordene->id_orden) }}" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 font-semibold">
                Gestionar Materiales
            </a>
        </div>
    </div>

    <!-- Cambiar Estado del Vehículo -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg shadow p-6 mt-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Estado del Vehículo</h2>
                <p class="text-sm text-slate-600 mt-1">
                    Estado Actual: <span class="font-bold text-blue-600">{{ $ordene->vehiculo->estado->nombre_estado ?? 'N/A' }}</span>
                </p>
            </div>
            <button onclick="openEstadoVehiculoModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold">
                Cambiar Estado
            </button>
        </div>

        <!-- Historial de Cambios -->
        <div class="mt-4">
            <p class="text-sm font-semibold text-slate-700 mb-2">Cambios Recientes:</p>
            @if($ordene->vehiculo->historial->isEmpty())
                <p class="text-slate-600 text-sm">Sin historial de cambios</p>
            @else
                <div class="space-y-2">
                    @foreach($ordene->vehiculo->historial->sortByDesc('created_at')->take(5) as $cambio)
                        <div class="bg-white rounded p-3 text-sm border-l-4 border-blue-400">
                            <p class="font-semibold text-slate-900">{{ $cambio->estado_anterior }} → {{ $cambio->estado_nuevo }}</p>
                            <p class="text-slate-600">{{ $cambio->created_at->format('d/m/Y H:i') }}</p>
                            @if($cambio->descripcion)
                                <p class="text-slate-600 italic mt-1">{{ $cambio->descripcion }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Modal para cambiar estado del vehículo -->
    <div id="estadoVehiculoModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full mx-4">
            <h3 class="text-2xl font-bold text-slate-900 mb-4">Cambiar Estado del Vehículo</h3>
            
            <form action="{{ route('tecnico.ordenes.estado-vehiculo', $ordene->id_orden) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nuevo Estado *</label>
                    <select name="id_estado_vehiculo" required class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Selecciona un estado --</option>
                        @php
                            $estadosDisponibles = App\Models\Tecnico\EstadoVehiculo::all();
                        @endphp
                        @foreach($estadosDisponibles as $estado)
                            <option value="{{ $estado->id_estado }}" 
                                {{ $ordene->vehiculo->id_estado == $estado->id_estado ? 'selected' : '' }}>
                                {{ $estado->nombre_estado }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Descripción / Observaciones</label>
                    <textarea name="descripcion" rows="3" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ej: Se completó el diagnóstico..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold transition">
                        Guardar Cambio
                    </button>
                    <button type="button" onclick="closeEstadoVehiculoModal()" class="flex-1 bg-slate-300 text-slate-900 px-4 py-2 rounded-lg hover:bg-slate-400 font-semibold transition">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEstadoVehiculoModal() {
            document.getElementById('estadoVehiculoModal').classList.remove('hidden');
        }

        function closeEstadoVehiculoModal() {
            document.getElementById('estadoVehiculoModal').classList.add('hidden');
        }

        // Cerrar modal al presionar Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeEstadoVehiculoModal();
            }
        });
    </script>
</div>
@endsection
