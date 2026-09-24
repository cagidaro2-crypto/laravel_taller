<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cliente — Taller Latonería</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-full bg-slate-950 text-slate-100 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 selection:bg-orange-500 selection:text-white">

    <!-- Luces de fondo ambientales -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[700px] h-[500px] bg-orange-600/15 rounded-full blur-[140px]"></div>
        <div class="absolute bottom-0 right-10 w-[500px] h-[400px] bg-amber-500/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="relative z-10 sm:mx-auto sm:w-full sm:max-w-2xl">
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <a href="{{ route('inicio') }}" class="inline-flex items-center gap-3 group">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center shadow-lg shadow-orange-500/30 group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                    </svg>
                </div>
                <div class="text-left">
                    <span class="text-xl font-extrabold tracking-tight text-white block">Taller Latonería</span>
                    <span class="text-xs font-semibold tracking-wider uppercase text-orange-400">Portal del Cliente</span>
                </div>
            </a>
            <h2 class="mt-6 text-3xl font-extrabold text-white tracking-tight">Crea tu cuenta de cliente</h2>
            <p class="mt-2 text-sm text-slate-400 max-w-md mx-auto">
                Registra tus datos para dar de alta tus vehículos, agendar citas de taller y descargar tus facturas digitales.
            </p>
        </div>

        <!-- Tarjeta del Formulario -->
        <div class="bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-3xl p-8 sm:p-10 shadow-2xl shadow-black/60">

            @auth
                <div class="mb-6 p-4 rounded-2xl bg-amber-500/10 border border-amber-500/25 text-amber-200 text-sm space-y-2">
                    <div class="flex items-center gap-2 font-semibold text-amber-300">
                        <svg class="w-5 h-5 flex-shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Sesión activa actualmente</span>
                    </div>
                    <p class="text-xs text-slate-300">
                        Estás conectado como <strong class="text-white">{{ Auth::user()->nombre }}</strong>. Al registrar un nuevo cliente, se creará su cuenta y accederás con el nuevo perfil.
                    </p>
                    <div class="pt-1">
                        <a href="{{ route('logout') }}" class="text-xs font-bold text-rose-400 hover:text-rose-300 underline">
                            Cerrar sesión actual &rarr;
                        </a>
                    </div>
                </div>
            @endauth

            @if($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-300 text-sm space-y-1">
                    <div class="flex items-center gap-2 font-semibold text-rose-200">
                        <svg class="w-5 h-5 flex-shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>Corrige los siguientes campos:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs pl-6 space-y-0.5 text-rose-300/90">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" class="space-y-6">
                @csrf

                <!-- Fila 1: Nombre Completo -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Nombre completo <span class="text-orange-400">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="nombre" 
                        value="{{ old('nombre') }}" 
                        required 
                        placeholder="Ej. Juan Carlos Pérez"
                        class="w-full px-4 py-3 bg-slate-950/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all shadow-sm @error('nombre') border-rose-500 @enderror"
                    >
                </div>

                <!-- Fila 2: Documento y Teléfono -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                            Identificación / Cédula <span class="text-orange-400">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="documento" 
                            value="{{ old('documento') }}" 
                            required 
                            placeholder="Número de documento"
                            class="w-full px-4 py-3 bg-slate-950/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all shadow-sm @error('documento') border-rose-500 @enderror"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                            Teléfono / Móvil
                        </label>
                        <input 
                            type="text" 
                            name="telefono" 
                            value="{{ old('telefono') }}" 
                            placeholder="Ej. +57 300 123 4567"
                            class="w-full px-4 py-3 bg-slate-950/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all shadow-sm"
                        >
                    </div>
                </div>

                <!-- Fila 3: Correo y Dirección -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                            Correo electrónico <span class="text-orange-400">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="correo" 
                            value="{{ old('correo') }}" 
                            required 
                            placeholder="tu@correo.com"
                            class="w-full px-4 py-3 bg-slate-950/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all shadow-sm @error('correo') border-rose-500 @enderror"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                            Dirección de residencia
                        </label>
                        <input 
                            type="text" 
                            name="direccion" 
                            value="{{ old('direccion') }}" 
                            placeholder="Ciudad, Calle o Carrera"
                            class="w-full px-4 py-3 bg-slate-950/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all shadow-sm"
                        >
                    </div>
                </div>

                <!-- Fila 4: Contraseña y Confirmación -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                            Contraseña <span class="text-orange-400">*</span>
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            required 
                            placeholder="••••••••"
                            class="w-full px-4 py-3 bg-slate-950/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all shadow-sm @error('password') border-rose-500 @enderror"
                        >
                        <p class="text-[11px] text-slate-400 mt-1">Mínimo 6 caracteres.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                            Confirmar contraseña <span class="text-orange-400">*</span>
                        </label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            required 
                            placeholder="••••••••"
                            class="w-full px-4 py-3 bg-slate-950/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all shadow-sm"
                        >
                    </div>
                </div>

                <!-- Botón de Registro -->
                <button 
                    type="submit" 
                    class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-bold text-sm shadow-lg shadow-orange-500/25 hover:shadow-orange-500/35 transition-all duration-200 transform active:scale-98 flex items-center justify-center gap-2"
                >
                    <span>Completar Registro</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-800 text-center">
                <p class="text-sm text-slate-400">
                    ¿Ya tienes una cuenta registrada? 
                    <a href="{{ route('login') }}" class="font-bold text-orange-400 hover:text-orange-300 transition-colors ml-1">
                        Inicia sesión aquí
                    </a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>
