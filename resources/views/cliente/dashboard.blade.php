@extends('layouts.cliente')
@section('title','Mi Panel')

@section('content')
<div class="mb-6">
    <h2 class="text-xl font-bold text-slate-800">Bienvenido, {{ auth()->user()->nombre }}</h2>
    <p class="text-slate-500 text-sm mt-0.5">{{ now()->format('d/m/Y') }}</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <a href="{{ route('cliente.vehiculos.index') }}" class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between hover:border-indigo-300 transition-colors group">
        <div>
            <p class="text-slate-500 text-xs font-medium uppercase tracking-wide">Mis vehículos</p>
            <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ $misVehiculos }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-indigo-100 group-hover:bg-indigo-200 flex items-center justify-center transition-colors">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h1m6-9h5l3 3v4h-1"/></svg>
        </div>
    </a>
    <a href="{{ route('cliente.citas.index') }}" class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between hover:border-indigo-300 transition-colors group">
        <div>
            <p class="text-slate-500 text-xs font-medium uppercase tracking-wide">Citas activas</p>
            <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ $misCitas }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-100 group-hover:bg-amber-200 flex items-center justify-center transition-colors">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
    </a>
    <a href="{{ route('cliente.cotizaciones.index') }}" class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between hover:border-indigo-300 transition-colors group">
        <div>
            <p class="text-slate-500 text-xs font-medium uppercase tracking-wide">Cotizaciones</p>
            <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ $cotizacionesPendientes }}</p>
            <span class="text-xs text-amber-600 font-semibold">pendientes</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-green-100 group-hover:bg-green-200 flex items-center justify-center transition-colors">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
    </a>
    <a href="{{ route('cliente.facturas.index') }}" class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between hover:border-indigo-300 transition-colors group">
        <div>
            <p class="text-slate-500 text-xs font-medium uppercase tracking-wide">Mis Facturas</p>
            <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ $misFacturas }}</p>
            @if($facturasPendientes > 0)
                <span class="text-xs text-amber-600 font-semibold">{{ $facturasPendientes }} por pagar</span>
            @else
                <span class="text-xs text-emerald-600 font-semibold">Al día</span>
            @endif
        </div>
        <div class="w-12 h-12 rounded-xl bg-rose-100 group-hover:bg-rose-200 flex items-center justify-center transition-colors">
            <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
        </div>
    </a>
</div>

{{-- Acceso rápido --}}
<div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
    <h3 class="text-sm font-semibold text-slate-700 mb-4">Acciones rápidas</h3>
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('cliente.vehiculos.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-indigo-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Registrar vehículo
        </a>
        <a href="{{ route('cliente.citas.create') }}" class="inline-flex items-center gap-2 bg-amber-500 text-white text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-amber-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Agendar cita
        </a>
        <a href="{{ route('cliente.facturas.index') }}" class="inline-flex items-center gap-2 bg-slate-800 text-white text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-slate-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
            Consultar facturas
        </a>
    </div>
</div>
@endsection
