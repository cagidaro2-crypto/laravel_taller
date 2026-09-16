<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña — Taller Latonería</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-slate-50">

<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
            <div class="text-center mb-6">
                <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-black text-slate-800">Restablecer contraseña</h2>
                <p class="text-slate-500 text-sm mt-1">Ingresa tu nueva contraseña</p>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-4 text-sm">
                    <ul class="space-y-1">
                        @foreach($errors->all() as $e)<li class="flex items-start gap-2"><span class="shrink-0 mt-0.5">•</span><span>{{ $e }}</span></li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.reset') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="id" value="{{ $usuario->id_usuario }}">
                <input type="hidden" name="token" value="{{ $request->token }}">

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Nueva contraseña</label>
                    <input type="password" name="password" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 @error('password') border-red-400 @enderror"
                           placeholder="Mín 8 caracteres, mayúscula, número y símbolo">
                    <p class="text-xs text-slate-400 mt-1">Debe tener: mayúscula, número, símbolo (@$!%*?&#)</p>
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400"
                           placeholder="Repite la contraseña">
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-xl transition-colors">
                    Restablecer contraseña
                </button>
            </form>

            <div class="text-center text-sm mt-4">
                <a href="{{ route('login') }}" class="text-slate-600 hover:text-slate-800 transition-colors">
                    ← Volver al login
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
