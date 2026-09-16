# 🧪 Guía de Pruebas - Taller Laravel

**Última actualización**: 16 de Septiembre de 2026  
**Status**: ✅ Sistema listo para pruebas

---

## 🚀 Iniciar el Servidor

Si usas Laragon:
```bash
# Ya debería estar ejecutándose en http://localhost
# O accede a través del panel de Laragon
```

Si quieres usar artisan serve:
```bash
php artisan serve --host=127.0.0.1 --port=8000
# Luego accede a http://127.0.0.1:8000
```

---

## 🔐 Prueba 1: Login Admin

### Pasos
1. Navega a: `http://localhost/taller_laravel-main/login`
2. Ingresa:
   - **Email**: `admin@taller.com`
   - **Password**: `Admin123!`
3. Haz clic en "Ingresar al sistema"

### Resultado Esperado
✅ Redirige a `/admin/dashboard`  
✅ Ves el nombre "Admin Taller" en la esquina superior derecha  
✅ Puedes ver el menú de usuario en top-right  

### Si Falla
- [ ] Revisa la consola del navegador (F12)
- [ ] Verifica que la BD tiene usuarios: `SELECT * FROM usuarios;`
- [ ] Revisa los logs: `storage/logs/laravel.log`

---

## 🔐 Prueba 2: Login Técnico

### Pasos
1. Navega a: `http://localhost/taller_laravel-main/login`
2. Ingresa:
   - **Email**: `tecnico@taller.com`
   - **Password**: `Tecnico123!`
3. Haz clic en "Ingresar al sistema"

### Resultado Esperado
✅ Redirige a `/tecnico/dashboard`  
✅ Ves el nombre "Carlos Técnico" en top-right  
✅ Acceso limitado solo a módulos técnico  

---

## 🔐 Prueba 3: Login Cliente

### Pasos
1. Navega a: `http://localhost/taller_laravel-main/login`
2. Ingresa:
   - **Email**: `cliente@taller.com`
   - **Password**: `Cliente123!`
3. Haz clic en "Ingresar al sistema"

### Resultado Esperado
✅ Redirige a `/cliente/dashboard`  
✅ Ves el nombre "Juan Cliente" en top-right  
✅ Acceso limitado solo a portal cliente  

---

## 🚪 Prueba 4: Logout

### Pasos
1. Estando logeado, haz clic en el menú usuario (top-right)
2. Haz clic en "Logout" o "Cerrar sesión"

### Resultado Esperado
✅ Redirige a `/login`  
✅ Ves mensaje: "Sesión cerrada correctamente."  
✅ Session se borra de la BD  

---

## 🔒 Prueba 5: Protección de Rutas

### Admin No Puede Acceder a Tecnico
1. Loguéate como admin
2. Intenta acceder a: `/tecnico/dashboard`

### Resultado Esperado
✅ Error 403 Forbidden  
✅ Mensaje: "No tienes permiso para acceder a esta sección."  

### Tecnico No Puede Acceder a Admin
1. Loguéate como tecnico
2. Intenta acceder a: `/admin/dashboard`

### Resultado Esperado
✅ Error 403 Forbidden  

---

## 🔑 Prueba 6: Credenciales Inválidas

### Pasos
1. Navega a login
2. Ingresa:
   - **Email**: `admin@taller.com`
   - **Password**: `WrongPassword123!`
3. Haz clic en "Ingresar"

### Resultado Esperado
✅ No redirige  
✅ Mensaje de error: "Correo o contraseña incorrectos..."  
✅ Formulario permanece en la página  

---

## 🔒 Prueba 7: Rate Limiting

### Pasos
1. Ingresa credenciales incorrectas 5 veces seguidas
2. En el 6to intento

### Resultado Esperado
✅ Mensaje: "Cuenta bloqueada temporalmente. Intente en X minutos..."  
✅ Cuenta se desbloquea automáticamente después de 15 minutos  

---

## 📊 Prueba 8: Reportes (Como Admin)

### Ir a Reportes
1. Loguéate como admin
2. Ve a: `/admin/dashboard`
3. Haz clic en "Reportes" en el menú

### Resultado Esperado
✅ Ves página de reportes  
✅ Puedes seleccionar fechas  

### Prueba Reporte de Productividad
1. Selecciona fechas
2. Haz clic en "Ver Reporte"

### Resultado Esperado
✅ Muestra técnicos con órdenes en ese período  
✅ Muestra total de órdenes completadas  

---

## 🛍️ Prueba 9: Módulo de Ventas

### Como Admin
1. Loguéate como admin
2. Ve a: `Ventas` en el menú
3. Haz clic en "Nueva Venta"

### Resultado Esperado
✅ Puedes seleccionar cliente  
✅ Puedes agregar productos  
✅ Se calcula automáticamente subtotal  
✅ Se calcula automáticamente impuesto  
✅ Se calcula automáticamente total  

### Guardar Venta
1. Rellena todos los campos
2. Haz clic en "Guardar"

### Resultado Esperado
✅ Venta se registra en BD  
✅ Se actualiza el inventario  
✅ Redirige a lista de ventas  

---

## 📋 Prueba 10: Módulo de Productos

### Como Admin
1. Loguéate como admin
2. Ve a: `Productos` en el menú

### Resultado Esperado
✅ Ves lista de productos (si existen)  
✅ Puedes hacer clic en "Nuevo Producto"  
✅ Puedes editar productos existentes  

### Crear Nuevo Producto
1. Haz clic en "Nuevo Producto"
2. Rellena:
   - Nombre
   - Código
   - Categoría
   - Precio de compra
   - Precio de venta
3. Haz clic en "Guardar"

### Resultado Esperado
✅ Producto se crea  
✅ Aparece en la lista  
✅ Se crea registro en inventario  

---

## ✅ Checklist de Pruebas

### Autenticación
- [ ] Login admin funciona
- [ ] Login técnico funciona
- [ ] Login cliente funciona
- [ ] Logout funciona
- [ ] Protección de rutas funciona (403 en acceso no autorizado)
- [ ] Credenciales inválidas muestran error
- [ ] Rate limiting funciona (5 intentos)

### Dashboards
- [ ] Dashboard admin visible solo para admin
- [ ] Dashboard técnico visible solo para técnico
- [ ] Dashboard cliente visible solo para cliente
- [ ] Menú de usuario en top-right

### Módulos (Como Admin)
- [ ] Reportes carga sin errores
- [ ] Ventas carga sin errores
- [ ] Productos carga sin errores
- [ ] Servicios carga sin errores
- [ ] Proveedores carga sin errores
- [ ] Inventario carga sin errores
- [ ] Órdenes de trabajo carga sin errores
- [ ] Cotizaciones carga sin errores
- [ ] Facturas carga sin errores
- [ ] Usuarios carga sin errores

### Base de Datos
- [ ] Sessions se crean en la tabla sessions
- [ ] Sessions se borran al logout
- [ ] Usuarios en tabla usuarios
- [ ] Roles asignados correctamente
- [ ] No hay errores de foreign key

---

## 🐛 Troubleshooting

### "Página en blanco"
- [ ] Revisa los logs: `storage/logs/laravel.log`
- [ ] Verifica que APP_DEBUG=true en `.env`
- [ ] Limpia cachés: `php artisan cache:clear`

### "Error 500"
- [ ] Revisa logs
- [ ] Verifica conexión a BD
- [ ] Verifica que APP_KEY existe en `.env`

### "Route not found"
- [ ] Limpia rutas: `php artisan route:clear`
- [ ] Verifica que rutas están en `routes/*.php`

### "SQLSTATE error"
- [ ] Verifica que BD existe y está accesible
- [ ] Verifica que tablas existen: `SHOW TABLES;`
- [ ] Re-migra: `php artisan migrate:refresh --seed`

### "Undefined class"
- [ ] Revisa import de modelos
- [ ] Verifica que los archivos existen
- [ ] Limpia cache: `php artisan cache:clear`

---

## 📞 Comandos Útiles

```bash
# Limpiar todo
php artisan optimize:clear

# Re-migrar BD
php artisan migrate:refresh --seed

# Ver rutas
php artisan route:list

# Ver logs
tail -f storage/logs/laravel.log

# Iniciar servidor
php artisan serve --host=127.0.0.1 --port=8000

# Compilar assets (si es necesario)
npm run build

# Ver estado de migración
php artisan migrate:status
```

---

## 🎯 Resultado Esperado Final

Si todas las pruebas pasan:

✅ Sistema de autenticación funcional  
✅ Control de acceso por roles  
✅ Todos los módulos cargando  
✅ BD sincronizada correctamente  
✅ No hay errores PHP  
✅ Sessions funcionan correctamente  
✅ Reportes generan sin errores  

---

## 🎉 ¡Éxito!

Si llegaste aquí con todas las pruebas pasadas, tu sistema está listo para usar.

¡Felicidades! 🚀

