@extends('layouts.cliente')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-900">Agendar Cita</h1>
        <p class="text-slate-600 mt-2">Reserva una cita para tu vehículo</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow p-6">
                <form action="{{ route('cliente.citas.store') }}" method="POST">
                    @csrf

                    <!-- Vehículo -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Vehículo *</label>
                        <select name="id_vehiculo" required class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none">
                            <option value="">Selecciona un vehículo</option>
                            @foreach($vehiculos as $vehiculo)
                                <option value="{{ $vehiculo->id_vehiculo }}" {{ old('id_vehiculo') == $vehiculo->id_vehiculo ? 'selected' : '' }}>
                                    {{ $vehiculo->placa }} - {{ $vehiculo->marca }} {{ $vehiculo->modelo }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_vehiculo')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Fecha *</label>
                        <input type="date" name="fecha" value="{{ old('fecha') }}" required 
                            min="{{ date('Y-m-d') }}"
                            class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none">
                        @error('fecha')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hora -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Hora *</label>
                        <input type="time" name="hora" value="{{ old('hora') }}" required
                            class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none">
                        @error('hora')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Motivo -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Motivo de la cita</label>
                        <textarea name="motivo" rows="4" placeholder="Describe el problema o motivo de la cita..."
                            class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none">{{ old('motivo') }}</textarea>
                        @error('motivo')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-4">
                        <button type="submit" class="bg-orange-500 text-white px-8 py-2.5 rounded-lg hover:bg-orange-600 font-semibold transition">
                            Agendar Cita
                        </button>
                        <a href="{{ route('cliente.citas.index') }}" class="bg-slate-300 text-slate-900 px-8 py-2.5 rounded-lg hover:bg-slate-400 font-semibold transition">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Información del vehículo seleccionado -->
        <div id="vehiculoInfo" class="hidden lg:block">
            <div class="bg-white rounded-xl shadow p-6 sticky top-8">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Información del Vehículo</h3>
                <div id="infoContent">
                    <p class="text-slate-600">Selecciona un vehículo para ver detalles</p>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="mt-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <p class="font-bold">Por favor revisa los errores:</p>
            @foreach($errors->all() as $error)
                <p class="text-sm">• {{ $error }}</p>
            @endforeach
        </div>
    @endif
</div>

<script>
// Datos de vehículos con fotos
const vehiculosData = {
    @foreach($vehiculos as $vehiculo)
        {{ $vehiculo->id_vehiculo }}: {
            placa: '{{ $vehiculo->placa }}',
            marca: '{{ $vehiculo->marca }}',
            modelo: '{{ $vehiculo->modelo }}',
            anio: '{{ $vehiculo->anio }}',
            color: '{{ $vehiculo->color }}',
            foto: '{{ $vehiculo->fotos->first() ? asset('storage/' . $vehiculo->fotos->first()->ruta_foto) : '' }}'
        },
    @endforeach
};

document.querySelector('select[name="id_vehiculo"]').addEventListener('change', function() {
    const vehiculoId = this.value;
    const infoContent = document.getElementById('infoContent');
    const vehiculoInfo = document.getElementById('vehiculoInfo');

    if (vehiculoId && vehiculosData[vehiculoId]) {
        const v = vehiculosData[vehiculoId];
        vehiculoInfo.classList.remove('hidden');
        infoContent.innerHTML = `
            <div class="space-y-4">
                ${v.foto ? `<img src="${v.foto}" alt="Foto del vehículo" class="w-full h-48 object-cover rounded-lg">` : '<div class="w-full h-48 bg-slate-200 rounded-lg flex items-center justify-center"><i class="bi bi-car-front text-3xl text-slate-400"></i></div>'}
                <div>
                    <p class="text-sm text-slate-600">Placa</p>
                    <p class="font-semibold text-slate-900 font-mono text-lg">${v.placa}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600">Marca y Modelo</p>
                    <p class="font-semibold text-slate-900">${v.marca} ${v.modelo}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600">Año</p>
                    <p class="font-semibold text-slate-900">${v.anio || 'N/A'}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600">Color</p>
                    <p class="font-semibold text-slate-900">${v.color || 'N/A'}</p>
                </div>
            </div>
        `;
    } else {
        vehiculoInfo.classList.add('hidden');
    }
});
</script>
@endsection
