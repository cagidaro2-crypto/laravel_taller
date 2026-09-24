# 🔐 Guía de Inicio de Sesión - Sistema de Latonería

## ✨ Autenticación Lista para Usar

El sistema de autenticación ha sido diagnosticado y reparado completamente. Ahora funciona correctamente.

---

## 🚀 Primeros Pasos

### 1. Asegúrate de que el servidor está corriendo
```bash
php artisan serve
```
Accede a: `http://localhost:8000`

### 2. Dirígete a la página de login
```
http://localhost:8000/login
```

### 3. Inicia sesión con una de estas credenciales:

#### **Usuario de Prueba (Recomendado)**
```
Correo:      admin@test.com
Contraseña:  password123
Rol:         Administrador
```

#### **Usuarios Existentes**
```
Correo:      admin@taller.com
Rol:         Administrador

Correo:      tecnico@taller.com
Rol:         Técnico

Correo:      cliente@taller.com
Rol:         Cliente
```

---

## 🎯 Características del Login

✅ **Formulario intuitivo**: Validación en tiempo real  
✅ **Seguridad**: Rate limiting (5 intentos = 15 min bloqueado)  
✅ **Recuperación**: Link a recuperar contraseña  
✅ **Recordarme**: Opción para mantener sesión  
✅ **Feedback**: Mensajes claros sobre errores  

---

## 🔄 Flujo de Autenticación

```
1. Ingresas correo y contraseña
2. El sistema valida tus credenciales
3. Se verifica que tu cuenta esté activa
4. Se carga tu rol (Admin, Técnico, Cliente)
5. Se crea una sesión segura
6. Se regenera el token CSRF
7. Te redirige a tu dashboard según tu rol
```

---

## 📊 Roles y Dashboards

| Rol | Correo | Dashboard |
|-----|--------|-----------|
| Administrador | admin@test.com | `/admin/dashboard` |
| Técnico | tecnico@taller.com | `/tecnico/dashboard` |
| Cliente | cliente@taller.com | `/cliente/dashboard` |

---

## 🆘 Solución de Problemas

### ❌ "Correo o contraseña incorrectos"
- Verifica que escribiste bien el correo
- Asegúrate de usar las credenciales exactas
- Intenta con: `admin@test.com` / `password123`

### ❌ "Cuenta bloqueada temporalmente"
- Hiciste más de 5 intentos fallidos
- Espera 15 minutos o recupera tu contraseña
- O usa otro usuario para continuar

### ❌ "Esta cuenta está desactivada"
- El administrador ha desactivado esta cuenta
- Contacta con el administrador del sistema

### ❌ "Error en configuración de usuario"
- El usuario no tiene rol asignado
- Contacta con el administrador

---

## 🧪 Testing Rápido

Para validar que todo funciona:

```bash
# Ver todos los usuarios del sistema
php artisan diagnostico:sesion

# Ver logs de intentos de login
tail -f storage/logs/laravel.log | grep -i login
```

---

## 🔒 Seguridad

- ✅ Las contraseñas se hashean con bcrypt
- ✅ Las sesiones se regeneran tras login
- ✅ CSRF token obligatorio en cada formulario
- ✅ Rate limiting protege contra fuerza bruta
- ✅ Los errores son genéricos por seguridad (no revelan si existe el usuario)

---

## 💡 Tips

1. **Recuerda tu contraseña**: Usa "Recordarme" si quieres mantener sesión
2. **¿Olvidaste tu contraseña?**: Usa el link "Recupérala aquí" en login
3. **Cambiar contraseña**: Una vez logueado, ve a tu perfil
4. **Múltiples dispositivos**: Cada login es una sesión independiente

---

## 📝 Información de Sistemas

```
Configuración:
- Guard: web
- Provider: users
- Tabla: usuarios (id_usuario como PK)
- Auth Field: correo (email)
- Password: bcrypt hasheada
- Rate Limit: 5 intentos en 15 minutos
```

---

## ✅ Sistema Listo

El sistema de autenticación está **100% funcional** y listo para usar.

**¡Inicia sesión y comienza a usar el sistema!** 🚀

---

*Última actualización: 16 de Septiembre de 2026*
