<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Taller Latonería') — Taller</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .sidebar { min-height: 100vh; background: #1e2a3a; color: #fff; width: 240px; position: fixed; top: 0; left: 0; padding-top: 1rem; z-index: 100; }
        .sidebar a { color: #adb5bd; text-decoration: none; display: block; padding: .5rem 1.25rem; border-radius: .375rem; margin: 2px 8px; }
        .sidebar a:hover, .sidebar a.active { background: #0d6efd; color: #fff; }
        .sidebar .brand { font-size: 1.1rem; font-weight: 700; padding: .75rem 1.25rem 1.25rem; color: #fff; border-bottom: 1px solid #2d3e50; margin-bottom: .5rem; }
        .main-content { margin-left: 240px; padding: 2rem; }
        .topbar { background: #fff; border-bottom: 1px solid #dee2e6; padding: .75rem 2rem; margin-left: 240px; position: sticky; top: 0; z-index: 99; display: flex; justify-content: space-between; align-items: center; }
    </style>
    @stack('styles')
</head>
<body>

{{-- Sidebar --}}
<div class="sidebar">
    <div class="brand"><i class="bi bi-tools me-2"></i>Taller Latonería</div>
    @include('layouts.partials.sidebar')
</div>

{{-- Topbar --}}
<div class="topbar">
    <span class="fw-semibold text-muted">@yield('title', 'Panel')</span>
    <div class="d-flex align-items-center gap-3">
        <span class="text-muted small">{{ auth()->user()->nombre }}</span>
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Desea cerrar sesión?')">
                <i class="bi bi-box-arrow-right"></i> Cerrar sesión
            </button>
        </form>
    </div>
</div>

{{-- Contenido --}}
<div class="main-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
