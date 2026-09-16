@extends('layouts.admin')
@section('title','Registrar Usuario')

@section('content')

<div class="page-header">
    <div class="page-header-inner">
        <div>
            <h2 class="page-title">Registrar usuario</h2>
            <p class="page-subtitle">Complete los datos del nuevo empleado o usuario del sistema</p>
        </div>
        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>
    </div>
</div>

<div class="form-card" style="max-width:680px;">
    <form method="POST" action="{{ route('admin.usuarios.store') }}">
        @csrf

        <div class="form-section">
            <p class="form-section-title">Información personal</p>
            <div class="form-group">
                <label class="form-label">Nombre completo <span class="required">*</span></label>
                <input type="text" name="nombre" value="{{ old('nombre') }}"
                       class="form-control @error('nombre') form-control-error @enderror"
                       placeholder="Ej: Juan García Rodríguez" required>
                @error('nombre')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Correo electrónico <span class="required">*</span></label>
                    <input type="email" name="correo" value="{{ old('correo') }}"
                           class="form-control @error('correo') form-control-error @enderror"
                           placeholder="correo@taller.com" required>
                    @error('correo')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono') }}"
                           class="form-control"
                           placeholder="Ej: 300 123 4567">
                </div>
            </div>
        </div>

        <div class="form-section">
            <p class="form-section-title">Rol y acceso</p>
            <div class="form-group">
                <label class="form-label">Rol del usuario <span class="required">*</span></label>
                <select name="id_rol" class="form-control" required>
                    <option value="">Seleccione un rol...</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->id_rol }}" {{ old('id_rol') == $rol->id_rol ? 'selected' : '' }}>
                            {{ $rol->nombre_rol }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Contraseña <span class="required">*</span></label>
                    <input type="password" name="password"
                           class="form-control @error('password') form-control-error @enderror" required>
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Confirmar contraseña <span class="required">*</span></label>
                    <input type="password" name="password_confirmation"
                           class="form-control" required>
                </div>
            </div>
        </div>

        <div style="display:flex; align-items:center; gap:12px; padding-top:4px;">
            <button type="submit" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 13l4 4L19 7"/>
                </svg>
                Registrar usuario
            </button>
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>
@endsection
