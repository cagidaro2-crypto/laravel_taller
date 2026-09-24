<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taller Latonería y Pintura — Inicio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased text-slate-800 bg-white">

{{-- ══════════════════════════════════════
     NAVBAR
════════════════════════════════════════ --}}
<header class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
    <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
        {{-- Logo --}}
        <a href="{{ route('inicio') }}" class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-orange-500 flex items-center justify-center">
                <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span class="font-extrabold text-slate-900 text-sm tracking-tight">Taller Latonería</span>
        </a>

        {{-- Nav links --}}
        <nav class="hidden md:flex items-center gap-7 text-sm font-medium text-slate-600">
            <a href="#servicios" class="hover:text-orange-500 transition-colors">Servicios</a>
            <a href="#como-funciona" class="hover:text-orange-500 transition-colors">¿Cómo funciona?</a>
            <a href="#nosotros" class="hover:text-orange-500 transition-colors">Nosotros</a>
            <a href="#contacto" class="hover:text-orange-500 transition-colors">Contacto</a>
        </nav>

        {{-- CTA --}}
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ match(Auth::user()->rol?->nombre_rol) { 'Administrador' => route('admin.dashboard'), 'Técnico', 'Empleado' => route('tecnico.dashboard'), default => route('cliente.dashboard') } }}" 
                   class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors">
                    Ir a mi Panel ({{ Auth::user()->nombre }})
                </a>
                <a href="{{ route('logout') }}" class="text-sm font-semibold text-rose-500 hover:text-rose-600 transition-colors">
                    Cerrar sesión
                </a>
            @else
                <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-orange-500 transition-colors hidden sm:block">
                    Iniciar sesión
                </a>
                <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors">
                    Registrarse
                </a>
            @endauth
        </div>
    </div>
</header>


{{-- ══════════════════════════════════════
     HERO
════════════════════════════════════════ --}}
<section class="relative min-h-screen flex items-center overflow-hidden pt-16">
    {{-- Fondo con imagen --}}
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?w=1600&q=80"
             alt="Taller" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-900/80 to-transparent"></div>
    </div>

    <div class="relative z-10 max-w-6xl mx-auto px-6 py-24">
        <div class="max-w-xl">
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 bg-orange-500/20 border border-orange-500/30 rounded-full px-4 py-1.5 mb-6">
                <div class="w-2 h-2 rounded-full bg-orange-400 animate-pulse"></div>
                <span class="text-orange-300 text-xs font-semibold uppercase tracking-wider">Taller profesional</span>
            </div>

            <h1 class="text-white text-5xl sm:text-6xl font-black leading-[1.1] mb-6">
                Tu vehículo en<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">
                    las mejores manos
                </span>
            </h1>

            <p class="text-slate-300 text-lg leading-relaxed mb-10 max-w-md">
                Latonería, pintura y reparación con tecnología de punta. Agenda tu cita, sigue el proceso en tiempo real y recibe tu vehículo como nuevo.
            </p>

            <div class="flex flex-wrap gap-4">
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-bold px-7 py-3.5 rounded-2xl text-sm transition-all hover:scale-105 shadow-lg shadow-orange-500/30">
                    Agenda tu cita
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
                <a href="#servicios"
                   class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold px-7 py-3.5 rounded-2xl text-sm border border-white/20 transition-colors backdrop-blur-sm">
                    Ver servicios
                </a>
            </div>

            {{-- Stats --}}
            <div class="flex gap-8 mt-14 pt-10 border-t border-white/10">
                @foreach([['500+','Vehículos atendidos'],['98%','Clientes satisfechos'],['10+','Años de experiencia']] as [$num,$lab])
                <div>
                    <p class="text-2xl font-black text-white">{{ $num }}</p>
                    <p class="text-slate-400 text-xs mt-0.5">{{ $lab }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 text-white/40 animate-bounce">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>
</section>


{{-- ══════════════════════════════════════
     SERVICIOS
════════════════════════════════════════ --}}
<section id="servicios" class="py-24 bg-slate-50">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-14">
            <span class="text-orange-500 text-xs font-bold uppercase tracking-widest">Lo que hacemos</span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mt-3">Nuestros servicios</h2>
            <p class="text-slate-500 mt-3 max-w-xl mx-auto">Cubrimos todo lo que tu vehículo necesita, desde reparaciones menores hasta restauraciones completas.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $servicios = [
                    ['icon'=>'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4',
                      'titulo'=>'Latonería',
                      'desc'=>'Reparación de golpes, abolladuras y deformaciones en la carrocería con técnicas modernas.',
                      'color'=>'orange'],
                    ['icon'=>'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01',
                      'titulo'=>'Pintura',
                      'desc'=>'Pintura automotriz de alta calidad con cabinas especializadas y acabados perfectos.',
                      'color'=>'blue'],
                    ['icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                      'titulo'=>'Diagnóstico',
                      'desc'=>'Evaluación completa del estado de tu vehículo con herramientas de diagnóstico profesional.',
                      'color'=>'emerald'],
                    ['icon'=>'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
                      'titulo'=>'Restauración',
                      'desc'=>'Restauración integral de vehículos clásicos y modernos con atención al detalle.',
                      'color'=>'purple'],
                    ['icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                      'titulo'=>'Garantía',
                      'desc'=>'Todos nuestros trabajos cuentan con garantía por escrito. Tu tranquilidad es nuestra prioridad.',
                      'color'=>'rose'],
                    ['icon'=>'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
                      'titulo'=>'Notificaciones',
                      'desc'=>'Te mantenemos informado en cada etapa del proceso. Recibe alertas del estado de tu vehículo.',
                      'color'=>'amber'],
                ];
                $colorMap = [
                    'orange'=>['bg'=>'bg-orange-100','text'=>'text-orange-600','border'=>'group-hover:border-orange-300'],
                    'blue'  =>['bg'=>'bg-blue-100',  'text'=>'text-blue-600',  'border'=>'group-hover:border-blue-300'],
                    'emerald'=>['bg'=>'bg-emerald-100','text'=>'text-emerald-600','border'=>'group-hover:border-emerald-300'],
                    'purple'=>['bg'=>'bg-purple-100','text'=>'text-purple-600','border'=>'group-hover:border-purple-300'],
                    'rose'  =>['bg'=>'bg-rose-100',  'text'=>'text-rose-600',  'border'=>'group-hover:border-rose-300'],
                    'amber' =>['bg'=>'bg-amber-100', 'text'=>'text-amber-600', 'border'=>'group-hover:border-amber-300'],
                ];
            @endphp

            @foreach($servicios as $s)
            @php $c = $colorMap[$s['color']]; @endphp
            <div class="group bg-white rounded-2xl p-6 border border-slate-100 hover:shadow-lg transition-all duration-300 {{ $c['border'] }} cursor-default">
                <div class="w-11 h-11 {{ $c['bg'] }} rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 {{ $c['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $s['icon'] }}"/>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-800 mb-2">{{ $s['titulo'] }}</h3>
                <p class="text-slate-500 text-sm leading-relaxed">{{ $s['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════
     CÓMO FUNCIONA
════════════════════════════════════════ --}}
<section id="como-funciona" class="py-24 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-14">
            <span class="text-orange-500 text-xs font-bold uppercase tracking-widest">Proceso simple</span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mt-3">¿Cómo funciona?</h2>
            <p class="text-slate-500 mt-3 max-w-lg mx-auto">En 4 pasos simples llevamos tu vehículo de regreso a su mejor estado.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                $pasos = [
                    ['n'=>'01','titulo'=>'Regístrate','desc'=>'Crea tu cuenta en minutos e ingresa la información de tu vehículo.','icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ['n'=>'02','titulo'=>'Agenda tu cita','desc'=>'Elige el día y hora disponible que mejor se adapte a tu horario.','icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['n'=>'03','titulo'=>'Aprueba cotización','desc'=>'Recibe y aprueba la cotización detallada antes de comenzar el trabajo.','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['n'=>'04','titulo'=>'Recoge tu vehículo','desc'=>'Seguimos el proceso en tiempo real y te notificamos cuando esté listo.','icon'=>'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h1m6-9h5l3 3v4h-1'],
                ];
            @endphp

            @foreach($pasos as $i => $p)
            <div class="relative text-center">
                {{-- Línea conectora --}}
                @if($i < 3)
                <div class="hidden lg:block absolute top-6 left-1/2 w-full h-px bg-gradient-to-r from-orange-300 to-slate-200 z-0"></div>
                @endif
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-orange-200">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $p['icon'] }}"/>
                        </svg>
                    </div>
                    <span class="text-xs font-black text-orange-400 tracking-widest">{{ $p['n'] }}</span>
                    <h3 class="font-bold text-slate-800 mt-1 mb-2">{{ $p['titulo'] }}</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">{{ $p['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════
     NOSOTROS / FOTO
════════════════════════════════════════ --}}
<section id="nosotros" class="py-24 bg-slate-900 overflow-hidden">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            {{-- Texto --}}
            <div>
                <span class="text-orange-400 text-xs font-bold uppercase tracking-widest">Sobre nosotros</span>
                <h2 class="text-3xl sm:text-4xl font-black text-white mt-3 mb-5">
                    Más de 10 años transformando vehículos
                </h2>
                <p class="text-slate-400 leading-relaxed mb-6">
                    Somos un taller especializado en latonería y pintura automotriz con más de una década de experiencia. Nuestro equipo de técnicos certificados trabaja con las últimas tecnologías para garantizar resultados excepcionales.
                </p>
                <p class="text-slate-400 leading-relaxed mb-8">
                    Contamos con cabinas de pintura de última generación, equipos de diagnóstico avanzados y un sistema de gestión digital que te permite seguir el estado de tu vehículo en tiempo real.
                </p>
                <div class="grid grid-cols-3 gap-6">
                    @foreach([['10+','Años'],['500+','Vehículos'],['98%','Satisfacción']] as [$n,$l])
                    <div class="text-center border border-slate-700 rounded-2xl p-4">
                        <p class="text-2xl font-black text-orange-400">{{ $n }}</p>
                        <p class="text-slate-500 text-xs mt-1">{{ $l }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Imagen --}}
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&q=80"
                     alt="Taller" class="w-full h-80 object-cover rounded-3xl">
                {{-- Floating card --}}
                <div class="absolute -bottom-5 -left-5 bg-orange-500 text-white rounded-2xl px-5 py-4 shadow-xl">
                    <p class="text-2xl font-black">98%</p>
                    <p class="text-orange-100 text-xs">Clientes satisfechos</p>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════
     TESTIMONIOS
════════════════════════════════════════ --}}
<section class="py-24 bg-slate-50">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-14">
            <span class="text-orange-500 text-xs font-bold uppercase tracking-widest">Testimonios</span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mt-3">Lo que dicen nuestros clientes</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @php
                $testimonios = [
                    ['nombre'=>'Carlos Rodríguez','cargo'=>'Cliente desde 2021','texto'=>'Excelente servicio. Llevé mi carro con un golpe fuerte y quedó como nuevo. El sistema de notificaciones me mantuvo informado en todo momento.','avatar'=>'CR'],
                    ['nombre'=>'María Fernanda López','cargo'=>'Cliente desde 2022','texto'=>'La plataforma es muy fácil de usar. Agendé mi cita en minutos y el proceso fue súper transparente. Totalmente recomendado.','avatar'=>'ML'],
                    ['nombre'=>'Andrés Torres','cargo'=>'Cliente desde 2020','texto'=>'Llevan 4 años cuidando mis vehículos. La calidad del trabajo es impecable y los precios son muy justos.','avatar'=>'AT'],
                ];
            @endphp

            @foreach($testimonios as $t)
            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                {{-- Stars --}}
                <div class="flex gap-1 mb-4">
                    @for($i=0;$i<5;$i++)
                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>
                <p class="text-slate-600 text-sm leading-relaxed mb-5">"{{ $t['texto'] }}"</p>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-orange-100 text-orange-600 text-xs font-bold flex items-center justify-center">{{ $t['avatar'] }}</div>
                    <div>
                        <p class="font-semibold text-slate-800 text-sm">{{ $t['nombre'] }}</p>
                        <p class="text-slate-400 text-xs">{{ $t['cargo'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════
     CTA FINAL
════════════════════════════════════════ --}}
<section class="py-24 bg-gradient-to-br from-orange-500 to-orange-700">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <h2 class="text-3xl sm:text-5xl font-black text-white mb-5">
            ¿Listo para darle vida a tu vehículo?
        </h2>
        <p class="text-orange-100 text-lg mb-10">
            Regístrate gratis, agenda tu cita y recibe la mejor atención en latonería y pintura.
        </p>
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="{{ route('register') }}"
               class="bg-white text-orange-600 font-bold px-8 py-3.5 rounded-2xl text-sm hover:bg-orange-50 transition-colors shadow-lg">
                Crear cuenta gratis
            </a>
            <a href="{{ route('login') }}"
               class="bg-white/10 text-white font-semibold px-8 py-3.5 rounded-2xl text-sm border border-white/30 hover:bg-white/20 transition-colors">
                Ya tengo cuenta
            </a>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════
     CONTACTO + FOOTER
════════════════════════════════════════ --}}
<section id="contacto" class="py-24 bg-slate-900">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-16">
            {{-- Info --}}
            <div>
                <span class="text-orange-400 text-xs font-bold uppercase tracking-widest">Contacto</span>
                <h2 class="text-3xl font-black text-white mt-3 mb-6">Estamos para ayudarte</h2>

                <div class="space-y-5">
                    @php
                        $contactos = [
                            ['icon'=>'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z','label'=>'Dirección','valor'=>'Cra. 5 #12-34, Popayán, Cauca'],
                            ['icon'=>'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z','label'=>'Teléfono','valor'=>'+57 (2) 836-1234'],
                            ['icon'=>'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z','label'=>'Correo','valor'=>'info@tallerlatoneria.com'],
                            ['icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z','label'=>'Horario','valor'=>'Lun–Vie 7:00am–6:00pm · Sáb 8:00am–2:00pm'],
                        ];
                    @endphp
                    @foreach($contactos as $c)
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-orange-500/20 flex items-center justify-center shrink-0">
                            <svg class="w-4.5 h-4.5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $c['icon'] }}"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs font-medium uppercase tracking-wide">{{ $c['label'] }}</p>
                            <p class="text-white text-sm mt-0.5">{{ $c['valor'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Acceso rápido --}}
            <div class="bg-slate-800 rounded-3xl p-8 border border-slate-700">
                <h3 class="text-white font-bold text-lg mb-2">Accede al sistema</h3>
                <p class="text-slate-400 text-sm mb-6">Ingresa o créate una cuenta para gestionar tus vehículos y citas.</p>
                <div class="space-y-3">
                    <a href="{{ route('login') }}"
                       class="flex items-center justify-between w-full bg-slate-700 hover:bg-slate-600 text-white px-5 py-3.5 rounded-xl text-sm font-medium transition-colors">
                        <span>Iniciar sesión</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('register') }}"
                       class="flex items-center justify-between w-full bg-orange-500 hover:bg-orange-600 text-white px-5 py-3.5 rounded-xl text-sm font-bold transition-colors">
                        <span>Crear cuenta gratis</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Footer bottom --}}
        <div class="mt-16 pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-lg bg-orange-500 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="text-slate-500 text-xs">© {{ date('Y') }} Taller Latonería. Todos los derechos reservados.</span>
            </div>
            <div class="flex gap-6 text-slate-600 text-xs">
                <a href="#servicios" class="hover:text-orange-400 transition-colors">Servicios</a>
                <a href="#nosotros" class="hover:text-orange-400 transition-colors">Nosotros</a>
                <a href="{{ route('login') }}" class="hover:text-orange-400 transition-colors">Ingresar</a>
            </div>
        </div>
    </div>
</section>

</body>
</html>
