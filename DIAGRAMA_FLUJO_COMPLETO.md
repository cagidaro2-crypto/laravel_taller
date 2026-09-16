# 🔧 TallerPro - Diagrama de Flujo Completo del Sistema

## Resumen Ejecutivo

**TallerPro** es un Sistema de Gestión Integral para Talleres de Latonería y Pintura Automotriz que utiliza arquitectura MVC manual con PHP 8.1+, MySQL 8.0 y Bootstrap 5.3.

---

## 📊 Flujo Principal del Sistema

```
┌─────────────────────────────────────────────────────────────────────┐
│                    🌐 ACCESO AL SISTEMA                             │
│         http://localhost/SystemTaller/views/usuarios/               │
└─────────────────────┬───────────────────────────────────────────────┘
                      │
                      ▼
        ┌─────────────────────────────┐
        │  ¿Usuario Autenticado?      │
        └──┬──────────────────────┬───┘
           │ NO                   │ SÍ
           ▼                      ▼
    ┌──────────────┐      ┌──────────────────┐
    │ LOGIN/REGISTRO│     │ Verificar Rol    │
    │ AuthController│     │ del Usuario      │
    └──────┬───────┘      └──┬──────────┬──────┬──────┐
           │                 │          │      │      │
           └─► BD: usuarios  │          │      │      │
                             │          │      │      │
        ┌────────────────────┘          │      │      │
        │                               │      │      │
        ▼                               ▼      ▼      ▼
   ┌─────────┐            ┌──────────┐ ┌────┐ ┌────┐ ┌────┐
   │ ADMIN   │            │ EMPLEADO │ │    │ │    │ │    │
   └─────────┘            └──────────┘ │    │ │    │ │    │
```

---

## 👨‍💼 DASHBOARD ADMINISTRADOR

### Acceso: `public/dashboard.php?role=admin`
### Controller Principal: `AdminCitaController`, `AdminClienteController`, etc.

```
┌─────────────────────────────────────────────────────────────────────┐
│                    👨‍💼 PANEL DE ADMINISTRADOR                         │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌─────────────┐  ┌──────────────┐  ┌──────────────┐              │
│  │👥 Usuarios │  │🏢 Clientes  │  │🚗 Vehículos│              │
│  │            │  │              │  │            │              │
│  │ Create     │  │ Create       │  │ Create     │              │
│  │ Edit       │  │ Edit         │  │ Edit       │              │
│  │ Delete     │  │ Delete       │  │ Delete     │              │
│  │ Ver Roles  │  │ Ver Historial│  │ Galería    │              │
│  └─────────────┘  └──────────────┘  └──────────────┘              │
│                                                                     │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐             │
│  │📋 Órdenes  │  │💰 Cotizaciones│ │📊 Facturas  │             │
│  │            │  │               │  │            │             │
│  │ Crear      │  │ Crear         │  │ Generar    │             │
│  │ Asignar    │  │ Ver Estado    │  │ Consultar  │             │
│  │ Seguimiento│  │ Aceptar/Rechaz│  │ Descargar  │             │
│  │ Historial  │  │ Presupuestar  │  │ PDF        │             │
│  └──────────────┘  └──────────────┘  └──────────────┘             │
│                                                                     │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐             │
│  │📅 Citas     │  │📦 Inventario │  │🤝 Proveedores           │
│  │            │  │               │  │            │             │
│  │ Agendar    │  │ Stock         │  │ Contactos  │             │
│  │ Confirmar  │  │ Productos     │  │ Órdenes    │             │
│  │ Cancelar   │  │ Categorías    │  │ Compras    │             │
│  │ Calendario │  │ Precios       │  │ Deudas     │             │
│  └──────────────┘  └──────────────┘  └──────────────┘             │
│                                                                     │
│  ┌──────────────────────┐      ┌──────────────────────┐           │
│  │📈 Reportes/Analytics │      │💵 Ventas            │           │
│  │                      │      │                     │           │
│  │ Ingresos mensuales   │      │ Servicios vendidos  │           │
│  │ Clientes activos     │      │ Productos vendidos  │           │
│  │ Órdenes completadas  │      │ Comisiones          │           │
│  │ Ocupación taller     │      │ Gráficos de ventas  │           │
│  └──────────────────────┘      └──────────────────────┘           │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │   BASE DE DATOS  │
                    │  latoneria_pintura
                    │                  │
                    │ • usuarios       │
                    │ • clientes       │
                    │ • vehículos      │
                    │ • citas          │
                    │ • cotizaciones   │
                    │ • facturas       │
                    │ • órdenes        │
                    │ • inventario     │
                    │ • proveedores    │
                    └──────────────────┘
```

---

## 👨‍🔧 DASHBOARD EMPLEADO

### Acceso: `public/dashboard.php?role=empleado`
### Controllers: `CitasCalendarioController`, `VehiculoController`, `AdminOrdenController`

```
┌─────────────────────────────────────────────────────────────────────┐
│                    👨‍🔧 PANEL DE EMPLEADO                             │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌────────────────────────┐  ┌────────────────────────┐           │
│  │📅 Citas Asignadas      │  │🚗 Vehículos en Taller │           │
│  │                        │  │                        │           │
│  │ Listar por fecha       │  │ Fotos: subir/ver       │           │
│  │ Estado: Pendiente      │  │ Detalles: marca, modelo            │
│  │         Realizada      │  │ Historial de trabajos  │           │
│  │         Cancelada      │  │ Estado del vehículo    │           │
│  │ Marcar como hecha      │  │ (FotoVehiculoController)           │
│  │ (CitasCalendarioController)                                     │
│  └────────────────────────┘  └────────────────────────┘           │
│                                                                     │
│  ┌────────────────────────┐  ┌────────────────────────┐           │
│  │📋 Órdenes de Trabajo   │  │📊 Consultar Facturas  │           │
│  │                        │  │                        │           │
│  │ Mis órdenes asignadas  │  │ Solo lectura           │           │
│  │ Ver detalles           │  │ Ver documentos         │           │
│  │ Actualizar estado      │  │ Verificar montos       │           │
│  │ Registrar tiempo       │  │ Descargar             │           │
│  │ Añadir notas           │  │ (FacturaController)   │           │
│  │ (AdminOrdenController) │  │                        │           │
│  └────────────────────────┘  └────────────────────────┘           │
│                                                                     │
│  ┌────────────────────────┐  ┌────────────────────────┐           │
│  │📦 Inventario           │  │💵 Registrar Ventas    │           │
│  │                        │  │                        │           │
│  │ Consultar stock        │  │ Servicios realizados   │           │
│  │ Registrar uso material │  │ Productos vendidos     │           │
│  │ Crear movimientos      │  │ Precios y totales      │           │
│  │ Reportes de materiales │  │ Comisión calculada     │           │
│  │ (AdminInventarioController)                                    │
│  └────────────────────────┘  └────────────────────────┘           │
│                                                                     │
│  ┌────────────────────────────────────────────┐                   │
│  │📜 Historial de Órdenes/Actividad          │                   │
│  │                                            │                   │
│  │ Timeline de tareas completadas             │                   │
│  │ Órdenes del día anterior/próximo           │                   │
│  │ Estadísticas personales                    │                   │
│  │ (HistorialAjaxController - Datos AJAX)     │                   │
│  └────────────────────────────────────────────┘                   │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 👤 DASHBOARD CLIENTE

### Acceso: `public/dashboard.php?role=cliente`
### Controllers: `ClienteController`, `ClienteCitaController`, `VehiculoController`

```
┌─────────────────────────────────────────────────────────────────────┐
│                    👤 PORTAL DEL CLIENTE                            │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌──────────────────────┐  ┌──────────────────────┐               │
│  │🚗 Mis Vehículos      │  │📅 Agendar Citas     │               │
│  │                      │  │                      │               │
│  │ Registrar vehículo   │  │ Seleccionar fecha    │               │
│  │ Editar datos         │  │ Tipo de servicio     │               │
│  │ Ver historial        │  │ Preferencias         │               │
│  │ (ClienteController)  │  │ (ClienteCitaController)              │
│  │                      │  │ Confirmación: Pendiente               │
│  └──────────────────────┘  └──────────────────────┘               │
│                                                                     │
│  ┌──────────────────────┐  ┌──────────────────────┐               │
│  │💰 Cotizaciones       │  │📋 Mis Órdenes       │               │
│  │                      │  │                      │               │
│  │ Ver presupuestos     │  │ Estado de servicios  │               │
│  │ Detalles: items      │  │ Fechas de entrega    │               │
│  │ Totales              │  │ Histórico trabajos   │               │
│  │ Estado: Aceptada     │  │ Solo lectura         │               │
│  │         Rechazada    │  │                      │               │
│  │         Pendiente    │  │                      │               │
│  └──────────────────────┘  └──────────────────────┘               │
│                                                                     │
│  ┌──────────────────────────────────────────┐                    │
│  │🛍️ Catálogo de Servicios                 │                    │
│  │                                          │                    │
│  │ Lista servicios disponibles              │                    │
│  │ Descripción y precios indicativos        │                    │
│  │ Fotosgalería                             │                    │
│  │ (cliente_catalogo.php - FotosAjaxController)                  │
│  └──────────────────────────────────────────┘                    │
│                                                                     │
│  ┌──────────────────────────────────────────┐                    │
│  │📊 Mi Dashboard                           │                    │
│  │                                          │                    │
│  │ Resumen de actividad                     │                    │
│  │ Próximas citas                           │                    │
│  │ Presupuestos pendientes                  │                    │
│  │ (DashboardController)                    │                    │
│  └──────────────────────────────────────────┘                    │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 🗄️ ARQUITECTURA DE BASE DE DATOS

### Tablas Principales y Relaciones

```
┌─────────────────────────────────────────────────────────────────────┐
│                  ESTRUCTURA DE BASE DE DATOS                        │
│                   latoneria_pintura (MySQL 8.0)                    │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│                          ┌─────────────┐                           │
│                          │  USUARIOS   │                           │
│                          │             │                           │
│                          │ id_usuario  │──┐ 1:N                   │
│                          │ nombre      │  │                       │
│                          │ correo      │  │                       │
│                          │ rol         │  │                       │
│                          │ contraseña  │  │                       │
│                          │ estado      │  │                       │
│                          │ created_at  │  │                       │
│                          └─────────────┘  │                       │
│                                           │                       │
│                    ┌──────────────────────┘                       │
│                    │                                              │
│                    ▼                                              │
│              ┌──────────────┐                                    │
│              │  CLIENTES    │                                    │
│              │              │                                    │
│              │ id_cliente   │──┐ 1:N ──────┐                   │
│              │ id_usuario   │  │            │                   │
│              │ documento    │  │            │                   │
│              │ nombres      │  │            │                   │
│              │ telefono     │  │            │                   │
│              │ correo       │  │            │                   │
│              │ direccion    │  │            │                   │
│              └──────────────┘  │            │                   │
│                                 │            │                   │
│              ┌──────────────┐   │            │                   │
│              │  VEHÍCULOS   │◄──┘            │                   │
│              │              │                │                   │
│              │ id_vehiculo  │──┐ 1:N        │                   │
│              │ id_cliente   │  │             │                   │
│              │ placa        │  │             │                   │
│              │ marca        │  │             │                   │
│              │ modelo       │  │             │                   │
│              │ color        │  │             │                   │
│              │ año          │  │             │                   │
│              │ fotos_url    │  │             │                   │
│              └──────────────┘  │             │                   │
│                                 │             │                   │
│           ┌─────────────────┐   │     ┌───────┴─────────┐        │
│           │    CITAS        │◄──┘     │                 │        │
│           │                 │         │                 │        │
│           │ id_cita         │──┐      │                 ▼        │
│           │ id_cliente      │  │      │           ┌──────────────┤
│           │ id_vehiculo     │  │      │           │ COTIZACIONES │
│           │ tipo_servicio   │  │      │           │              │
│           │ fecha_cita      │  │      │           │ id_cotizaci  │
│           │ estado          │  │      │           │ id_cliente   │
│           └─────────────────┘  │      │           │ id_vehiculo  │
│                                 │      │           │ pago_total   │
│                                 │      │           │ estado       │
│                                 │      │           └──────┬───────┤
│                                 │      │                  │ 1:N  │
│           ┌─────────────────┐   │      │                  │     │
│           │ ORDENES_SERVICIO│◄──┘      │           ┌──────▼──────┤
│           │                 │          │           │COTIZACI_PROD │
│           │ id_orden        │──┐       │           │              │
│           │ id_cliente      │  │       │           │ id_detalle   │
│           │ id_vehiculo     │  │       │           │ id_cotizac   │
│           │ descripcion     │  │       │           │ id_producto  │
│           │ estado          │  │       │           │ cantidad     │
│           │ fecha_inicio    │  │       │           │ precio       │
│           └─────────────────┘  │       │           └──────────────┤
│                                 │       │                         │
│                                 │       │           ┌──────────────┤
│                 ┌───────────────┴───────┴───────┐   │              │
│                 │                               │   │              │
│                 ▼                               │   │              │
│           ┌──────────────┐                 ┌────▼──▼──────────┐  │
│           │  FACTURAS    │                 │   INVENTARIO     │  │
│           │              │                 │                  │  │
│           │ id_factura   │                 │ id_inventario    │  │
│           │ id_orden     │                 │ id_producto      │  │
│           │ id_cliente   │                 │ cantidad         │  │
│           │ monto_total  │                 │ precio_unitario  │  │
│           │ fecha        │                 │ categoria        │  │
│           │ estado       │                 └────────────────┘  │
│           └──────────────┘                                      │
│                                      ┌──────────────────────┐    │
│                                      │  PROVEEDORES         │    │
│                                      │                      │    │
│                                      │ id_proveedor         │    │
│                                      │ nombre               │    │
│                                      │ contacto             │    │
│                                      │ telefono             │    │
│                                      │ correo               │    │
│                                      │ direccion            │    │
│                                      │ especialidad         │    │
│                                      └──────────────────────┘    │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 🔄 FLUJOS DE PROCESOS PRINCIPALES

### 1️⃣ Flujo de Cita (Cliente → Admin → Empleado)

```
Cliente                          Sistema                        Admin/Empleado
   │                               │                                 │
   ├──► Solicita Cita ─────────────► ClienteCitaController          │
   │    (ClienteCitaController)      Creates Cita Record            │
   │                                 Envía notificación              │
   │                                                                  │
   │                                                              Admin ve
   │                                                              cita pendiente
   │                                                                  │
   │                                 Admin revisa ◄───────────────────┤
   │                                 y Confirma                       │
   │                               (AdminCitaController)             │
   │◄───── Notificación Cita ────── Status: Confirmada           
   │       Confirmada                                                
   │                                 Admin asigna ◄───────────────────┤
   │                                 a empleado                       
   │                                                                  │
   │                                                              Empleado
   │                                                              recibe
   │                                                              cita y
   │                                                              ve en su
   │                                 Empleado ve (CitasCalendario   dashboard
   │                                 Controller)                  
   │                                                              │
   │                                                          Realiza trabajo
   │                                                              │
   │                                                          Marca como
   │                                                          "Realizada"
   │                                                              │
   │◄───── Notificación Cita ────── Cita Realizada ───────────────┤
   │       Completada               Status: Realizada
   │
```

### 2️⃣ Flujo de Cotización (Admin → Cliente)

```
Admin                            Sistema                        Cliente
 │                                │                              │
 ├──► Crear Cotización ──────────► AdminCotizacionController     │
 │    Selecciona:                 Creates Cotización             │
 │    • Cliente                   Calcula total                  │
 │    • Vehículo                  Estado: Pendiente              │
 │    • Servicios/Productos                                      │
 │    • Precios                                                  │
 │                                                               │
 │                                                           Cliente
 │                                                           ve en su
 │                                                           portal
 │                                                               │
 │                                ◄──────── Cotización Generada ┤
 │                                                               │
 │                                                           Cliente
 │                                                           Acepta/
 │                                                           Rechaza
 │                                ┌─────────────────────────────┤
 │                                │                              │
 │                                ▼                              │
 │                        Estado: Aceptada/Rechazada
 │◄────── Notificación Cotización ───────────────► Cliente
 │        Actualizada                              recibe
 │                                                 confirmación
 │
 │ Si Aceptada ──► Crear Orden de Servicio
 │
```

### 3️⃣ Flujo de Facturación

```
Empleado                         Sistema                        Admin/Cliente
   │                               │                                 │
   ├──► Registra Orden ────────────► AdminOrdenController           │
   │    Completa                    Calcula montos                   │
   │                                                                  │
   │    Marque como                                                  │
   │    "Finalizada"                                                 │
   │                                                                  │
   │                              FacturaController              Admin genera
   │                              (Admin action)                  factura
   │                              Crea Factura                       │
   │                              Calcula impuestos
   │                              Total final
   │                                                            Cliente recibe
   │◄───── PDF Factura ──────────────────────────────────────────► factura
   │       Descargar                                               (email)
   │
```

---

## 🌐 ESTRUCTURA DE CARPETAS Y ARCHIVOS

```
systemTaller/
│
├── config/
│   └── database.php              ◄─ Conexión PDO a MySQL
│
├── controllers/                  ◄─ Lógica de negocio
│   ├── AuthController.php        ◄─ Login/Logout/Registro
│   ├── AdminCitaController.php
│   ├── AdminClienteController.php
│   ├── AdminCotizacionController.php
│   ├── AdminInventarioController.php
│   ├── AdminOrdenController.php
│   ├── AdminProveedorController.php
│   ├── AdminUsuarioController.php
│   ├── AdminVehiculoController.php
│   ├── CitasCalendarioController.php
│   ├── ClienteCitaController.php
│   ├── ClienteController.php
│   ├── DashboardController.php
│   ├── DashboardDataController.php ◄─ AJAX para datos en tiempo real
│   ├── FacturaController.php
│   ├── FotosAjaxController.php    ◄─ Galería de vehículos
│   ├── FotoVehiculoController.php
│   ├── HistorialAjaxController.php ◄─ Historial dinámico
│   └── VehiculoController.php
│
├── models/                       ◄─ Acceso a datos (PDO)
│   ├── Usuario.php
│   ├── cliente.php
│   ├── Vehiculo.php
│   ├── cotizacion.php
│   ├── OrdenServicio.php
│   ├── Facturacion.php
│   └── inventario.php
│
├── views/
│   ├── layouts/
│   │   ├── header.php           ◄─ Navegación y menú
│   │   ├── footer.php           ◄─ Pie de página
│   │   └── sidebar.php          ◄─ Menú lateral
│   │
│   ├── usuarios/
│   │   ├── login.php            ◄─ Página de login
│   │   ├── registro.php         ◄─ Registro de nuevos usuarios
│   │   └── recuperar.php        ◄─ Recuperación de contraseña
│   │
│   └── dashboard/
│       ├── admin_dashboard.php
│       ├── admin_usuarios.php
│       ├── admin_clientes.php
│       ├── admin_vehiculos.php
│       ├── admin_citas.php
│       ├── admin_cotizaciones.php
│       ├── admin_ordenes.php
│       ├── admin_facturas.php
│       ├── admin_inventario.php
│       ├── admin_proveedores.php
│       ├── admin_reportes.php
│       ├── admin_ventas.php
│       │
│       ├── empleado_dashboard.php
│       ├── empleado_citas.php
│       ├── empleado_ordenes.php
│       ├── empleado_vehiculos.php
│       ├── empleado_historial.php
│       ├── empleado_inventario.php
│       ├── empleado_ventas.php
│       │
│       ├── cliente_dashboard.php
│       ├── cliente_vehiculos.php
│       ├── cliente_citas.php
│       ├── cliente_cotizaciones.php
│       ├── cliente_ordenes.php
│       └── cliente_catalogo.php ◄─ Catálogo público
│
├── public/
│   ├── index.php                ◄─ Punto de entrada
│   ├── dashboard.php            ◄─ Distribuidor a dashboards
│   ├── css/
│   │   └── dashboard.css        ◄─ Estilos Bootstrap customizados
│   ├── img/                     ◄─ Logos y assets
│   └── uploads/
│       └── vehiculos/           ◄─ Fotos de vehículos
│
├── database/
│   ├── BD_TALLER.sql           ◄─ Script de creación de BD
│   └── migracion_segura.sql    ◄─ Script de migración
│
├── routes/
│   └── login.php               ◄─ Rutas de autenticación
│
├── documentacion/
│   ├── SRS.md
│   └── UML.md
│
└── README.md
```

---

## 🔐 Sistema de Autenticación y Roles

```
Login (AuthController)
        │
        ├─► Validar correo/contraseña
        │
        ├─► ¿Usuario existe y activo?
        │
        └─► $_SESSION['user_id', 'rol', 'nombre']
                │
                ├─► rol = 'admin'
                │   └─► Acceso a TODAS las funciones
                │
                ├─► rol = 'empleado'
                │   └─► Acceso a: Citas, Órdenes, 
                │       Vehículos, Inventario, Ventas
                │
                └─► rol = 'cliente'
                    └─► Acceso a: Mis Vehículos,
                        Mis Citas, Mis Cotizaciones,
                        Mis Órdenes
```

---

## ⚡ Flujo de Datos en Tiempo Real (AJAX)

```
Cliente/Empleado/Admin
        │
        ├──► JavaScript AJAX
        │    $.get('/controllers/DashboardDataController.php')
        │    $.get('/controllers/FotosAjaxController.php')
        │    $.get('/controllers/HistorialAjaxController.php')
        │
        ▼
    Controller AJAX
        │
        ├──► Query a BD
        │
        ├──► Prepara datos
        │
        └──► echo json_encode($datos)
                │
                ▼
        JavaScript recibe JSON
                │
                ├──► Actualiza DOM
                │
                └──► No requiere recargar página
```

---

## 📱 Flujo de Solicitudes HTTP

```
┌──────────┐
│ Usuario  │
│ Interfaz │
└────┬─────┘
     │
     ▼
   GET/POST
    /views/usuarios/login.php
    /public/dashboard.php
    /controllers/AdminClienteController.php
    /controllers/DashboardDataController.php (AJAX)
     │
     ▼
┌─────────────────────┐
│  PHP Controller     │
│ Recibe petición     │
│ Valida sesión       │
│ Valida input        │
└────┬────────────────┘
     │
     ▼
┌─────────────────────┐
│  Model (PDO)        │
│ Query a BD          │
│ Retorna resultados  │
└────┬────────────────┘
     │
     ▼
┌─────────────────────┐
│  MySQL BD           │
│ latoneria_pintura   │
└────┬────────────────┘
     │
     ▼
┌──────────────────────┐
│ Controller procesa   │
│ respuesta            │
│ • HTML (vistas)      │
│ • JSON (AJAX)        │
│ • PDF (facturas)     │
└────┬─────────────────┘
     │
     ▼
  Response
  HTML/JSON/PDF
     │
     ▼
   Browser
   Renderiza
```

---

## 📊 Módulos y Funcionalidades por Rol

| Módulo | Admin | Empleado | Cliente |
|--------|:-----:|:--------:|:-------:|
| **Dashboard** | ✅ Completo | ✅ Operativo | ✅ Personal |
| **Usuarios** | ✅ CRUD | ❌ No | ❌ No |
| **Clientes** | ✅ CRUD | ❌ No | ✅ Perfil propio |
| **Vehículos** | ✅ CRUD + Fotos | ✅ Ver/Editar | ✅ Gestionar propios |
| **Citas** | ✅ CRUD + Calendario | ✅ Ver + Confirmar | ✅ Agendar |
| **Cotizaciones** | ✅ Crear + Ver | ❌ No | ✅ Ver + Aceptar/Rechazar |
| **Órdenes** | ✅ CRUD | ✅ Ver + Actualizar | ✅ Ver Estado |
| **Facturas** | ✅ Generar + Ver | ✅ Ver | ❌ No |
| **Inventario** | ✅ CRUD | ✅ Registrar uso | ❌ No |
| **Ventas** | ✅ Reportes | ✅ Registrar | ❌ No |
| **Proveedores** | ✅ CRUD | ❌ No | ❌ No |
| **Reportes** | ✅ Completos | ❌ No | ❌ No |

---

## 🚀 Tecnologías Utilizadas

- **Backend:** PHP 8.1+ (MVC manual)
- **Base de Datos:** MySQL 8.0
- **Frontend:** Bootstrap 5.3, JavaScript vanilla, jQuery
- **AJAX:** Llamadas dinámicas sin refresco de página
- **Servidor:** Apache (Laragon)
- **Conexión:** PDO (PHP Data Objects)
- **Seguridad:** Sesiones PHP, validación de inputs, prepared statements

---

## 🔗 Puntos de Entrada Principales

1. **Inicio:** `http://localhost/SystemTaller/views/usuarios/login.php`
2. **Dashboard Admin:** `http://localhost/SystemTaller/public/dashboard.php?role=admin`
3. **Dashboard Empleado:** `http://localhost/SystemTaller/public/dashboard.php?role=empleado`
4. **Dashboard Cliente:** `http://localhost/SystemTaller/public/dashboard.php?role=cliente`

---

## 📝 Flujo de Datos Ejemplo: Crear una Cita

```
1. Cliente accede a ClienteCitaController
2. Selecciona: Fecha, Vehículo, Tipo de servicio
3. Envía POST a AdminCitaController
4. AdminCitaController:
   - Valida datos
   - Inserta en BD (tabla citas)
   - Estado = 'Pendiente'
5. BD genera id_cita
6. Sistema notifica Admin
7. Admin ve en dashboard (admin_citas.php)
8. Admin confirma (AdminCitaController)
9. Estado = 'Confirmada'
10. Sistema notifica a Cliente y Empleado
11. Empleado ve en CitasCalendarioController
12. Empleado realiza trabajo
13. Marca como 'Realizada'
14. Se puede generar cotización o factura si es necesario
15. Ciclo completo
```

---

**Generado:** Agosto 2026  
**Proyecto:** TallerPro - Sistema de Gestión de Taller  
**Versión:** 1.0
