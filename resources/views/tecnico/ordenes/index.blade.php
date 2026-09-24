@extends('layouts.tecnico')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-900">Órdenes de Trabajo</h1>
        <p class="text-slate-600 mt-2">Gestiona las órdenes asignadas a ti</p>
    </div>

    @if($ordenes->isEmpty())
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
            <p class="text-blue-800">No hay órdenes asignadas en este momento</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-slate-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">ID</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Vehículo</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Estado</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Fecha Ingreso</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($ordenes as $orden)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-sm text-slate-900">#{{ $orden->id_orden }}</td>
                            <td class="px-6 py-4 text-sm text-slate-900">{{ $orden->vehiculo->placa ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                    {{ $orden->estado->nombre ?? 'Pendiente' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $orden->fecha_ingreso->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('tecnico.ordenes.show', $orden) }}" class="text-blue-600 hover:text-blue-700 font-semibold">Ver</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
