# 📚 Documentación del Sistema - Taller Latonería

## 📖 Índice de Contenidos

Bienvenido a la documentación completa del Sistema de Gestión de Taller Latonería. Aquí encontrarás detalles línea por línea de cada archivo, explicando su propósito, dependencias y qué sucede si se modifica.

### 🚀 Inicio Rápido
- [Instalación](./setup/INSTALACION.md) - Cómo instalar y configurar el sistema
- [Configuración](./setup/CONFIGURACION.md) - Variables de entorno y configuración
- [Base de Datos](./setup/BASE_DATOS.md) - Estructura y migraciones

### 🏗️ Arquitectura
- [Visión General de Arquitectura](./ARQUITECTURA.md) - Estructura del proyecto

### 👥 Documentación por Roles

#### 👑 Administrador
- [Controllers Admin](./roles/admin/controllers.md) - Controladores del panel admin
- [Models Admin](./roles/admin/models.md) - Modelos de datos
- [Rutas Admin](./roles/admin/rutas.md) - Definición de rutas
- [Vistas Admin](./roles/admin/vistas.md) - Vistas y templates

#### 🔧 Técnico
- [Controllers Técnico](./roles/tecnico/controllers.md) - Controladores del panel técnico
- [Models Técnico](./roles/tecnico/models.md) - Modelos de datos
- [Rutas Técnico](./roles/tecnico/rutas.md) - Definición de rutas
- [Vistas Técnico](./roles/tecnico/vistas.md) - Vistas y templates

#### 👤 Cliente
- [Controllers Cliente](./roles/cliente/controllers.md) - Controladores del panel cliente
- [Models Cliente](./roles/cliente/models.md) - Modelos de datos
- [Rutas Cliente](./roles/cliente/rutas.md) - Definición de rutas
- [Vistas Cliente](./roles/cliente/vistas.md) - Vistas y templates

### ⚙️ Features Principales
- [Autenticación](./features/autenticacion.md) - Sistema de login y sesiones
- [Órdenes de Trabajo](./features/ordenes_trabajo.md) - Gestión de órdenes
- [Inventario](./features/inventario.md) - Control de inventario
- [Facturas](./features/facturas.md) - Sistema de facturación
- [Notificaciones](./features/notificaciones.md) - Sistema de notificaciones por email

### 🐛 Resolución de Problemas
- [Errores Comunes](./troubleshooting/errores_comunes.md) - Errores y soluciones
- [Resolución de Problemas](./troubleshooting/resolucion_problemas.md) - Troubleshooting

---

## 📝 Convenciones de Documentación

Cada documento sigue este formato:

```markdown
## Línea X: [Código]
**Propósito**: Explicación clara
**Dependencias**: Qué necesita esta línea
**Qué sucede si se borra**: Impacto de eliminar esta línea
**Cómo arreglarlo**: Pasos para restaurar
**Variaciones**: Alternativas válidas
```

---

## 🎯 Cómo Usar Esta Documentación

1. **Si instalas por primera vez**: Ve a [Instalación](./setup/INSTALACION.md)
2. **Si necesitas entender un rol**: Ve a la carpeta [roles/](./roles/)
3. **Si necesitas entender una feature**: Ve a [features/](./features/)
4. **Si tienes un error**: Ve a [Troubleshooting](./troubleshooting/)
5. **Si necesitas ver la arquitectura completa**: Lee [ARQUITECTURA.md](./ARQUITECTURA.md)
6. **📊 Para ver el progreso de documentación**: Ve a [INDICE_MAESTRO.md](./INDICE_MAESTRO.md)

---

## 🔧 Stack Técnico

- **Framework**: Laravel 11.x
- **PHP**: 8.3+
- **Base de Datos**: MySQL 8.0+
- **Frontend**: Tailwind CSS 4.0 + Blade Templates
- **Autenticación**: Laravel Auth
- **Email**: Laravel Mail (SMTP)
- **Queue**: Redis/Sync
- **Versionamiento**: Git

---

## 📞 Notas Importantes

⚠️ **Backup Importante**: Antes de realizar cambios importantes, haz un backup de la base de datos.

🔒 **Seguridad**: Nunca compartas las credenciales de .env con terceros.

📱 **Responsive**: El sistema está optimizado para desktop, tablet y móvil.

⚡ **Performance**: El CSS está compilado y optimizado con Tailwind. Tamaño: 19.89 kB (gzip).

---

**Última actualización**: Septiembre 2026
**Versión**: 1.0.0
**Mantenedor**: Sistema de Taller Latonería
