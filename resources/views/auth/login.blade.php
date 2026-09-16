<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — Taller Latonería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            display: flex;
            font-family: 'Segoe UI', sans-serif;
            background: #0f172a;
        }

        /* ── Panel izquierdo (imagen/branding) ── */
        .left-panel {
            flex: 1;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 3rem;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(to bottom, rgba(15,23,42,.3) 0%, rgba(15,23,42,.85) 100%),
                url('https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?w=1200&q=80') center/cover no-repeat;
            z-index: 0;
        }

        .left-panel .content {
            position: relative;
            z-index: 1;
            color: #fff;
        }

        .left-panel .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: rgba(255,255,255,.12);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 2rem;
            padding: .4rem 1rem;
            font-size: .8rem;
            font-weight: 600;
            letter-spacing: .05em;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
            color: #f1f5f9;
        }

        .left-panel h1 {
            font-size: clamp(1.8rem, 3vw, 2.6rem);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: .75rem;
        }

        .left-panel p {
            color: #cbd5e1;
            font-size: .95rem;
            max-width: 380px;
            line-height: 1.6;
        }

        .stats-row {
            display: flex;
            gap: 2rem;
            margin-top: 2rem;
        }

        .stat-item {
            text-align: left;
        }

        .stat-item .num {
            font-size: 1.6rem;
            font-weight: 800;
            color: #f97316;
        }

        .stat-item .label {
            font-size: .75rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        /* ── Panel derecho (formulario) ── */
        .right-panel {
            width: 440px;
            min-width: 440px;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 3rem;
        }

        .right-panel .logo-area {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 2.5rem;
        }

        .logo-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #f97316, #ea580c);
            border-radius: .75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.3rem;
        }

        .right-panel h2 {
            font-size: 1.8rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: .5rem;
        }

        .right-panel .subtitle {
            color: #64748b;
            font-size: .95rem;
            margin-bottom: 2.5rem;
            font-weight: 500;
        }

        .form-label {
            font-size: .8rem;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: .4rem;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .input-group-custom .icon {
            position: absolute;
            left: .9rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
            z-index: 2;
        }

        .input-group-custom input {
            padding-left: 2.5rem;
            padding-right: 2.5rem;
            height: 48px;
            border: 1.5px solid #e2e8f0;
            border-radius: .6rem;
            font-size: .9rem;
            transition: border-color .2s, box-shadow .2s;
            width: 100%;
        }

        .input-group-custom input:focus {
            outline: none;
            border-color: #f97316;
            box-shadow: 0 0 0 3px rgba(249,115,22,.15);
        }

        .input-group-custom .toggle-pass {
            position: absolute;
            right: .9rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            font-size: 1rem;
            background: none;
            border: none;
            padding: 0;
            z-index: 2;
        }

        .btn-login {
            width: 100%;
            height: 54px;
            background: linear-gradient(135deg, #f97316, #ea580c);
            border: none;
            border-radius: .75rem;
            color: #fff;
            font-weight: 800;
            font-size: 1.05rem;
            letter-spacing: .05em;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1.5rem;
            box-shadow: 0 8px 16px rgba(249, 115, 22, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
        }

        .btn-login:hover { 
            opacity: 0.95;
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(249, 115, 22, 0.4);
        }
        
        .btn-login:active { 
            transform: translateY(0px);
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: .75rem;
            color: #cbd5e1;
            font-size: .8rem;
            margin: 1.5rem 0;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .footer-link {
            text-align: center;
            font-size: .85rem;
            color: #64748b;
        }

        .footer-link a {
            color: #f97316;
            font-weight: 600;
            text-decoration: none;
        }

        .footer-link a:hover { text-decoration: underline; }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: .6rem;
            color: #b91c1c;
            font-size: .85rem;
            padding: .75rem 1rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: .5rem;
        }

        .alert-success-msg {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: .6rem;
            color: #166534;
            font-size: .85rem;
            padding: .75rem 1rem;
            margin-bottom: 1.25rem;
        }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }

        .remember-row label {
            font-size: .85rem;
            color: #475569;
            display: flex;
            align-items: center;
            gap: .4rem;
            cursor: pointer;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; min-width: unset; padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>

    {{-- Panel izquierdo --}}
    <div class="left-panel">
        <div class="content">
            <div class="brand-badge">
                <i class="bi bi-tools"></i> Taller Profesional
            </div>
            <h1>Latonería y Pintura<br>de Excelencia</h1>
            <p>Gestionamos cada vehículo con precisión, trazabilidad total y atención al cliente de primer nivel.</p>
            <div class="stats-row">
                <div class="stat-item">
                    <div class="num">+500</div>
                    <div class="label">Vehículos atendidos</div>
                </div>
                <div class="stat-item">
                    <div class="num">98%</div>
                    <div class="label">Clientes satisfechos</div>
                </div>
                <div class="stat-item">
                    <div class="num">24h</div>
                    <div class="label">Respuesta promedio</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Panel derecho --}}
    <div class="right-panel">

        <div class="logo-area">
            <div class="logo-icon"><i class="bi bi-car-front-fill"></i></div>
            <div>
                <div style="font-weight:800;font-size:1rem;color:#0f172a">Taller Latonería</div>
                <div style="font-size:.75rem;color:#94a3b8">Sistema de gestión</div>
            </div>
        </div>

        <h2>Bienvenido de vuelta</h2>
        <p class="subtitle">Ingresa tus credenciales para acceder al sistema</p>

        {{-- Mensajes --}}
        @if(session('success'))
            <div class="alert-success-msg">
                <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                <i class="bi bi-exclamation-circle mt-1"></i>
                <div>
                    @foreach($errors->all() as $e)
                        <div>{{ $e }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" id="loginForm" enctype="application/x-www-form-urlencoded">
            @csrf

            {{-- Correo --}}
            <label class="form-label">Correo electrónico</label>
            <div class="input-group-custom">
                <i class="bi bi-envelope icon"></i>
                <input
                    type="email"
                    name="correo"
                    value="{{ old('correo') }}"
                    placeholder="tu@correo.com"
                    required
                    autofocus
                    class="{{ $errors->has('correo') ? 'border-red-500' : '' }}"
                >
            </div>

            {{-- Contraseña --}}
            <label class="form-label">Contraseña</label>
            <div class="input-group-custom">
                <i class="bi bi-lock icon"></i>
                <input
                    type="password"
                    name="password"
                    id="passwordInput"
                    placeholder="••••••••"
                    required
                >
                <button type="button" class="toggle-pass" onclick="togglePassword()">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
            </div>

            {{-- Recordar --}}
            <div class="remember-row">
                <label>
                    <input type="checkbox" name="remember" style="accent-color:#f97316" value="1">
                    Recordarme
                </label>
            </div>

            <button type="submit" class="btn-login" id="btnLogin">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 1h-8A1.5 1.5 0 0 0 0 2.5v9A1.5 1.5 0 0 0 1.5 13h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                    <path fill-rule="evenodd" d="M15.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L12.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                </svg>
                Ingresar al sistema
            </button>
        </form>

        <div class="divider">o</div>

        <p class="footer-link">
            ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate como cliente</a>
        </p>

        <p class="footer-link text-xs mt-3">
            ¿Olvidaste tu contraseña? <a href="{{ route('password.forgot') }}">Recupérala aquí</a>
        </p>

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

// Verificar que el formulario esté funcionando correctamente
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Loaded');
    
    const form = document.getElementById('loginForm');
    console.log('Formulario encontrado:', !!form);
    
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Submit event triggered');
            console.log('Form action:', form.action);
            console.log('Form method:', form.method);
        });
    }
});
</script>
</body>
</html>
