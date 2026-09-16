@php $role = auth()->user()->rol->nombre_rol ?? ''; @endphp

@if($role === 'Administrador')
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </a>
    <a href="{{ route('admin.usuarios.index') }}" class="{{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
        <i class="bi bi-people me-2"></i>Usuarios
    </a>
    <a href="{{ route('admin.ordenes.index') }}" class="{{ request()->routeIs('admin.ordenes.*') ? 'active' : '' }}">
        <i class="bi bi-clipboard2-check me-2"></i>Órdenes
    </a>
    <a href="{{ route('admin.cotizaciones.index') }}" class="{{ request()->routeIs('admin.cotizaciones.*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-text me-2"></i>Cotizaciones
    </a>
    <a href="{{ route('admin.productos.index') }}" class="{{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
        <i class="bi bi-box-seam me-2"></i>Productos
    </a>
    <a href="{{ route('admin.inventario.index') }}" class="{{ request()->routeIs('admin.inventario.*') ? 'active' : '' }}">
        <i class="bi bi-archive me-2"></i>Inventario
    </a>
    <a href="{{ route('admin.proveedores.index') }}" class="{{ request()->routeIs('admin.proveedores.*') ? 'active' : '' }}">
        <i class="bi bi-truck me-2"></i>Proveedores
    </a>
    <a href="{{ route('admin.servicios.index') }}" class="{{ request()->routeIs('admin.servicios.*') ? 'active' : '' }}">
        <i class="bi bi-gear me-2"></i>Servicios
    </a>

@elseif($role === 'Técnico')
    <a href="{{ route('tecnico.dashboard') }}" class="{{ request()->routeIs('tecnico.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </a>
    <a href="{{ route('tecnico.ordenes.index') }}" class="{{ request()->routeIs('tecnico.ordenes.*') ? 'active' : '' }}">
        <i class="bi bi-clipboard2-check me-2"></i>Mis Órdenes
    </a>
    <a href="{{ route('tecnico.vehiculos.index') }}" class="{{ request()->routeIs('tecnico.vehiculos.*') ? 'active' : '' }}">
        <i class="bi bi-car-front me-2"></i>Vehículos
    </a>
    <a href="{{ route('tecnico.citas.index') }}" class="{{ request()->routeIs('tecnico.citas.*') ? 'active' : '' }}">
        <i class="bi bi-calendar3 me-2"></i>Citas
    </a>
    <a href="{{ route('tecnico.historial.index') }}" class="{{ request()->routeIs('tecnico.historial.*') ? 'active' : '' }}">
        <i class="bi bi-clock-history me-2"></i>Historial
    </a>

@elseif($role === 'Cliente')
    <a href="{{ route('cliente.dashboard') }}" class="{{ request()->routeIs('cliente.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2 me-2"></i>Mi Panel
    </a>
    <a href="{{ route('cliente.vehiculos.index') }}" class="{{ request()->routeIs('cliente.vehiculos.*') ? 'active' : '' }}">
        <i class="bi bi-car-front me-2"></i>Mis Vehículos
    </a>
    <a href="{{ route('cliente.citas.index') }}" class="{{ request()->routeIs('cliente.citas.*') ? 'active' : '' }}">
        <i class="bi bi-calendar3 me-2"></i>Mis Citas
    </a>
    <a href="{{ route('cliente.cotizaciones.index') }}" class="{{ request()->routeIs('cliente.cotizaciones.*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-text me-2"></i>Cotizaciones
    </a>
    <a href="{{ route('cliente.historial.index') }}" class="{{ request()->routeIs('cliente.historial.*') ? 'active' : '' }}">
        <i class="bi bi-clock-history me-2"></i>Historial
    </a>
@endif
