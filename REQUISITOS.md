# 📋 REQUISITOS DEL SISTEMA — Taller Latonería y Pintura

**Proyecto:** TallerPro — Sistema de Gestión Integral  
**Versión:** 1.0  
**Fecha:** Agosto 2026  

---

## 1. REQUISITOS FUNCIONALES

### 1.1 🔐 Módulo de Autenticación

| ID | Requisito | Rol |
|----|-----------|-----|
| RF-01 | El sistema debe permitir iniciar sesión con correo y contraseña | Todos |
| RF-02 | El sistema debe validar que los campos de login no estén vacíos | Todos |
| RF-03 | El sistema debe mostrar un mensaje de error si las credenciales son incorrectas | Todos |
| RF-04 | El sistema debe bloquear temporalmente la cuenta tras 5 intentos fallidos | Todos |
| RF-05 | El sistema debe redirigir al usuario a su panel según su rol (Admin / Técnico / Cliente) | Todos |
| RF-06 | El sistema debe permitir cerrar sesión con confirmación | Todos |
| RF-07 | El sistema debe permitir registrar una cuenta nueva como cliente | Cliente |
| RF-08 | El sistema debe permitir recuperar la contraseña por correo electrónico | Todos |
| RF-09 | El enlace de recuperación de contraseña debe expirar en 30 minutos | Todos |

---

### 1.2 👥 Módulo de Usuarios

| ID | Requisito | Rol |
|----|-----------|-----|
| RF-10 | El administrador debe poder registrar nuevos usuarios (empleados) | Admin |
| RF-11 | El administrador debe poder editar la información de cualquier usuario | Admin |
| RF-12 | El administrador debe poder eliminar usuarios sin registros activos asociados | Admin |
| RF-13 | El sistema debe impedir eliminar usuarios con órdenes, citas o cotizaciones activas | Admin |
| RF-14 | El administrador debe poder activar o desactivar usuarios | Admin |
| RF-15 | El administrador debe poder asignar roles a los usuarios | Admin |
| RF-16 | El sistema debe validar que no existan usuarios duplicados por correo | Admin |

---

### 1.3 🚗 Módulo de Vehículos

| ID | Requisito | Rol |
|----|-----------|-----|
| RF-17 | El cliente debe poder registrar sus vehículos con placa, marca, modelo, año y color | Cliente |
| RF-18 | El sistema debe validar el formato de la placa (ABC-123) | Cliente |
| RF-19 | El sistema debe impedir registrar placas duplicadas | Cliente |
| RF-20 | El cliente debe poder ver el historial de servicios de su vehículo | Cliente |
| RF-21 | El técnico debe poder ver los vehículos en taller con su estado actual | Técnico |
| RF-22 | El técnico debe poder actualizar el estado del vehículo (Ingresado / En espera / En reparación / Finalizado / Entregado) | Técnico |
| RF-23 | El sistema debe validar que solo se actualice el estado si el vehículo tiene una orden activa | Técnico |
| RF-24 | El administrador debe poder gestionar (crear, editar, eliminar) vehículos | Admin |
| RF-25 | El sistema debe permitir subir fotografías del vehículo (antes, durante, después) | Admin / Técnico / Cliente |
| RF-26 | Las fotografías deben ser en formato JPG, PNG o JPEG con máximo 10 MB | Todos |
| RF-27 | El sistema debe mostrar galería de fotos del vehículo organizada cronológicamente | Todos |
| RF-28 | La eliminación de fotos debe requerir confirmación del administrador | Admin |

---

### 1.4 📅 Módulo de Citas

| ID | Requisito | Rol |
|----|-----------|-----|
| RF-29 | El cliente debe poder agendar una cita seleccionando fecha, hora y tipo de servicio | Cliente |
| RF-30 | El sistema debe impedir agendar en horarios ya ocupados o fuera del horario laboral | Cliente |
| RF-31 | El sistema debe generar un número de referencia único por cada cita | Cliente |
| RF-32 | El cliente debe poder cancelar una cita y el sistema debe notificar al taller | Cliente |
| RF-33 | El cliente debe poder ver todas sus citas (activas e historial) | Cliente |
| RF-34 | El administrador debe poder confirmar, cancelar o reasignar citas | Admin |
| RF-35 | El administrador debe poder ver todas las citas en una vista de calendario | Admin |
| RF-36 | El técnico debe poder ver las citas que tiene asignadas filtradas por fecha y estado | Técnico |
| RF-37 | El sistema debe enviar recordatorio automático al cliente 24 horas antes de la cita | Sistema |
| RF-38 | El sistema debe notificar al administrador cuando un cliente agende o cancele una cita | Sistema |

---

### 1.5 💰 Módulo de Cotizaciones

| ID | Requisito | Rol |
|----|-----------|-----|
| RF-39 | El administrador debe poder crear cotizaciones con cliente, vehículo, servicios y productos | Admin |
| RF-40 | El sistema debe calcular automáticamente el subtotal, impuesto (19%) y total | Admin |
| RF-41 | El sistema debe validar que la cotización tenga al menos un servicio o producto | Admin |
| RF-42 | La cotización debe tener una fecha de vencimiento configurable | Admin |
| RF-43 | El cliente debe poder ver el detalle completo de sus cotizaciones | Cliente |
| RF-44 | El cliente debe poder aprobar o rechazar una cotización desde su portal | Cliente |
| RF-45 | El sistema debe impedir aprobar cotizaciones vencidas | Cliente |
| RF-46 | El administrador debe poder convertir una cotización aprobada en factura | Admin |
| RF-47 | El administrador debe poder registrar el rechazo de una cotización con motivo | Admin |
| RF-48 | El sistema debe notificar al administrador cuando el cliente apruebe o rechace una cotización | Sistema |

---

### 1.6 📋 Módulo de Órdenes de Trabajo

| ID | Requisito | Rol |
|----|-----------|-----|
| RF-49 | El administrador debe poder crear órdenes de trabajo con vehículo, técnico y descripción | Admin |
| RF-50 | Cada orden debe tener un número consecutivo único generado automáticamente | Sistema |
| RF-51 | El sistema debe impedir crear una orden si el vehículo ya tiene una orden activa | Admin |
| RF-52 | El sistema debe impedir crear una orden para un vehículo no registrado | Admin |
| RF-53 | El técnico debe poder actualizar el estado de sus órdenes asignadas | Técnico |
| RF-54 | El técnico debe poder agregar notas u observaciones a una orden | Técnico |
| RF-55 | El cliente debe poder consultar el estado de sus órdenes (solo lectura) | Cliente |
| RF-56 | El administrador debe poder asignar servicios y productos a una orden | Admin |
| RF-57 | El sistema debe registrar fecha de ingreso y fecha de salida del vehículo | Sistema |

---

### 1.7 📊 Módulo de Facturas

| ID | Requisito | Rol |
|----|-----------|-----|
| RF-58 | El administrador debe poder generar facturas a partir de órdenes completadas o cotizaciones aprobadas | Admin |
| RF-59 | Cada factura debe tener un número consecutivo único (ej: F-000001) | Sistema |
| RF-60 | La factura debe incluir datos del cliente, servicios, productos, subtotal, impuesto y total | Sistema |
| RF-61 | El administrador debe poder descargar la factura en formato PDF | Admin |
| RF-62 | El administrador debe poder consultar y filtrar facturas por fecha, cliente y estado | Admin |
| RF-63 | El técnico debe poder consultar facturas (solo lectura) | Técnico |
| RF-64 | El sistema debe registrar los pagos asociados a cada factura | Admin |

---

### 1.8 📦 Módulo de Inventario

| ID | Requisito | Rol |
|----|-----------|-----|
| RF-65 | El administrador debe poder registrar productos con nombre, categoría, marca, precio y stock | Admin |
| RF-66 | El sistema debe impedir registrar productos duplicados en la misma categoría | Admin |
| RF-67 | El administrador debe poder actualizar la cantidad de stock con motivo del ajuste | Admin |
| RF-68 | El sistema debe generar una alerta cuando un producto esté por debajo del stock mínimo | Sistema |
| RF-69 | El sistema debe permitir subir una imagen por producto (JPG, PNG, máx 10 MB) | Admin |
| RF-70 | El administrador debe poder filtrar el inventario por categoría, marca y disponibilidad | Admin |
| RF-71 | El administrador debe poder desactivar productos del catálogo | Admin |
| RF-72 | El técnico debe poder consultar el stock disponible de productos | Técnico |
| RF-73 | El sistema debe descontar automáticamente el stock al usar productos en una orden | Sistema |

---

### 1.9 💵 Módulo de Ventas

| ID | Requisito | Rol |
|----|-----------|-----|
| RF-74 | El técnico debe poder registrar ventas de servicios y productos | Técnico |
| RF-75 | El sistema debe actualizar el inventario al registrar una venta | Sistema |
| RF-76 | El sistema debe bloquear ventas si el stock es insuficiente | Sistema |
| RF-77 | El administrador debe poder consultar el historial de ventas con filtros | Admin |
| RF-78 | El administrador debe poder anular o modificar una venta dejando trazabilidad | Admin |
| RF-79 | El sistema debe generar un comprobante de pago al registrar una venta | Sistema |

---

### 1.10 🤝 Módulo de Proveedores

| ID | Requisito | Rol |
|----|-----------|-----|
| RF-80 | El administrador debe poder registrar proveedores con NIT, contacto y especialidad | Admin |
| RF-81 | El sistema debe impedir registrar proveedores duplicados por NIT o correo | Admin |
| RF-82 | El administrador debe poder editar y eliminar proveedores | Admin |
| RF-83 | El administrador debe poder ver el listado completo de proveedores activos | Admin |

---

### 1.11 📈 Módulo de Reportes

| ID | Requisito | Rol |
|----|-----------|-----|
| RF-84 | El administrador debe poder generar reportes de productividad por técnico y período | Admin |
| RF-85 | El administrador debe poder generar reportes de ingresos con filtros por fecha y servicio | Admin |
| RF-86 | El administrador debe poder generar reportes de consumo de materiales | Admin |
| RF-87 | El administrador debe poder exportar reportes en PDF o Excel | Admin |
| RF-88 | El sistema debe mostrar un mensaje si no hay datos para el período seleccionado | Admin |

---

### 1.12 🔔 Módulo de Notificaciones

| ID | Requisito | Rol |
|----|-----------|-----|
| RF-89 | El sistema debe notificar al cliente cuando inicie el servicio de su vehículo | Sistema |
| RF-90 | El sistema debe notificar al cliente cuando el vehículo esté listo para recoger | Sistema |
| RF-91 | El sistema debe notificar al cliente ante cualquier novedad o retraso | Sistema |
| RF-92 | El sistema debe registrar el error si falla el envío de una notificación y permitir reenvío | Sistema |
| RF-93 | El administrador debe poder enviar mensajes personalizados a clientes | Admin |
| RF-94 | El sistema debe enviar recordatorio de cita 24 horas antes | Sistema |

---

## 2. REQUISITOS NO FUNCIONALES

### 2.1 🔒 Seguridad

| ID | Requisito |
|----|-----------|
| RNF-01 | Las contraseñas deben almacenarse con hash seguro (bcrypt) |
| RNF-02 | Todos los formularios deben incluir protección CSRF |
| RNF-03 | Las consultas a la base de datos deben usar parámetros preparados (Prepared Statements / ORM) |
| RNF-04 | El sistema debe validar y sanitizar todos los inputs del usuario |
| RNF-05 | Las rutas protegidas deben verificar autenticación y rol antes de responder |
| RNF-06 | El sistema debe bloquear el acceso entre roles (un cliente no puede ver rutas de admin) |

### 2.2 ⚡ Rendimiento

| ID | Requisito |
|----|-----------|
| RNF-07 | Las páginas principales deben cargar en menos de 3 segundos |
| RNF-08 | Las consultas de listados deben usar paginación (máximo 15 registros por página) |
| RNF-09 | Las imágenes subidas deben comprimirse o limitarse a 10 MB |

### 2.3 🖥️ Usabilidad

| ID | Requisito |
|----|-----------|
| RNF-10 | La interfaz debe ser responsive (funcionar en móvil, tablet y escritorio) |
| RNF-11 | Los mensajes de error deben ser claros y en español |
| RNF-12 | El sistema debe confirmar las acciones destructivas (eliminar, cancelar) |
| RNF-13 | Los formularios deben resaltar visualmente los campos con errores |

### 2.4 🗄️ Base de Datos

| ID | Requisito |
|----|-----------|
| RNF-14 | La base de datos debe usar MySQL 8.0 |
| RNF-15 | Todas las tablas deben tener campos `created_at` y `updated_at` |
| RNF-16 | Las relaciones entre tablas deben tener claves foráneas con restricciones de integridad |
| RNF-17 | Los datos eliminados críticos deben usar soft delete o bloqueo por registros activos |

### 2.5 📁 Archivos

| ID | Requisito |
|----|-----------|
| RNF-18 | Las fotos de vehículos y productos deben almacenarse en `/storage/` |
| RNF-19 | Solo se deben aceptar formatos de imagen: JPG, JPEG, PNG |
| RNF-20 | El tamaño máximo de archivo por imagen es 10 MB |

---

## 3. RESUMEN DE MÓDULOS POR ROL

| Módulo | Administrador | Técnico (Empleado) | Cliente |
|--------|:---:|:---:|:---:|
| Autenticación | ✅ | ✅ | ✅ |
| Gestión de usuarios | ✅ CRUD | ❌ | ❌ |
| Vehículos | ✅ CRUD + Fotos | ✅ Ver + Estado + Fotos | ✅ Propios |
| Citas | ✅ CRUD + Calendario | ✅ Ver asignadas | ✅ Agendar / Cancelar |
| Cotizaciones | ✅ Crear + Gestionar | ❌ | ✅ Ver + Aprobar/Rechazar |
| Órdenes de trabajo | ✅ CRUD | ✅ Ver + Actualizar estado | ✅ Ver estado |
| Facturas | ✅ Generar + PDF | ✅ Ver (lectura) | ❌ |
| Inventario | ✅ CRUD | ✅ Consultar stock | ❌ |
| Ventas | ✅ Reportes + Gestión | ✅ Registrar | ❌ |
| Proveedores | ✅ CRUD | ❌ | ❌ |
| Reportes | ✅ Completos + Export | ❌ | ❌ |
| Notificaciones | ✅ Enviar + Gestionar | Recibe | Recibe |
| Catálogo de servicios | ✅ Gestionar | ✅ Consultar | ✅ Ver |

---

## 4. TECNOLOGÍAS REQUERIDAS

| Capa | Tecnología |
|------|-----------|
| Backend | PHP 8.3 / Laravel 12 |
| Base de datos | MySQL 8.0 |
| Frontend | Tailwind CSS v4 |
| Servidor local | Laragon (Apache) |
| Generación PDF | DomPDF / Barryvdh |
| Almacenamiento | Laravel Storage (local) |

---

**Total de requisitos funcionales:** 94  
**Total de requisitos no funcionales:** 20  
