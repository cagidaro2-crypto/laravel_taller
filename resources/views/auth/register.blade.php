<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro — Taller Latonería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #1e2a3a; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { border: none; border-radius: 1rem; box-shadow: 0 8px 32px rgba(0,0,0,.25); }
    </style>
</head>
<body>
<div class="container" style="max-width:480px">
    <div class="card p-4">
        <div class="text-center mb-4">
            <h4 class="fw-bold">🔧 Taller Latonería</h4>
            <p class="text-muted small">Crea tu cuenta de cliente</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold">Nombre completo <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Identificación <span class="text-danger">*</span></label>
                    <input type="text" name="documento" class="form-control @error('documento') is-invalid @enderror"
                           value="{{ old('documento') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Correo electrónico <span class="text-danger">*</span></label>
                    <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror"
                           value="{{ old('correo') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Dirección</label>
                    <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Contraseña <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    <div class="form-text">Mínimo 8 caracteres, una mayúscula, un número y un carácter especial.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Confirmar contraseña <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                </div>
            </div>
        </form>

        <hr>
        <p class="text-center small mb-0">
            ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
        </p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
