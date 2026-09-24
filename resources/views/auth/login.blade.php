<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — Taller Latonería y Pintura</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-full bg-slate-950 text-slate-100 flex flex-col justify-center selection:bg-orange-500 selection:text-white">

    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- ══════════════════════════════════════════════════
             PANEL IZQUIERDO: Branding y Experiencia Visual
        ══════════════════════════════════════════════════ -->
        <div class="relative hidden lg:flex lg:w-1/2 xl:w-7/12 flex-col justify-between p-12 xl:p-16 overflow-hidden">
            <!-- Imagen de fondo con gradientes de oscurecimiento y color -->
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?w=1600&auto=format&fit=crop&q=80" 
                     alt="Taller de Latonería y Pintura" 
                     class="w-full h-full object-cover scale-105 filter brightness-75 contrast-110">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/70 to-slate-950/40"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-950/40 to-transparent"></div>
                <!-- Luces ambientales de acento -->
                <div class="absolute -top-24 -left-24 w-96 h-96 bg-orange-600/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 right-0 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
            </div>

            <!-- Top: Logo y Marca -->
            <div class="relative z-10">
                <a href="{{ route('inicio') }}" class="inline-flex items-center gap-3 group">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center shadow-lg shadow-orange-500/30 group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 7v5"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-extrabold tracking-tight text-white block">Taller Latonería</span>
                        <span class="text-xs font-semibold tracking-wider uppercase text-orange-400">Pintura & Restauración</span>
                    </div>
                </a>
            </div>

            <!-- Bottom: Mensaje, Testimonio y Estadísticas -->
            <div class="relative z-10 space-y-8 max-w-xl">
                <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-orange-300 text-xs font-semibold uppercase tracking-wider shadow-inner">
                    <span class="w-2 h-2 rounded-full bg-orange-400 animate-ping"></span>
                    Portal de Gestión & Trazabilidad
                </div>

                <div class="space-y-4">
                    <h1 class="text-4xl xl:text-5xl font-extrabold tracking-tight text-white leading-tight">
                        Precisión automotriz con <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 via-amber-300 to-yellow-400">control en tiempo real</span>.
                    </h1>
                    <p class="text-slate-300 text-base xl:text-lg leading-relaxed font-normal">
                        Monitorea el progreso de tu vehículo, revisa cotizaciones, recibe actualizaciones con fotos y descarga tus facturas al instante.
                    </p>
                </div>

                <!-- Métricas destacadas -->
                <div class="grid grid-cols-3 gap-4 pt-6 border-t border-white/10">
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 p-4 rounded-2xl">
                        <p class="text-2xl xl:text-3xl font-black text-orange-400">+500</p>
                        <p class="text-xs font-medium text-slate-400 mt-1 uppercase tracking-wider">Vehículos atendidos</p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 p-4 rounded-2xl">
                        <p class="text-2xl xl:text-3xl font-black text-emerald-400">98%</p>
                        <p class="text-xs font-medium text-slate-400 mt-1 uppercase tracking-wider">Clientes conformes</p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 p-4 rounded-2xl">
                        <p class="text-2xl xl:text-3xl font-black text-amber-300">100%</p>
                        <p class="text-xs font-medium text-slate-400 mt-1 uppercase tracking-wider">Digital & Seguro</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════
             PANEL DERECHO: Formulario de Login Moderno
        ══════════════════════════════════════════════════ -->
        <div class="flex-1 flex flex-col justify-center px-6 py-12 sm:px-12 lg:px-16 xl:px-24 bg-slate-900 border-l border-slate-800/80">
            <div class="mx-auto w-full max-w-md">

                <!-- Header para móviles -->
                <div class="lg:hidden mb-8 text-center">
                    <a href="{{ route('inicio') }}" class="inline-flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center shadow-lg shadow-orange-500/25">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-black text-white">Taller Latonería</span>
                    </a>
                </div>

                <!-- Título del formulario -->
                <div class="mb-8">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Iniciar Sesión</h2>
                    <p class="text-slate-400 text-sm mt-2">
                        Accede a tu cuenta según tu rol asignado (Cliente, Técnico o Administrador).
                    </p>
                </div>

                <!-- Alertas de Éxito / Error -->
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-sm flex items-center gap-3 animate-fade-in">
                        <svg class="w-5 h-5 flex-shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-300 text-sm space-y-1">
                        <div class="flex items-center gap-2 font-semibold text-rose-200">
                            <svg class="w-5 h-5 flex-shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>No se pudo iniciar sesión</span>
                        </div>
                        <ul class="list-disc list-inside text-xs pl-6 space-y-0.5 text-rose-300/90">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Píldoras de Acceso Rápido / Demo Roles -->
                <div class="mb-6 p-3.5 rounded-2xl bg-slate-950/60 border border-slate-800">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-orange-400" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.477.859h4z"/></svg>
                        Relleno rápido de credenciales:
                    </p>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" onclick="fillCreds('cliente@taller.com', 'Cliente123!')" 
                                class="px-3 py-1.5 rounded-xl bg-purple-500/10 hover:bg-purple-500/20 border border-purple-500/30 text-purple-300 text-xs font-semibold transition text-center hover:scale-102">
                            👤 Cliente
                        </button>
                        <button type="button" onclick="fillCreds('tecnico@taller.com', 'Tecnico123!')" 
                                class="px-3 py-1.5 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-xs font-semibold transition text-center hover:scale-102">
                            🔧 Técnico
                        </button>
                        <button type="button" onclick="fillCreds('admin@taller.com', 'Admin123!')" 
                                class="px-3 py-1.5 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 text-amber-300 text-xs font-semibold transition text-center hover:scale-102">
                            👑 Admin
                        </button>
                    </div>
                </div>

                <!-- Formulario -->
                <form method="POST" action="{{ route('login.post') }}" class="space-y-5" id="loginForm">
                    @csrf

                    <!-- Campo Correo -->
                    <div>
                        <label for="correo" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                            Correo electrónico
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <input 
                                type="email" 
                                name="correo" 
                                id="correo"
                                value="{{ old('correo') }}"
                                required 
                                autofocus
                                placeholder="tu@correo.com"
                                class="w-full pl-10 pr-4 py-3 bg-slate-950/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all shadow-sm"
                            >
                        </div>
                    </div>

                    <!-- Campo Contraseña -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="passwordInput" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">
                                Contraseña
                            </label>
                            <a href="{{ route('password.forgot') }}" class="text-xs font-semibold text-orange-400 hover:text-orange-300 transition-colors">
                                ¿La olvidaste?
                            </a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input 
                                type="password" 
                                name="password" 
                                id="passwordInput"
                                required
                                placeholder="••••••••"
                                class="w-full pl-10 pr-11 py-3 bg-slate-950/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all shadow-sm"
                            >
                            <button type="button" onclick="togglePassword()" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-white transition-colors"
                                    aria-label="Ver u ocultar contraseña">
                                <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Recordar Sesión -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember" value="1" 
                                   class="w-4 h-4 rounded bg-slate-950 border-slate-700 text-orange-500 focus:ring-orange-500/20 focus:ring-offset-0">
                            <span class="text-xs font-medium text-slate-400">Mantener sesión iniciada</span>
                        </label>
                    </div>

                    <!-- Botón de Ingreso -->
                    <button 
                        type="submit" 
                        class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-bold text-sm shadow-lg shadow-orange-500/25 hover:shadow-orange-500/35 transition-all duration-200 transform active:scale-98 flex items-center justify-center gap-2"
                    >
                        <span>Ingresar al Sistema</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </form>

                <!-- Divisor -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-800"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="px-4 bg-slate-900 text-slate-500 font-semibold uppercase tracking-wider">¿Nuevo por aquí?</span>
                    </div>
                </div>

                <!-- Enlace a Registro -->
                <div class="text-center space-y-3">
                    <a href="{{ route('register') }}" 
                       class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-slate-800/80 hover:bg-slate-800 text-slate-200 hover:text-white font-semibold text-sm border border-slate-700/80 hover:border-slate-600 transition-all">
                        <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        <span>Crear cuenta de Cliente</span>
                    </a>

                    <div class="pt-2">
                        <a href="{{ route('inicio') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-300 transition-colors inline-flex items-center gap-1">
                            ← Volver al sitio principal
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Scripts de interactividad -->
    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                `;
            } else {
                input.type = 'password';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                `;
            }
        }

        function fillCreds(email, pass) {
            document.getElementById('correo').value = email;
            document.getElementById('passwordInput').value = pass;
        }
    </script>
</body>
</html>
