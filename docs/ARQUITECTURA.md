# 🏗️ Arquitectura del Sistema - Taller Latonería

## 📊 Estructura General

```
proyecto/
├── app/                          # Lógica de la aplicación
│   ├── Console/Commands/         # Comandos Artisan
│   ├── Http/
│   │   ├── Controllers/          # Controladores por rol
│   │   │   ├── Admin/            # Panel administrativo
│   │   │   ├── Tecnico/          # Panel técnico
│   │   │   ├── Cliente/          # Panel cliente
│   │   │   └── Auth/             # Autenticación
│   │   ├── Middleware/           # Middlewares (auth, role, etc)
│   │   └── Kernel.php            # Configuración HTTP
│   ├── Models/                   # Modelos de datos
│   │   ├── Admin/                # Modelos admin
│   │   ├── Tecnico/              # Modelos técnico
│   │   └── Cliente/              # Modelos cliente
│   ├── Mail/                     # Clases de Email
│   └── Exceptions/               # Excepciones personalizadas
├── resources/
│   ├── css/
│   │   └── app.css              # Tailwind CSS
│   ├── js/
│   │   └── app.js               # JavaScript principal
│   └── views/                   # Vistas Blade
│       ├── layouts/             # Layouts base
│       │   ├── app.blade.php
│       │   ├── dashboard.blade.php
│       │   ├── admin.blade.php
│       │   ├── tecnico.blade.php
│       │   └── cliente.blade.php
│       ├── admin/               # Vistas admin
│       ├── tecnico/             # Vistas técnico
│       ├── cliente/             # Vistas cliente
│       ├── auth/                # Vistas auth
│       └── emails/              # Templates de email
├── routes/                      # Definición de rutas
│   ├── api.php                  # Rutas API (no usado)
│   ├── web.php                  # Rutas web principales
│   ├── admin.php                # Rutas admin
│   ├── tecnico.php              # Rutas técnico
│   └── cliente.php              # Rutas cliente
├── database/
│   ├── migrations/              # Migraciones BD
│   ├── seeders/                 # Seeders (datos de prueba)
│   └── factories/               # Factories
├── storage/                     # Archivos generados
│   ├── app/public/vehiculos/   # Fotos de vehículos
│   └── logs/                    # Logs de la app
├── public/
│   ├── build/                   # Assets compilados por Vite
│   └── index.php                # Punto de entrada
├── config/                      # Configuraciones
│   ├── app.php
│   ├── database.php
│   ├── auth.php
│   └── mail.php
├── .env                         # Variables de entorno
├── .env.example                 # Ejemplo de variables
├── package.json                 # Dependencias npm
├── composer.json                # Dependencias PHP
├── tailwind.config.js           # Config Tailwind
└── docs/                        # 📍 Esta documentación
```

---

## 🔄 Flujo de Petición (Request/Response)

```
Usuario hace click
    ↓
Navegador envía petición HTTP
    ↓
Laravel recibe en routes/[rol].php
    ↓
Middleware valida:
    - ¿Está autenticado? (Authenticate.php)
    - ¿Tiene el rol correcto? (CheckRole.php)
    ↓
Controller procesa la lógica
    ↓
Controller interactúa con Models
    ↓
Models consultan/modifican BD
    ↓
Controller renderiza vista (Blade)
    ↓
Tailwind CSS estiliza la vista
    ↓
JavaScript añade interactividad
    ↓
Navegador renderiza HTML
    ↓
Usuario ve resultado
```

---

## 👥 Modelo de Roles y Permisos

```
Usuario
├── id_usuario (PK)
├── nombre
├── correo
├── password (bcrypt)
├── activo (boolean)
└── rol (relación)
    ↓
Rol
├── id_rol (PK)
├── nombre_rol (Administrador, Técnico, Cliente)
└── permisos (implícitos en rutas)
```

### Rutas Protegidas por Rol

```
/admin/*          → middleware: ['auth', 'role:Administrador']
/tecnico/*        → middleware: ['auth', 'role:Técnico']
/cliente/*        → middleware: ['auth', 'role:Cliente']
```

---

## 🗄️ Modelos de Datos Principales

### Usuarios y Autenticación
```
usuarios ─── roles
   │
   ├─── clientes
   │       └─── vehiculos
   │           ├─── fotos
   │           ├─── ordenes_trabajo
   │           ├─── historial_vehiculo
   │           └─── ventas
   │
   └─── admin (todo)
```

### Órdenes de Trabajo
```
ordenes_trabajo
├── id_orden (PK)
├── id_vehiculo (FK)
├── id_usuario (técnico asignado)
├── estado
├── servicios (muchos-a-muchos)
├── productos (muchos-a-muchos)
├── consumoMateriales
└── facturas
```

### Inventario
```
productos
├── id_producto (PK)
├── nombre
├── precio_venta
├── categoria (relación)
└── inventario
    ├── cantidad_disponible
    └── auditoria_inventario (historial)
```

### Facturación
```
facturas
├── id_factura (PK)
├── numero_factura (único)
├── id_cliente (FK)
├── id_orden (FK nullable)
├── estado (Pendiente, Pagada, Anulada)
├── subtotal, impuesto, total
└── pagos
    ├── monto
    ├── metodo_pago
    ├── fecha_pago
    └── referencia
```

---

## 🔐 Seguridad

### Autenticación
- Contraseñas hasheadas con **bcrypt** (cost: 12)
- Sesiones almacenadas en base de datos
- CSRF token en todo formulario
- Rate limiting: 5 intentos fallidos = 15 min bloqueado

### Autorización
- **Middleware CheckRole**: Valida rol en cada ruta
- **Modelo: abort_if()**: Valida propiedad en controllers
- **Validación**: Clientes solo ven sus datos

### Datos Sensibles
- `.env`: Nunca en version control
- `password`: Solo hasheado en BD
- `correo`: Usado solo para contacto
- `telefono`: Almacenado pero no mostrado públicamente

---

## 🎨 Arquitectura Frontend

### Tailwind CSS
- **Sistema de diseño**: Utility-first
- **Clases personalizadas**: En `resources/css/app.css`
- **Compiled size**: 112.70 kB (19.89 kB gzip)
- **Breakpoints**: sm(640px), md(768px), lg(1024px), xl(1280px)

### Blade Templates
- **Layouts base**: `dashboard.blade.php` para roles
- **Componentes reutilizables**: Cards, tablas, formularios
- **Directivas**: @if, @foreach, @yield, @section
- **Helpers**: route(), auth(), abort_if()

### JavaScript
- **Tipo**: Vanilla JS (sin frameworks pesados)
- **AJAX**: Fetch API para peticiones asíncronas
- **Interactividad**: Modales, dropdowns, toggles

---

## 📡 Flujo de Datos - Ejemplo: Crear Orden

```
1. Cliente ve "Crear Orden" en /admin/ordenes/create
   ↓
2. Ruta: Route::get('ordenes/create', [OrdenController::class, 'create'])
   ↓
3. Controller: OrdenTrabajoController@create
   - Carga clientes, vehículos, servicios, productos
   ↓
4. Vista: admin/ordenes/create.blade.php
   - Renderiza formulario
   ↓
5. Usuario llena formulario y hace submit
   ↓
6. Ruta: Route::post('ordenes', [OrdenController::class, 'store'])
   ↓
7. Controller: OrdenTrabajoController@store
   - Valida con Validator
   - Crea orden con OrdenTrabajo::create()
   - Sincroniza relaciones: attach()
   - Retorna redirect con success
   ↓
8. Middleware: VerifyCsrfToken valida token
   ↓
9. Base de datos: INSERT INTO ordenes_trabajo
   ↓
10. Usuario ve: /admin/ordenes con nueva orden
```

---

## 🔄 Relaciones de Modelos

### HasMany (1 a muchos)
```php
Usuario hasMany Ordenes       // 1 usuario → muchas órdenes
Cliente hasMany Vehiculos     // 1 cliente → muchos vehículos
Vehiculo hasMany Ordenes      // 1 vehículo → muchas órdenes
```

### BelongsTo (muchos a 1)
```php
Orden belongsTo Usuario       // muchas órdenes → 1 usuario
Vehiculo belongsTo Cliente    // muchos vehículos → 1 cliente
```

### BelongsToMany (muchos a muchos)
```php
Orden belongsToMany Servicios // muchas órdenes ↔ muchos servicios
Orden belongsToMany Productos // muchas órdenes ↔ muchos productos
```

---

## 🚀 Ciclo de Vida de Solicitud HTTP

```
REQUEST
  ↓
public/index.php (punto de entrada)
  ↓
bootstrap/app.php (cargar Laravel)
  ↓
routes/web.php → routes/[rol].php (encontrar ruta)
  ↓
Middleware (HTTP Kernel):
  ├── EncryptCookies
  ├── AddQueuedCookiesToResponse
  ├── StartSession
  ├── ShareErrorsFromSession
  ├── VerifyCsrfToken
  ├── SubstituteBindings
  ├── Authenticate (si requiere auth)
  └── CheckRole (si requiere rol específico)
  ↓
Controller@method (procesar lógica)
  ├── Validar entrada
  ├── Interactuar con Models
  ├── Procesar datos
  └── Retornar vista o JSON
  ↓
Response (vista Blade o JSON)
  ├── Tailwind CSS (estilizar)
  ├── JavaScript (interactividad)
  └── Renderizar HTML
  ↓
Navegador renderiza
  ↓
USER SEES RESULT
```

---

## 📊 Flujo de Pagos (Feature Reciente)

```
Cliente ve factura pendiente
  ↓
Hace click en "Marcar como Pagada"
  ↓
JavaScript: marcarPagada()
  ├── Pide confirmación
  ├── Envía POST AJAX
  └── Muestra spinner
  ↓
Ruta: POST /cliente/facturas/{factura}/marcar-pagada
  ↓
Controller: FacturaController@marcarPagada
  ├── Valida: ¿Es cliente propietario?
  ├── Valida: ¿No está ya pagada?
  ├── UPDATE facturas SET estado = 'Pagada'
  ├── INSERT INTO pagos (nuevo registro)
  └── Retorna JSON con datos actualizados
  ↓
JavaScript actualiza en tiempo real:
  ├── Badge → verde (Pagada)
  ├── Total pagado → monto completo
  ├── Saldo → $0.00
  ├── Botón desaparece
  └── Notificación de éxito
  ↓
Página se recarga después de 2 segundos
```

---

## 🎯 Decisiones Arquitectónicas

### 1. **Tailwind CSS en lugar de Bootstrap**
- **Por qué**: Mejor performance (19.89 kB vs 150+ kB)
- **Ventaja**: Utility-first es más mantenible
- **Impacto**: CSS compilado y optimizado

### 2. **Roles en lugar de permisos granulares**
- **Por qué**: Simplicidad (3 roles claros)
- **Ventaja**: Fácil de entender y mantener
- **Impacto**: Escalabilidad limitada pero suficiente

### 3. **Sesiones en BD en lugar de cookies**
- **Por qué**: Seguridad y control
- **Ventaja**: Revocar sesión es instantáneo
- **Impacto**: Poco overhead (mismos datos)

### 4. **Laravel Mail Async (Queue)**
- **Por qué**: No bloquear request
- **Ventaja**: Mejor UX (respuesta rápida)
- **Impacto**: Requiere queue worker corriendo

### 5. **PDF generado on-demand**
- **Por qué**: Flexibilidad (datos siempre actualizados)
- **Ventaja**: No hay archivos PDF obsoletos
- **Impacto**: CPU al generar PDF

---

**Última actualización**: Septiembre 2026
