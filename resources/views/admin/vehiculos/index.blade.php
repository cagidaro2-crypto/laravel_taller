@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Gestión de Vehículos</h1>
        <p class="text-slate-600 mt-2">Visualiza y administra todos los vehículos del sistema</p>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <form method="GET" action="{{ route('admin.vehiculos.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Placa</label>
                <input type="text" name="placa" placeholder="Buscar por placa" value="{{ request('placa') }}" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Cliente</label>
                <input type="text" name="cliente" placeholder="Buscar por cliente" value="{{ request('cliente') }}" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Estado</label>
                <select name="estado" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Todos los estados</option>
                    @foreach($estados as $estado)
                        <option value="{{ $estado->id_estado }}" {{ request('estado') == $estado->id_estado ? 'selected' : '' }}>
                            {{ $estado->nombre ?? $estado->nombre_estado }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700 font-semibold transition">
                    Filtrar
                </button>
                <a href="{{ route('admin.vehiculos.index') }}" class="flex-1 bg-slate-300 text-slate-900 px-4 py-2.5 rounded-lg hover:bg-slate-400 font-semibold transition text-center">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla de Vehículos -->
    @if($vehiculos->isEmpty())
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-12 text-center">
            <i class="bi bi-car-front-2 text-5xl text-blue-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-900 mb-2">No hay vehículos registrados</h3>
            <p class="text-slate-600">Comienza agregando vehículos al sistema</p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Placa</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Vehículo</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Cliente</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Estado</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-slate-900">Órdenes</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-slate-900">Ventas</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-slate-900">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($vehiculos as $vehiculo)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-mono font-bold text-slate-900">{{ $vehiculo->placa }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-slate-900 font-semibold">{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</div>
                                    <div class="text-slate-600 text-sm">{{ $vehiculo->anio }} - {{ $vehiculo->color }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-slate-900 font-semibold">{{ $vehiculo->cliente?->usuario->nombre ?? 'N/A' }}</div>
                                    <div class="text-slate-600 text-sm">{{ $vehiculo->cliente?->usuario->correo ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                        {{ str_contains($vehiculo->estado?->nombre, 'Entregado') ? 'bg-green-100 text-green-700' : 
                                           (str_contains($vehiculo->estado?->nombre, 'Reparación') ? 'bg-yellow-100 text-yellow-700' : 
                                            'bg-blue-100 text-blue-700') }}">
                                        {{ $vehiculo->estado?->nombre ?? $vehiculo->estado?->nombre_estado ?? 'Sin estado' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">
                                        {{ $vehiculo->ordenesTrabajo->count() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                                        {{ $vehiculo->ventas->count() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.vehiculos.show', $vehiculo) }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                                        Ver Detalles
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación -->
        <div class="mt-8">
            {{ $vehiculos->links() }}
        </div>
    @endif
</div>
@endsection
