<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Taller</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Iniciar Sesión</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="correo">
                    Correo Electrónico
                </label>
                <input 
                    type="email" 
                    name="correo" 
                    value="{{ old('correo') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                    required
                >
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2" for="password">
                    Contraseña
                </label>
                <input 
                    type="password" 
                    name="password" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                    required
                >
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    <span class="text-gray-700">Recordarme</span>
                </label>
            </div>

            <button 
                type="submit" 
                class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600"
            >
                Ingresar
            </button>
        </form>

        <div class="mt-4 text-center">
            <p class="text-gray-700 text-sm">
                ¿No tienes cuenta? 
                <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Regístrate</a>
            </p>
        </div>

        <div class="mt-6 bg-blue-50 p-4 rounded">
            <p class="text-xs text-gray-600"><strong>Credenciales de prueba:</strong></p>
            <p class="text-xs text-gray-600">Email: admin@test.com</p>
            <p class="text-xs text-gray-600">Password: password123</p>
        </div>
    </div>
</div>

</body>
</html>
