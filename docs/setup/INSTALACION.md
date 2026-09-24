# 🚀 Guía de Instalación - Taller Latonería

## Requisitos Previos

Antes de instalar el sistema, asegúrate de tener instalado:

- **PHP 8.3+** - [Descargar](https://www.php.net/downloads)
- **Composer** - [Descargar](https://getcomposer.org/download/)
- **MySQL 8.0+** - [Descargar](https://dev.mysql.com/downloads/mysql/)
- **Node.js 18+** - [Descargar](https://nodejs.org/)
- **Git** - [Descargar](https://git-scm.com/downloads)

### Verificar Instalación

```bash
# Verificar versiones
php --version          # Debe ser 8.3+
composer --version     # Composer 2.x
mysql --version        # MySQL 8.0+
node --version         # Node 18+
npm --version          # npm 9+
git --version          # Git 2.x
```

---

## Pasos de Instalación

### 1️⃣ Clonar el Repositorio

```bash
# Navega a tu directorio de proyectos
cd c:/laragon/www

# Clona el repositorio
git clone https://github.com/tu-usuario/taller_laravel.git
cd taller_laravel-main
```

**Qué sucede aquí:**
- Se descarga toda la base de código del proyecto
- Se crea la carpeta `taller_laravel-main` con el proyecto completo
- Git inicializa el historial de versiones

**Línea por línea:**
```bash
git clone <url>    # Descarga repositorio
cd <carpeta>       # Entra en el directorio
```

---

### 2️⃣ Instalar Dependencias PHP

```bash
# Instala dependencias de Composer
composer install

# Optimiza autoloader (IMPORTANTE para producción)
composer dump-autoload --optimize
```

**Qué sucede aquí:**
- Lee `composer.json` y descarga todas las dependencias PHP
- Crea carpeta `vendor/` con todas las librerías
- Genera autoloader optimizado para carga de clases

**Archivo clave: composer.json**
```json
{
  "require": {
    "php": "^8.3",
    "laravel/framework": "^11.0",
    "laravel/tinker": "^2.9"
  }
}
```

**Línea por línea:**
- `composer install` → Lee composer.json y descarga dependencias exactas
- `composer dump-autoload --optimize` → Genera mapa de clases optimizado

**Si falla:**
- Verifica que PHP esté en PATH
- Ejecuta: `php -m | find "curl"` (debe haber curl)
- Intenta: `composer install --no-interaction --prefer-dist`

---

### 3️⃣ Instalar Dependencias Node.js

```bash
# Instala dependencias npm
npm install

# Si npm install falla, intenta:
npm install --legacy-peer-deps
```

**Qué sucede aquí:**
- Lee `package.json` y descarga dependencias JavaScript
- Crea carpeta `node_modules/` con paquetes npm
- Instala Tailwind CSS, Vite y otros

**Archivo clave: package.json**
```json
{
  "dependencies": {
    "laravel-vite-plugin": "^1.0",
    "tailwindcss": "^4.0.0",
    "autoprefixer": "^10.4.0"
  }
}
```

**Línea por línea:**
- `npm install` → Lee package.json e instala todas las dependencias
- `--legacy-peer-deps` → Permite versiones antiguas si hay conflictos

**Si falla:**
- Verifica Node.js 18+: `node --version`
- Limpia cache: `npm cache clean --force`
- Reintenta: `npm install`

---

### 4️⃣ Copiar Archivo .env

```bash
# Copia archivo de ejemplo
cp .env.example .env

# O en Windows PowerShell:
Copy-Item .env.example .env
```

**Qué sucede aquí:**
- Se crea archivo `.env` con variables de configuración
- Este archivo es ignorado por Git (por seguridad)
- Contiene credenciales de base de datos, email, etc.

**Archivo creado: .env**
```env
APP_NAME="Taller Latonería"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taller_laravel
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-contraseña-app
```

**Línea por línea:**
- `APP_NAME` → Nombre que aparece en el navegador
- `APP_ENV=local` → Entorno (local, staging, production)
- `APP_DEBUG=true` → Mostrar errores detallados (SOLO en local)
- `DB_*` → Credenciales de base de datos
- `MAIL_*` → Configuración de envío de emails

**Si falta:**
- Crea manualmente .env basado en .env.example
- Llena credenciales de BD y email

---

### 5️⃣ Generar Clave de Aplicación

```bash
# Genera clave de encriptación
php artisan key:generate

# Verifica que se agregó a .env:
# APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxx
```

**Qué sucede aquí:**
- Genera una clave criptográfica aleatoria de 32 caracteres
- Se escribe en `.env` como `APP_KEY`
- Esta clave encripta cookies, sesiones, y datos sensibles

**Por qué es importante:**
- Sin esta clave, no puedes desencriptar datos
- Si la pierdes, TODAS las sesiones activas se invalidan

**Línea por línea:**
```bash
php artisan key:generate
# Genera clave random
# Escribe en .env
# Muestra: "Application key [base64:...] set successfully."
```

**Si falla:**
- Verifica que .env existe: `Test-Path .env`
- Verifica permisos: `icacls .env` (debe ser escribible)

---

### 6️⃣ Crear Base de Datos

```bash
# Acceder a MySQL
mysql -u root -p

# En la consola MySQL, crear base de datos:
CREATE DATABASE taller_laravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Verificar que se creó:
SHOW DATABASES;

# Salir:
exit
```

**Qué sucede aquí:**
- Crea base de datos MySQL con codificación UTF-8
- UTF8MB4 permite caracteres especiales (acentos, emojis)
- La collation unicode_ci no diferencia entre mayúsculas/minúsculas

**Línea por línea:**
```sql
CREATE DATABASE taller_laravel
  CHARACTER SET utf8mb4      -- Codificación de caracteres
  COLLATE utf8mb4_unicode_ci -- Comparación de strings
;
```

**Si falla:**
- Verifica que MySQL está corriendo: `mysqld --version`
- Verifica usuario root: `mysql -u root` (sin -p)
- Intenta crear con credenciales diferentes

---

### 7️⃣ Ejecutar Migraciones

```bash
# Crea todas las tablas en la BD
php artisan migrate

# Verifica que funcionó:
php artisan migrate:status

# Si necesitas revertir (CUIDADO - borra datos):
php artisan migrate:rollback
```

**Qué sucede aquí:**
- Lee archivos en `database/migrations/`
- Ejecuta cada migración que no ha sido ejecutada
- Crea tablas en la base de datos MySQL

**Archivo clave: database/migrations/***_create_usuarios_table.php**
```php
Schema::create('usuarios', function (Blueprint $table) {
    $table->id();                           // id_usuario INT PRIMARY KEY AUTO_INCREMENT
    $table->string('nombre', 100);          // nombre VARCHAR(100)
    $table->string('correo', 100)->unique();// correo VARCHAR(100) UNIQUE
    $table->string('password');             // password VARCHAR(255)
    $table->boolean('activo')->default(1); // activo BOOLEAN DEFAULT 1
    $table->foreignId('id_rol')            // id_rol INT FOREIGN KEY
        ->constrained('roles');
    $table->timestamps();                   // created_at, updated_at TIMESTAMP
});
```

**Línea por línea:**
- `$table->id()` → Crea columna id INT PRIMARY KEY AUTO_INCREMENT
- `$table->string('nombre', 100)` → Crea VARCHAR(100) para nombre
- `$table->unique()` → Asegura que no haya duplicados
- `$table->foreignId('id_rol')->constrained('roles')` → Crea FK a tabla roles
- `$table->timestamps()` → Crea created_at y updated_at automáticos

**Si falla:**
- Verifica BD existe: `php artisan tinker` → `DB::connection()->getPdo()`
- Verifica .env DB_* correctas
- Reinicia MySQL si es necesario

**¿Qué sucede si se borra una migración?**
- Ejecutar `migrate:rollback` causará error
- La tabla existe en BD pero Laravel no la conoce
- **Solución**: Agregar registro en tabla `migrations`

---

### 8️⃣ Ejecutar Seeders (Datos Iniciales)

```bash
# Inserta datos de prueba
php artisan db:seed

# O seed específico:
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=UsuarioSeeder
```

**Qué sucede aquí:**
- Lee archivos en `database/seeders/`
- Inserta datos iniciales en la BD
- Crea roles, usuarios de prueba, categorías, etc.

**Archivos clave: database/seeders/***Seeder.php**
```php
class RoleSeeder extends Seeder
{
    public function run()
    {
        Rol::create(['nombre_rol' => 'Administrador']);
        Rol::create(['nombre_rol' => 'Técnico']);
        Rol::create(['nombre_rol' => 'Cliente']);
    }
}
```

**Línea por línea:**
- `Rol::create([...])` → Inserta nuevo registro en tabla roles
- `['nombre_rol' => 'Administrador']` → Array con datos a insertar

**Si falla:**
- Verifica que migraciones ya corrieron
- Verifica que Seeders existen
- Revisa errores con: `php artisan db:seed --verbose`

---

### 9️⃣ Compilar Assets (Tailwind CSS + JavaScript)

```bash
# Desarrollo: Compilar CSS/JS y mirar cambios
npm run dev

# Producción: Compilar minificado
npm run build
```

**Qué sucede aquí:**
- Vite compilador transpila CSS y JavaScript
- Tailwind genera CSS optimizado (19.89 kB gzip)
- Crea archivos en `public/build/`

**Archivo clave: tailwind.config.js**
```js
export default {
  content: [
    "./resources/views/**/*.blade.php",  // Busca clases en vistas
    "./resources/js/**/*.js",             // Busca clases en JS
  ],
  theme: {
    extend: {},
  },
}
```

**Línea por línea:**
- `content: [...]` → Archivos donde buscar clases Tailwind usadas
- Tailwind genera CSS SOLO para clases encontradas (tree-shaking)
- Resultado: CSS muy pequeño (19.89 kB vs 150+ kB sin optimizar)

**Si falla:**
- Verifica que Node 18+: `node --version`
- Verifica npm install completó: `ls node_modules/@vitejs` debe existir
- Intenta: `npm run dev` (muestra errores en consola)

---

### 🔟 Iniciar Servidor de Desarrollo

```bash
# En otra terminal, inicia servidor Laravel
php artisan serve

# Accede en navegador:
# http://localhost:8000

# O especifica IP/puerto:
php artisan serve --host=0.0.0.0 --port=8001
```

**Qué sucede aquí:**
- Inicia servidor HTTP built-in de Laravel
- Escucha en localhost:8000
- Carga rutas y controladores dinámicamente

**Línea por línea:**
```bash
php artisan serve
# Inicia Artisan (CLI de Laravel)
# serve = comando para iniciar servidor
# --host=0.0.0.0 = accesible desde cualquier IP
# --port=8001 = usa puerto 8001 en lugar de 8000
```

**Si falla:**
- Puerto 8000 ocupado: Usa `--port=8001`
- Verifica PHP en PATH: `php --version`
- Intenta: `php -S localhost:8000 -t public`

---

## Verificación Final

```bash
# Terminal 1: npm run dev (Tailwind watch)
npm run dev

# Terminal 2: php artisan serve
php artisan serve

# Terminal 3: php artisan queue:work (Para procesar emails)
php artisan queue:work --timeout=60
```

**Accede a:**
- **App**: http://localhost:8000
- **Login**: http://localhost:8000/login
- **Admin**: usuario admin (si se ejecutó seeder)

---

## Usuarios de Prueba (Después de Seeders)

```
Email: admin@taller.com
Password: password

Email: tecnico@taller.com
Password: password

Email: cliente@taller.com
Password: password
```

---

## Solución de Problemas Comunes

### ❌ Error: "SQLSTATE[HY000]: General error"
```bash
# Solución: Verifica BD conexión
php artisan tinker
>>> DB::connection()->getPdo();
>>> exit
```

### ❌ Error: "Call to undefined function"
```bash
# Solución: Regenera autoloader
composer dump-autoload -o
php artisan cache:clear
```

### ❌ Error: "Port 8000 already in use"
```bash
# Solución: Usa otro puerto
php artisan serve --port=8001
```

### ❌ Error: "Node not found"
```bash
# Solución: Verifica Node.js instalado
node --version
# Debe ser 18+
```

---

## Próximos Pasos

1. ✅ Instalación completada
2. 📖 Lee [CONFIGURACION.md](./CONFIGURACION.md) - Ajusta variables de entorno
3. 🗄️ Lee [BASE_DATOS.md](./BASE_DATOS.md) - Entiende estructura de BD
4. 👥 Ve a [docs/roles/](../roles/) - Documentación por rol

---

**Última actualización:** Septiembre 2026  
**Versión:** 1.0.0
