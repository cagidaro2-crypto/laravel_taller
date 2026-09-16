@extends('layouts.admin')
@section('title','Detalle Usuario')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-slate-800">Detalle de usuario</h2>
    <a href="{{ route('admin.usuarios.index') }}" class="text-sm text-slate-500 hover:text-slate-800 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Volver
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-lg">
    <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
        <div class="w-14 h-14 rounded-2xl bg-orange-100 text-orange-600 text-xl font-bold flex items-center justify-center">
            {{ strtoupper(substr($usuario->nombre,0,1)) }}
        </div>
        <div>
            <p class="font-bold text-slate-800 text-lg">{{ $usuario->nombre }}</p>
            <p class="text-slate-500 text-sm">{{ $usuario->correo }}</p>
        </div>
    </div>
    <dl class="space-y-3 text-sm">
        <div class="flex justify-between"><dt class="text-slate-500">Rol</dt><dd><span class="bg-slate-100 text-slate-700 text-xs font-medium px-2.5 py-1 rounded-full">{{ $usuario->rol->nombre_rol }}</span></dd></div>
        <div class="flex justify-between"><dt class="text-slate-500">Teléfono</dt><dd class="text-slate-800">{{ $usuario->telefono ?? '—' }}</dd></div>
        <div class="flex justify-between"><dt class="text-slate-500">Estado</dt>
            <dd>
                @if($usuario->activo)
                    <span class="bg-green-100 text-green-700 text-xs font-medium px-2.5 py-1 rounded-full">Activo</span>
                @else
                    <span class="bg-red-100 text-red-700 text-xs font-medium px-2.5 py-1 rounded-full">Inactivo</span>
                @endif
            </dd>
        </div>
    </dl>
    <div class="mt-6">
        <a href="{{ route('admin.usuarios.edit',$usuario) }}" class="inline-flex items-center gap-2 bg-orange-500 text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-orange-600 transition-colors">Editar</a>
    </div>
</div>
@endsection
