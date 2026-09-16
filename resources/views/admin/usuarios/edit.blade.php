@extends('layouts.admin')
@section('title','Editar Usuario')

@section('content')

<div class="page-header">
    <div class="page-header-inner">
        <div>
            <h2 class="page-title">Editar usuario</h2>
            <p class="page-subtitle">Actualice la información del usuario seleccionado</p>
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
    <form method="POST" action="{{ route('admin.usuarios.update',$usuario) }}">
        @csrf @method('PUT')

        <div class="form-section">
            <p class="form-section-title">Información personal</p>
            <div class="form-group">
                <label class="form-label">Nombre completo <span class="required">*</span></label>
                <input type="text" name="nombre" value="{{ old('nombre',$usuario->nombre) }}"
                       class="form-control" required>
            </div>
            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Correo electrónico <span class="required">*</span></label>
                    <input type="email" name="correo" value="{{ old('correo',$usuario->correo) }}"
                           class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono',$usuario->telefono) }}"
                           class="form-control" placeholder="Ej: 300 123 4567">
                </div>
            </div>
        </div>

        <div class="form-section">
            <p class="form-section-title">Rol y estado</p>
            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Rol del usuario <span class="required">*</span></label>
                    <select name="id_rol" class="form-control" required>
                        @foreach($roles as $rol)
                            <option value="{{ $rol->id_rol }}" {{ $usuario->id_rol == $rol->id_rol ? 'selected' : '' }}>
                                {{ $rol->nombre_rol }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Estado del usuario</label>
                    <div style="display:flex; align-items:center; gap:10px; padding:9px 13px; border:1.5px solid #e2e8f0; border-radius:9px; background:#fff;">
                        <input type="checkbox" name="activo" id="activo" value="1"
                               style="width:16px;height:16px;accent-color:#1e3a5f;cursor:pointer;"
                               {{ $usuario->activo ? 'checked' : '' }}>
                        <label for="activo" style="font-size:13.5px;color:#1e293b;font-weight:500;cursor:pointer;margin:0;">
                            Usuario activo
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex; align-items:center; gap:12px; padding-top:4px;">
            <button type="submit" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 13l4 4L19 7"/>
                </svg>
                Guardar cambios
            </button>
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>

@endsection

