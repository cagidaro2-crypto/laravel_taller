<!DOCTYPE html>
<html lang="es" style="height:100%; background:#f4f6f9;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Panel') — @yield('role') | Taller Latonería</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body style="height:100%; display:flex; flex-direction:column; font-family:'Plus Jakarta Sans','Inter',sans-serif; -webkit-font-smoothing:antialiased;">

{{-- Overlay mobile --}}
<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

<div style="display:flex; height:100%;">

    {{-- ── SIDEBAR ── --}}
    <aside id="sidebar" class="sidebar @yield('sidebar-class')">

        {{-- Logo --}}
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3-3a1 1 0 000-1.4l-1.6-1.6a1 1 0 00-1.4 0z"/>
                    <path d="M5 20L4 16l4-4 7 1-3.5 3.5z"/>
                    <path d="M9 12l-4 4"/>
                </svg>
            </div>
            <div style="min-width:0;">
                <p class="sidebar-logo-title">Taller Latonería</p>
                <p class="sidebar-logo-subtitle">@yield('role')</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="sidebar-nav">
            @yield('nav-items')
        </nav>
    </aside>

    {{-- ── MAIN CONTENT ── --}}
    <div class="content-area">

        {{-- Topbar --}}
        <header class="topbar">
            <div class="topbar-left">
                <button class="topbar-menu-btn" onclick="toggleSidebar()" aria-label="Menú">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h1 class="topbar-title">@yield('title','Panel')</h1>
                </div>
            </div>
            <div class="topbar-right" style="display:flex; align-items:center; gap:12px;">
                <span class="role-badge @yield('badge-class')">@yield('role')</span>
                
                {{-- User Menu Dropdown --}}
                <div style="position:relative;">
                    <button onclick="toggleUserMenu()" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-100 transition-colors" style="border:1px solid #e2e8f0;">
                        <div style="width:32px; height:32px; background:linear-gradient(135deg, #f97316, #ea580c); box-shadow:0 2px 8px rgba(234,88,12,0.3); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:12px;">
                            {{ strtoupper(substr(auth()->user()->nombre, 0, 2)) }}
                        </div>
                        <svg id="userMenuIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="width:16px; height:16px; transition:transform 0.3s;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                    </button>
                    
                    {{-- Dropdown Menu --}}
                    <div id="userMenuDropdown" style="display:none; position:absolute; right:0; top:calc(100% + 8px); background:white; border:1px solid #e2e8f0; border-radius:12px; box-shadow:0 10px 25px rgba(0,0,0,0.1); z-index:1000; min-width:240px; overflow:hidden;">
                        {{-- User Info --}}
                        <div style="padding:16px; border-bottom:1px solid #e2e8f0; background:#f9fafb;">
                            <p style="font-weight:600; color:#111827; margin:0; font-size:14px;">{{ auth()->user()->nombre }}</p>
                            <p style="color:#6b7280; margin:4px 0 0; font-size:12px;">{{ auth()->user()->correo }}</p>
                        </div>
                        
                        {{-- Options --}}
                        <a href="{{ route('password.change') }}" style="display:flex; align-items:center; gap:12px; padding:12px 16px; color:#374151; text-decoration:none; border-bottom:1px solid #e2e8f0; transition:background-color 0.2s; font-size:14px;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='transparent'">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="width:16px; height:16px; flex-shrink:0;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                            Cambiar contraseña
                        </a>
                        
                        {{-- Logout --}}
                        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                            @csrf
                            <button type="submit" onclick="return confirm('¿Deseas cerrar sesión?')" style="width:100%; display:flex; align-items:center; gap:12px; padding:12px 16px; color:#ef4444; text-decoration:none; background:none; border:none; cursor:pointer; transition:background-color 0.2s; font-size:14px; font-family:inherit;" onmouseover="this.style.backgroundColor='#fee2e2'" onmouseout="this.style.backgroundColor='transparent'">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="width:16px; height:16px; flex-shrink:0;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Alerts --}}
        <div style="padding: 16px 28px 0;">
            @if(session('success'))
                <div class="alert alert-success">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('error') }}
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('info') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-error" style="flex-direction:column; align-items:flex-start;">
                    <ul style="list-style:disc; padding-left:18px; margin:0; display:flex; flex-direction:column; gap:3px;">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif
        </div>

        <main class="page-content">
            @yield('content')
        </main>
    </div>
</div>

<script>
function toggleSidebar() {
    const s = document.getElementById('sidebar');
    const o = document.getElementById('overlay');
    s.classList.toggle('translate-x-0');
    s.classList.toggle('-translate-x-full');
    o.classList.toggle('active');
}
function closeSidebar() {
    const s = document.getElementById('sidebar');
    const o = document.getElementById('overlay');
    s.classList.remove('translate-x-0');
    s.classList.add('-translate-x-full');
    o.classList.remove('active');
}
function toggleUserMenu() {
    const dropdown = document.getElementById('userMenuDropdown');
    const icon = document.getElementById('userMenuIcon');
    const isOpen = dropdown.style.display === 'block';
    dropdown.style.display = isOpen ? 'none' : 'block';
    icon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
}
// Cerrar menú cuando se hace click fuera
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('userMenuDropdown');
    const button = event.target.closest('button[onclick="toggleUserMenu()"]');
    if (!button && !event.target.closest('#userMenuDropdown')) {
        dropdown.style.display = 'none';
        document.getElementById('userMenuIcon').style.transform = 'rotate(0deg)';
    }
});
</script>
@stack('scripts')
</body>
</html>
