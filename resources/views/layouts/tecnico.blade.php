@extends('layouts.dashboard')

@section('role', 'Técnico')
@section('sidebar-class', 'sidebar-tecnico')
@section('badge-class', 'role-badge-tecnico')

@section('nav-items')
    @php
        $navTec = [
            ['route'=>'tecnico.dashboard',      'label'=>'Dashboard',       'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['route'=>'tecnico.ordenes.index',  'label'=>'Mis Órdenes',     'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
            ['route'=>'tecnico.vehiculos.index','label'=>'Vehículos',       'icon'=>'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h1m6-9h5l3 3v4h-1'],
            ['route'=>'tecnico.citas.index',    'label'=>'Citas',           'icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ['route'=>'tecnico.historial.index','label'=>'Historial',       'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['route'=>'tecnico.ventas.index',   'label'=>'Registrar Venta', 'icon'=>'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
            ['route'=>'tecnico.facturas.index', 'label'=>'Facturas',        'icon'=>'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z'],
        ];
    @endphp
    @foreach($navTec as $item)
        @php $active = request()->routeIs($item['route'].'*'); @endphp
        <a href="{{ route($item['route']) }}" class="nav-item {{ $active ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="{{ $item['icon'] }}"/>
            </svg>
            {{ $item['label'] }}
        </a>
    @endforeach
@endsection
