@extends('layouts.cliente')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Mis Vehículos</h1>
                <p class="text-slate-600 mt-2">Consulta el estado y seguimiento de tus vehículos</p>
            </div>
            <a href="{{ route('cliente.vehiculos.index') }}" class="bg-slate-500 text-white px-6 py-2 rounded-lg hover:bg-slate-600 font-semibold">
                ← Volver
            </a>
        </div>
    </div>

    <!-- Modal Dialog -->
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                    <i class="bi bi-car-front text-orange-600"></i> Registrar mi Vehículo
                </h2>
                <a href="{{ route('cliente.vehiculos.index') }}" class="text-slate-400 hover:text-slate-600 text-2xl">
                    ✕
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm">• {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Formulario -->
            <form action="{{ route('cliente.vehiculos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Foto del vehículo -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Foto del vehículo <span class="text-gray-500 text-xs">(opcional)</span>
                    </label>
                    <div class="flex items-center gap-2 mb-2">
                        <i class="bi bi-image text-slate-400"></i>
                        <span class="text-xs text-slate-600">Seleccionar foto (JPG, PNG, WEBP)</span>
                    </div>
                    <input 
                        type="file"
                        name="fotos[]"
                        multiple
                        accept="image/jpeg,image/png,image/webp"
                        class="w-full text-sm text-slate-600 file:mr-2 file:py-1.5 file:px-3 file:rounded file:bg-orange-500 file:text-white file:font-semibold hover:file:bg-orange-600 border border-slate-200 rounded p-2"
                    >
                    <div id="fotosPreview" class="grid grid-cols-3 gap-2 mt-3"></div>
                </div>

                <!-- Placa -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Placa *</label>
                    <input 
                        type="text" 
                        name="placa"
                        value="{{ old('placa') }}"
                        placeholder="Ej: ABC-123"
                        class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none {{ $errors->has('placa') ? 'border-red-500' : '' }}"
                    >
                    @error('placa')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Marca y Modelo -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Marca *</label>
                        <input 
                            type="text"
                            name="marca"
                            value="{{ old('marca') }}"
                            placeholder="Toyota, Ford..."
                            class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none {{ $errors->has('marca') ? 'border-red-500' : '' }}"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Modelo *</label>
                        <input 
                            type="text"
                            name="modelo"
                            value="{{ old('modelo') }}"
                            placeholder="Corolla, Focus..."
                            class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none {{ $errors->has('modelo') ? 'border-red-500' : '' }}"
                        >
                    </div>
                </div>

                <!-- Año y Color -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Año *</label>
                        <input 
                            type="number"
                            name="anio"
                            value="{{ old('anio') }}"
                            placeholder="2026"
                            min="1900"
                            max="{{ date('Y') + 1 }}"
                            class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none {{ $errors->has('anio') ? 'border-red-500' : '' }}"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Color</label>
                        <input 
                            type="text"
                            name="color"
                            value="{{ old('color') }}"
                            placeholder="Blanco, Negro..."
                            class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none"
                        >
                    </div>
                </div>

                <!-- Estado Inicial -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Estado inicial</label>
                    <select 
                        name="estado"
                        class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none"
                    >
                        <option value="">En espera</option>
                        <option value="reparacion">En reparación</option>
                        <option value="listo">Listo</option>
                    </select>
                </div>

                <!-- Botones -->
                <div class="flex gap-3 pt-4">
                    <a href="{{ route('cliente.vehiculos.index') }}" class="flex-1 text-center bg-slate-200 text-slate-900 px-4 py-2.5 rounded-lg hover:bg-slate-300 font-semibold text-sm transition">
                        Cancelar
                    </a>
                    <button 
                        type="submit"
                        class="flex-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white px-4 py-2.5 rounded-lg hover:shadow-lg font-semibold text-sm transition flex items-center justify-center gap-2"
                    >
                        <i class="bi bi-check-circle"></i> Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script para preview de fotos -->
<script>
document.querySelector('input[name="fotos[]"]').addEventListener('change', function(e) {
    const preview = document.getElementById('fotosPreview');
    preview.innerHTML = '';
    
    Array.from(this.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(event) {
            const div = document.createElement('div');
            div.className = 'relative group';
            div.innerHTML = `
                <img src="${event.target.result}" class="w-full h-24 object-cover rounded border border-slate-200" alt="Preview">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 rounded transition flex items-center justify-center">
                    <span class="text-white text-xs opacity-0 group-hover:opacity-100">Foto</span>
                </div>
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
});
</script>

@endsection
