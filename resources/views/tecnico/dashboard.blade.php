@extends('layouts.tecnico')
@section('title','Dashboard')

@section('content')
<div class="mb-6">
    <h2 class="text-xl font-bold text-slate-800">Hola, {{ auth()->user()->nombre }}</h2>
    <p class="text-slate-500 text-sm mt-0.5">{{ now()->format('d/m/Y') }}</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
        <div>
            <p class="text-slate-500 text-xs font-medium uppercase tracking-wide">Mis órdenes activas</p>
            <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ $misOrdenes }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
        <div>
            <p class="text-slate-500 text-xs font-medium uppercase tracking-wide">Citas hoy</p>
            <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ $citasHoy }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
        <div>
            <p class="text-slate-500 text-xs font-medium uppercase tracking-wide">Vehículos en taller</p>
            <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ $vehiculosEnTaller }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h1m6-9h5l3 3v4h-1"/></svg>
        </div>
    </div>
</div>
@endsection
