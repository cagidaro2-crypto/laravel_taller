@extends('layouts.admin')
@section('title','Usuarios')

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div class="page-header-inner">
        <div>
            <h2 class="page-title">Usuarios del sistema</h2>
            <p class="page-subtitle">Gestión de empleados y usuarios registrados</p>
        </div>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 4v16m8-8H4"/>
            </svg>
            Registrar usuario
        </a>
    </div>
</div>

{{-- Table --}}
<div class="table-wrapper">

    {{-- Toolbar --}}
    <div class="table-toolbar">
        <div class="table-search">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
            </svg>
            <form method="GET">
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre o correo...">
            </form>
        </div>
        <div style="display:flex; align-items:center; gap:8px;">
            <form method="GET">
                <input type="hidden" name="buscar" value="{{ request('buscar') }}">
                <button type="submit" class="btn btn-outline btn-sm">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><path d="M21 21l-4.35-4.35"/><circle cx="11" cy="11" r="8"/></svg>
                    Buscar
                </button>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Correo electrónico</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th style="text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $u)
                <tr>
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar-sm">{{ strtoupper(substr($u->nombre,0,2)) }}</div>
                            <span class="td-primary">{{ $u->nombre }}</span>
                        </div>
                    </td>
                    <td class="td-muted">{{ $u->correo }}</td>
                    <td>
                        @php
                            $rolNombre = $u->rol->nombre_rol ?? '';
                            $rolClass = match($rolNombre) {
                                'Administrador' => 'badge-role-admin',
                                'Técnico'       => 'badge-role-tecnico',
                                'Cliente'       => 'badge-role-cliente',
                                default         => 'badge-neutral',
                            };
                        @endphp
                        <span class="badge {{ $rolClass }}">{{ $rolNombre }}</span>
                    </td>
                    <td>
                        @if($u->activo)
                            <span class="badge badge-success"><span class="badge-dot"></span> Activo</span>
                        @else
                            <span class="badge badge-error"><span class="badge-dot"></span> Inactivo</span>
                        @endif
                    </td>
                    <td style="text-align:right;">
                        <div style="display:flex; align-items:center; justify-content:flex-end; gap:6px;">
                            <a href="{{ route('admin.usuarios.show',$u) }}" class="btn btn-outline btn-sm">Ver</a>
                            <a href="{{ route('admin.usuarios.edit',$u) }}" class="btn btn-sm" style="background:linear-gradient(135deg,#1e3a5f,#2d5286);color:#fff;box-shadow:0 2px 8px rgba(30,58,95,.25);">Editar</a>
                            <form method="POST" action="{{ route('admin.usuarios.destroy',$u) }}" onsubmit="return confirm('¿Eliminar este usuario?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <p class="empty-state-title">No hay usuarios registrados</p>
                            <p class="empty-state-desc">Agrega el primer usuario con el botón de arriba</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($usuarios->hasPages())
    <div class="pagination-wrapper">
        {{ $usuarios->links() }}
    </div>
    @endif
</div>

@endsection

