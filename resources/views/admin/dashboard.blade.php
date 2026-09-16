@extends('layouts.admin')
@section('title','Dashboard')

@section('content')

{{-- Welcome --}}
<div class="page-header">
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
        <div>
            <h2 class="page-title">Bienvenido, {{ auth()->user()->nombre }}</h2>
            <p class="page-subtitle">Resumen ejecutivo del sistema — {{ now()->isoFormat('D [de] MMMM [de] YYYY') }}</p>
        </div>
        <div style="display:flex; align-items:center; gap:8px;">
            <span class="badge badge-gold">
                <span class="badge-dot"></span>
                Sistema activo
            </span>
        </div>
    </div>
</div>

{{-- Stats Grid --}}
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px; margin-bottom:28px;">

    {{-- Órdenes activas --}}
    <div class="stat-card stat-card-blue">
        <div class="stat-icon" style="background:linear-gradient(135deg,#dbeafe,#bfdbfe);">
            <svg fill="none" stroke="#1e3a5f" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
        </div>
        <p class="stat-label">Órdenes activas</p>
        <p class="stat-value">{{ $totalOrdenes }}</p>
        <p class="stat-trend">
            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
            En progreso
        </p>
    </div>

    {{-- Clientes --}}
    <div class="stat-card stat-card-gold">
        <div class="stat-icon" style="background:linear-gradient(135deg,#fef9c3,#fef08a);">
            <svg fill="none" stroke="#854d0e" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <p class="stat-label">Clientes</p>
        <p class="stat-value">{{ $totalClientes }}</p>
        <p class="stat-trend">Registrados en el sistema</p>
    </div>

    {{-- Bajo stock --}}
    <div class="stat-card {{ $bajosStock > 0 ? 'stat-card-amber' : 'stat-card-green' }}">
        <div class="stat-icon" style="background:{{ $bajosStock > 0 ? 'linear-gradient(135deg,#fef9c3,#fde68a)' : 'linear-gradient(135deg,#dcfce7,#bbf7d0)' }}">
            <svg fill="none" stroke="{{ $bajosStock > 0 ? '#92400e' : '#166534' }}" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
            </svg>
        </div>
        <p class="stat-label">Bajo stock</p>
        <p class="stat-value" style="{{ $bajosStock > 0 ? 'color:#d97706;' : '' }}">{{ $bajosStock }}</p>
        <p class="stat-trend">{{ $bajosStock > 0 ? 'Requieren atención' : 'Inventario correcto' }}</p>
    </div>

    {{-- Citas hoy --}}
    <div class="stat-card stat-card-green">
        <div class="stat-icon" style="background:linear-gradient(135deg,#dcfce7,#bbf7d0);">
            <svg fill="none" stroke="#166534" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="stat-label">Citas hoy</p>
        <p class="stat-value">{{ $citasHoy }}</p>
        <p class="stat-trend">{{ now()->format('d/m/Y') }}</p>
    </div>

</div>

{{-- Alert bajo stock --}}
@if($bajosStock > 0)
<div class="alert alert-warning">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
    </svg>
    <span>
        Hay <strong>{{ $bajosStock }}</strong> producto(s) con stock bajo.
        <a href="{{ route('admin.inventario.index') }}" style="font-weight:600; text-decoration:underline; margin-left:4px;">Ver inventario →</a>
    </span>
</div>
@endif

@endsection

