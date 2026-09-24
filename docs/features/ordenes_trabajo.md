# 🔧 Órdenes de Trabajo - Documentación de Feature

## Resumen
Las órdenes de trabajo son el **núcleo del sistema**. Representan el trabajo que hace el técnico: reparar/mantener vehículos de clientes. Una orden conecta cliente → vehículo → servicios/productos → factura → pago.

**Definición**: Documento que especifica qué trabajo se debe hacer, quién lo hace (técnico), cuándo se hace, y qué cuesta.

---

## Ciclo de Vida de una Orden

```
1. CREAR ORDEN
   ├── Cliente solícita reparación (vía cita)
   ├── Admin crea orden manualmente
   └── Sistema asigna a técnico

   ↓

2. EN PROGRESO
   ├── Técnico recibe orden
   ├── Actualiza estado: "En Progreso"
   ├── Registra consumo de materiales
   └── Actualiza estado vehículo

   ↓

3. REVISAR TRABAJO
   ├── Técnico verifica trabajo completado
   ├── Actualiza estado: "Terminado"
   └── Actualiza estado vehículo: "Listo"

   ↓

4. GENERAR FACTURA
   ├── Admin o Técnico: Click "Generar Factura"
   ├── Sistema crea Factura automáticamente
   ├── Calcula subtotal (servicios + productos)
   ├── Aplica impuesto
   └── Total = Subtotal + Impuesto

   ↓

5. CLIENTE PAGA
   ├── Cliente recibe factura
   ├── Cliente: Click "Marcar como Pagada"
   ├── Sistema registra pago
   ├── Descarga PDF para archivo

   ↓

6. ENTREGADO
   ├── Vehículo se entrega al cliente
   ├── Estado vehículo: "Entregado"
   └── Orden CERRADA ✓
```

---

## Estructura de Datos

### Tabla `ordenes_trabajo`

```sql
CREATE TABLE ordenes_trabajo (
    id_orden INT PRIMARY KEY AUTO_INCREMENT,
    id_vehiculo INT NOT NULL,      -- FK a vehiculos (qué se repara)
    id_usuario INT NOT NULL,       -- FK a usuarios (técnico asignado)
    id_estado INT NOT NULL,        -- FK a estados_ot (En Progreso, Terminado, etc.)
    descripcion TEXT,              -- Qué se debe hacer
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_estimada DATE,           -- Cuándo estará listo
    fecha_entrega DATE,            -- Cuándo se entregó
    activo BOOLEAN DEFAULT 1,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Relaciones

```
OrdenTrabajo
├── belongsTo Vehiculo       (id_vehiculo → id_vehiculo)
├── belongsTo Usuario        (id_usuario → id_usuario) [técnico]
├── belongsTo EstadoOt       (id_estado → id_estado)
├── belongsToMany Servicio   (orden_servicios tabla pivote)
├── belongsToMany Producto   (orden_productos tabla pivote)
├── hasMany ConsumoMaterial  (registra qué se gastó)
├── hasOne Factura           (cuando se termina)
└── hasMany HistorialVehiculo (registro de cambios)
```

---

## Estados Posibles

```sql
SELECT * FROM estados_ot;

id_estado | nombre
----------|----------
    1     | Recibido
    2     | En Progreso
    3     | Terminado
    4     | Entregado
    5     | Cancelado
```

### Significado de cada Estado

| Estado | Significa | Técnico | Admin | Cliente |
|--------|-----------|---------|-------|---------|
| **Recibido** | Orden creada, espera asignación | ❌ No ve | ✅ Puede asignar | ❓ No ve |
| **En Progreso** | Técnico trabajando | ✅ Actualiza progreso | ✅ Monitorea | ✅ Ve "en reparación" |
| **Terminado** | Trabajo listo, espera pago | ✅ Genera factura | ✅ Revisión | ✅ Recibe factura |
| **Entregado** | Cliente recibió vehículo | ✅ Cierra | ✅ Archiva | ✅ Descarga recibo |
| **Cancelado** | Cancelada (cliente cambió idea) | ❌ No puede | ✅ Cancela | ✅ Ve cancelada |

---

## Flujo Detallado: Vista de Técnico

### 1. Ver Mis Órdenes
```
GET /tecnico/ordenes
├── Query: OrdenTrabajo where id_usuario = AUTH_USER
├── Eager load: vehiculo.cliente, estado, servicios, productos
└── Retorna: Lista de órdenes + estado actual
```

**Qué ve técnico:**
```
Orden #5 - Toyota Corolla de Juan Pérez
├── Estado: En Progreso
├── Asignada: Hoy a las 09:00
├── Vehículo: Toyota 2015, Placa ABC123
├── Servicios: Cambio de aceite, Revisión frenos
├── Productos usados: Aceite Premium (1L), Pastillas frenos (1 set)
├── Botones: [Actualizar Estado] [Ver Consumo Material] [Generar Factura]
```

### 2. Ver Detalle de Orden
```
GET /tecnico/ordenes/{ordene}
├── Carga: Orden con todas relaciones
├── Muestra: Descripción completa
└── Opciones: Editar estado, Ver/Agregar consumo material
```

### 3. Actualizar Estado
```
PATCH /tecnico/ordenes/{ordene}/estado
Body: { estado: "Terminado" }

Proceso:
1. Valida: ¿Nuevo estado es válido?
2. Carga: Estado anterior de BD
3. Actualiza: Orden.id_estado = nuevo
4. Notifica: Envía email al cliente
5. Registra: Historial de cambio
6. Retorna: Redirect con éxito
```

**Email que recibe cliente:**
```
De: taller@system.com
Para: juan@mail.com
Asunto: Tu vehículo está listo

Hola Juan,

Tu Toyota Corolla (Placa ABC123) está terminada y lista para retirar.
Factura: #2024-00005
Total: $450.00

Haz clic aquí para ver: [Link a factura]

¡Gracias!
```

### 4. Registrar Consumo de Materiales
```
GET /tecnico/ordenes/{ordene}/materiales
├── Muestra: Formulario + lista actual
├── Campos: [Producto dropdown] [Cantidad] [Botón Agregar]
└── Lista: Materiales ya consumidos

POST /tecnico/ordenes/{ordene}/materiales
Body: { id_producto: 5, cantidad: 2 }

Proceso:
1. Valida: ¿Producto existe?
2. Valida: ¿Hay stock?
3. Crear: ConsumoMaterial record
4. Actualizar: Inventario.cantidad -= 2
5. Registrar: AuditoriaInventario (para auditoría)
6. Retorna: JSON con nueva lista
```

**Datos creados:**
```
consumo_materiales
├── id_consumo: 15 (nuevo)
├── id_orden: 5
├── id_producto: 5 (Aceite Premium)
├── cantidad: 2
├── precio_unitario: 25.00
├── subtotal: 50.00 (2 × 25)
└── created_at: 2026-09-24 14:30:00

inventario
├── id_inventario: 5
├── cantidad: 48 (antes 50, menos 2 usados)
└── updated_at: 2026-09-24 14:30:00
```

### 5. Generar Factura
```
POST /tecnico/ordenes/{ordene}/factura
Body: {} (vacío, todo automático)

Proceso en Transacción:
1. Valida: ¿Orden está Terminada?
2. Calcula: Subtotal
   ├── Servicios: Sum(precio)
   └── Productos: Sum(cantidad × precio_unitario)
3. Calcula: Subtotal = Servicios + Productos
4. Calcula: Impuesto = Subtotal × 0.13 (13% IVA)
5. Calcula: Total = Subtotal + Impuesto
6. Crear: Factura record
7. Crear: Pago record (pendiente)
8. Actualizar: Orden.estado = "Facturada"
9. Retorna: Redirect a ver factura
```

**Factura creada:**
```
facturas
├── id_factura: 100
├── numero_factura: "2024-00005"
├── id_cliente: 3 (extraído de vehiculo.cliente)
├── id_orden: 5
├── subtotal: 450.00
├── impuesto: 58.50 (13%)
├── total: 508.50
├── estado: "Pendiente"
├── fecha_emision: 2026-09-24
└── created_at: 2026-09-24 14:45:00
```

---

## Flujo Detallado: Vista de Cliente

### 1. Cliente Recibe Notificación
- Email automático cuando orden → "Terminado"
- Link: "Ver mi factura"

### 2. Cliente Accede a Factura
```
GET /cliente/facturas/{factura}
├── Verifica: ¿Factura es del cliente?
├── Muestra: Detalle de factura
├── Botones: [Descargar PDF] [Marcar como Pagada]
```

### 3. Cliente Marca como Pagada
```
POST /cliente/facturas/{factura}/marcar-pagada
Body: {} (vacío)

Proceso:
1. Valida: ¿Factura pertenece a cliente autenticado?
2. Valida: ¿No está ya pagada?
3. Actualizar: Factura.estado = "Pagada"
4. Crear: Pago record
   ├── id_factura: 100
   ├── monto: 508.50 (total de factura)
   ├── fecha_pago: NOW()
   └── metodo: "Online"
5. Retorna: JSON con datos actualizados
6. JavaScript actualiza página sin recargar
```

---

## Arquitectura de Código

### OrdenTrabajoController (Técnico)

```php
class OrdenTrabajoController extends Controller
{
    // GET /tecnico/ordenes
    public function index(Request $request)
    {
        $ordenes = OrdenTrabajo::where('id_usuario', auth()->user()->id_usuario)
            ->with(['vehiculo.cliente.usuario', 'estado', 'servicios', 'productos'])
            ->paginate(15);
        
        return view('tecnico.ordenes.index', compact('ordenes'));
    }

    // GET /tecnico/ordenes/{ordene}
    public function show(OrdenTrabajo $ordene)
    {
        return view('tecnico.ordenes.show', compact('ordene'));
    }

    // PATCH /tecnico/ordenes/{ordene}/estado
    public function actualizarEstado(Request $request, OrdenTrabajo $ordene)
    {
        $validated = $request->validate([
            'estado' => 'required|exists:estados_ot,id_estado'
        ]);

        $estadoAnterior = $ordene->estado->nombre;
        $ordene->update(['id_estado' => $validated['estado']]);
        $estadoNuevo = $ordene->estado()->find($validated['estado'])->nombre;

        // Notificar cliente
        $this->notificarCambioEstado($ordene, $estadoAnterior, $estadoNuevo);

        return redirect()->back()->with('success', 'Estado actualizado');
    }

    // POST /tecnico/ordenes/{ordene}/factura
    public function generarFactura(OrdenTrabajo $ordene)
    {
        DB::transaction(function () use ($ordene) {
            $subtotal = $ordene->servicios->sum('precio')
                      + $ordene->consumoMateriales->sum('subtotal');
            
            $impuesto = $subtotal * 0.13;
            $total = $subtotal + $impuesto;

            $factura = Factura::create([
                'numero_factura' => Factura::generateNumber(),
                'id_cliente' => $ordene->vehiculo->cliente->id_usuario,
                'id_orden' => $ordene->id_orden,
                'subtotal' => $subtotal,
                'impuesto' => $impuesto,
                'total' => $total,
                'estado' => 'Pendiente',
            ]);

            return $factura;
        });

        return redirect()->route('tecnico.facturas.show', $factura);
    }

    private function notificarCambioEstado($ordene, $estadoAnterior, $estadoNuevo)
    {
        $cliente = $ordene->vehiculo->cliente->usuario;
        Mail::to($cliente->correo)->send(
            new NotificacionEstadoVehiculoMail($ordene, $estadoAnterior, $estadoNuevo)
        );
    }
}
```

### Modelo OrdenTrabajo

```php
class OrdenTrabajo extends Model
{
    protected $table = 'ordenes_trabajo';
    protected $primaryKey = 'id_orden';
    
    protected $fillable = [
        'id_vehiculo',
        'id_usuario',
        'id_estado',
        'descripcion',
        'fecha_estimada',
        'fecha_entrega',
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoOt::class, 'id_estado', 'id_estado');
    }

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'orden_servicios', 
            'id_orden', 'id_servicio', 'id_orden', 'id_servicio');
    }

    public function consumoMateriales()
    {
        return $this->hasMany(ConsumoMaterial::class, 'id_orden', 'id_orden');
    }
}
```

---

## Errores Comunes y Soluciones

### ❌ Error: "SQLSTATE[23000]: Integrity constraint violation"
```
Causa: Técnico intenta marcar orden como pagada sin factura
Solución: Generar factura primero (crear factura antes de pago)
```

### ❌ Error: "Trying to get property of non-object"
```
Causa: Relación no cargada con `with()`
Solución:
  // MAL:
  $orden = OrdenTrabajo::find(5);
  echo $orden->vehiculo->cliente->nombre;  // Error si no eager loaded

  // BIEN:
  $orden = OrdenTrabajo::with(['vehiculo.cliente'])->find(5);
  echo $orden->vehiculo->cliente->nombre;  // OK
```

### ❌ Error: "No hay stock disponible"
```
Causa: Inventario insuficiente al registrar consumo
Solución:
1. Verificar cantidad en inventario
2. Comprar más producto al proveedor
3. Registrar nueva compra en BD
4. Reintentar consumo
```

---

## Consultas SQL Importantes

### Órdenes en progreso hoy
```sql
SELECT * FROM ordenes_trabajo
WHERE id_estado = 2  -- En Progreso
  AND DATE(updated_at) = CURDATE()
ORDER BY updated_at DESC;
```

### Órdenes sin facturar
```sql
SELECT o.* FROM ordenes_trabajo o
LEFT JOIN facturas f ON o.id_orden = f.id_orden
WHERE o.id_estado = 3  -- Terminado
  AND f.id_factura IS NULL;  -- Sin factura aún
```

### Consumo de materiales por orden
```sql
SELECT 
    cm.id_consumo,
    p.nombre,
    cm.cantidad,
    p.precio_venta,
    (cm.cantidad * p.precio_venta) as subtotal
FROM consumo_materiales cm
JOIN productos p ON cm.id_producto = p.id_producto
WHERE cm.id_orden = 5
ORDER BY cm.created_at DESC;
```

### Ingresos por orden
```sql
SELECT 
    o.id_orden,
    v.placa,
    c.documento,
    f.total,
    f.estado
FROM ordenes_trabajo o
JOIN vehiculos v ON o.id_vehiculo = v.id_vehiculo
JOIN clientes c ON v.id_cliente = c.id_usuario
LEFT JOIN facturas f ON o.id_orden = f.id_orden
WHERE o.id_estado = 4  -- Entregado
ORDER BY o.fecha_entrega DESC;
```

---

## Mantenimiento y Mejoras

### Agregar nuevo estado
```bash
# 1. En migración:
INSERT INTO estados_ot (nombre) VALUES ('En Espera de Repuesto');

# 2. En flujo:
- Cliente espera más de 3 días → "En Espera de Repuesto"
- Cuando llega repuesto → "En Progreso" nuevamente
```

### Agregar notificaciones SMS
```php
// Agregar a notificarCambioEstado():
$cliente = $ordene->vehiculo->cliente->usuario;
Twilio::sendSMS($cliente->telefono, "Tu orden está lista!");
```

---

**Última actualización**: Septiembre 2026  
**Versión**: 1.0.0  
**Mantenedor**: Sistema de Taller Latonería
