# 💰 Facturas - Documentación de Feature

## Resumen
Las facturas son documentos legales que registran el trabajo realizado y los montos a pagar. Se generan automáticamente desde órdenes terminadas. Cliente puede ver, descargar PDF, y marcar como pagadas.

**Definición**: Comprobante que especifica servicios/productos prestados, cantidad, precio unitario, total sin impuesto, impuesto, y total a pagar.

---

## Ciclo de Vida

```
ORDEN COMPLETADA
    ↓
    Técnico/Admin click "Generar Factura"
    ↓
FACTURA CREADA (automática)
    ├── Número único: 2024-00001
    ├── Subtotal: $450.00
    ├── Impuesto (13%): $58.50
    └── Total: $508.50
    
    ↓
    Cliente recibe notificación por email
    ↓
CLIENT ACCEDE A FACTURA
    ├── Ve detalle completo
    ├── Descarga PDF
    └── Click "Marcar Pagada"
    
    ↓
REGISTRO DE PAGO
    ├── Método: Online/Efectivo/Tarjeta
    ├── Monto: $508.50
    └── Fecha: 2026-09-24
    
    ↓
FACTURA PAGADA ✓
    └── Cliente recibe comprobante
```

---

## Estructura de Datos

### Tabla `facturas`

```sql
CREATE TABLE facturas (
    id_factura INT PRIMARY KEY AUTO_INCREMENT,
    numero_factura VARCHAR(50) UNIQUE,     -- "2024-00001"
    id_cliente INT NOT NULL,               -- FK a usuarios (cliente)
    id_orden INT,                          -- FK a ordenes_trabajo (nullable)
    estado VARCHAR(50) DEFAULT 'Pendiente', -- Pendiente, Pagada, Anulada
    subtotal DECIMAL(10,2),                -- Servicios + Productos (sin impuesto)
    impuesto DECIMAL(10,2),                -- 13% del subtotal
    total DECIMAL(10,2),                   -- Subtotal + Impuesto
    fecha_emision DATE,
    fecha_vencimiento DATE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Tabla `pagos`

```sql
CREATE TABLE pagos (
    id_pago INT PRIMARY KEY AUTO_INCREMENT,
    id_factura INT NOT NULL,               -- FK a facturas
    monto DECIMAL(10,2),                   -- Monto pagado
    metodo_pago VARCHAR(50),               -- 'Online', 'Efectivo', 'Tarjeta'
    referencia VARCHAR(100),               -- Comprobante/referencia
    fecha_pago TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (id_factura) REFERENCES facturas(id_factura)
);
```

### Relaciones

```
Factura
├── belongsTo Usuario      (id_cliente → id_usuario) [cliente]
├── belongsTo OrdenTrabajo (id_orden → id_orden)
└── hasMany Pago          (pagos de esta factura)

Pago
└── belongsTo Factura     (id_factura → id_factura)
```

---

## Estados de Factura

| Estado | Significa | Puede Cambiar a | Acciones |
|--------|-----------|-----------------|----------|
| **Pendiente** | Emitida, espera pago | Pagada, Anulada | Marcar pagada, Anular |
| **Pagada** | Pago recibido | Anulada | Descargar comprobante |
| **Anulada** | Cancelada (error, etc.) | - | Solo ver historial |

---

## Cálculo de Totales

### Ejemplo Real

```
ORDEN #5 - Reparación Toyota Corolla

SERVICIOS:
├── Cambio de aceite:  $45.00
├── Revisión frenos:   $60.00
└── Lavado:           $30.00
TOTAL SERVICIOS:     $135.00

PRODUCTOS (consumo de materiales):
├── Aceite Premium (2L):     $50.00 (2 × $25)
├── Pastillas frenos:        $120.00 (1 set)
└── Filtro de aire:          $45.00
TOTAL PRODUCTOS:           $215.00

SUBTOTAL:  $135.00 + $215.00 = $350.00
IMPUESTO:  $350.00 × 0.13    =  $45.50
TOTAL:     $350.00 + $45.50  = $395.50
```

### Fórmulas

```
Subtotal = Σ(servicios.precio) + Σ(consumo_materiales.cantidad × producto.precio)
Impuesto = Subtotal × 0.13 (13% IVA)
Total = Subtotal + Impuesto
```

---

## Flujo de Creación Automática

### Cuándo se Crea

```
Admin/Técnico click: "Generar Factura" en orden terminada
    ↓
POST /admin/ordenes/{ordene}/factura
    ↓
OrdenTrabajoController@generarFactura()
```

### Código del Controlador

```php
public function generarFactura(OrdenTrabajo $ordene)
{
    // Transacción para garantizar consistencia
    DB::transaction(function () use ($ordene) {
        // 1. Validar que orden esté terminada
        if ($ordene->estado->nombre !== 'Terminado') {
            abort(400, 'Orden debe estar terminada');
        }

        // 2. Calcular subtotal desde servicios
        $subtotalServicios = $ordene->servicios()
            ->sum('servicios.precio');

        // 3. Calcular subtotal desde consumo materiales
        $subtotalProductos = $ordene->consumoMateriales()
            ->join('productos', 'consumo_materiales.id_producto', 'productos.id_producto')
            ->sum(DB::raw('consumo_materiales.cantidad * productos.precio_venta'));

        // 4. Calcular totales
        $subtotal = $subtotalServicios + $subtotalProductos;
        $impuesto = $subtotal * 0.13;
        $total = $subtotal + $impuesto;

        // 5. Generar número único
        $numeroFactura = $this->generarNumeroFactura();

        // 6. Crear factura
        $factura = Factura::create([
            'numero_factura' => $numeroFactura,
            'id_cliente' => $ordene->vehiculo->cliente->id_usuario,
            'id_orden' => $ordene->id_orden,
            'estado' => 'Pendiente',
            'subtotal' => $subtotal,
            'impuesto' => $impuesto,
            'total' => $total,
            'fecha_emision' => now()->toDateString(),
            'fecha_vencimiento' => now()->addDays(15)->toDateString(),  // Vence en 15 días
        ]);

        // 7. Crear registro de pago inicial (pendiente)
        Pago::create([
            'id_factura' => $factura->id_factura,
            'monto' => 0,
            'metodo_pago' => 'Pendiente',
            'referencia' => 'Factura emitida',
        ]);

        // 8. Enviar notificación al cliente
        Mail::to($ordene->vehiculo->cliente->usuario->correo)
            ->send(new FacturaEmitidaMail($factura));

        return $factura;
    });

    return redirect()->route('admin.facturas.show', $factura)
        ->with('success', 'Factura generada exitosamente');
}

private function generarNumeroFactura()
{
    $ano = date('Y');
    $ultimaFactura = Factura::whereYear('created_at', $ano)
        ->orderBy('id_factura', 'desc')
        ->first();
    
    $numero = $ultimaFactura ? $ultimaFactura->numero + 1 : 1;
    return "$ano-" . str_pad($numero, 5, '0', STR_PAD_LEFT);
    // Resultado: "2024-00001", "2024-00002", etc.
}
```

---

## Flujo: Cliente Marca como Pagada

### Interfaz

```html
<!-- resources/views/cliente/facturas/show.blade.php -->

<div class="factura">
    <h2>Factura #{{ $factura->numero_factura }}</h2>
    
    <div class="totales">
        <p>Subtotal: ${{ $factura->subtotal }}</p>
        <p>Impuesto (13%): ${{ $factura->impuesto }}</p>
        <h3>TOTAL A PAGAR: ${{ $factura->total }}</h3>
    </div>

    @if ($factura->estado === 'Pendiente')
        <button id="btn-pagar" class="btn-primary">
            Marcar como Pagada
        </button>
    @else
        <p class="estado-pagada">✓ Factura Pagada</p>
    @endif
</div>

<script>
document.getElementById('btn-pagar').addEventListener('click', function() {
    if (!confirm('¿Confirmar pago de ${{ $factura->total }}?')) return;
    
    fetch(`/cliente/facturas/{{ $factura->id_factura }}/marcar-pagada`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('[name=csrf-token]').value,
            'Content-Type': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('¡Pago registrado!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(e => alert('Error de conexión'));
});
</script>
```

### Código del Controlador

```php
// app/Http/Controllers/Cliente/FacturaController.php

public function marcarPagada(Factura $factura)
{
    // Validar que factura pertenece a cliente autenticado
    if ($factura->id_cliente !== auth()->user()->id_usuario) {
        abort(403, 'No autorizado');
    }

    // Validar que no esté ya pagada
    if ($factura->estado === 'Pagada') {
        return response()->json([
            'success' => false,
            'message' => 'Factura ya está pagada'
        ], 400);
    }

    DB::transaction(function () use ($factura) {
        // 1. Actualizar estado de factura
        $factura->update([
            'estado' => 'Pagada',
            'updated_at' => now()
        ]);

        // 2. Crear registro de pago
        Pago::create([
            'id_factura' => $factura->id_factura,
            'monto' => $factura->total,
            'metodo_pago' => 'Online',
            'referencia' => 'Pago cliente: ' . now()->timestamp,
            'fecha_pago' => now()
        ]);

        // 3. Registrar en auditoría (si aplica)
        Log::info("Factura pagada", [
            'id_factura' => $factura->id_factura,
            'id_cliente' => auth()->user()->id_usuario,
            'monto' => $factura->total,
            'timestamp' => now()
        ]);

        // 4. Enviar confirmación por email
        Mail::to($factura->cliente->usuario->correo)
            ->send(new ConfirmacionPagoMail($factura));
    });

    return response()->json([
        'success' => true,
        'message' => 'Pago registrado',
        'estado' => 'Pagada',
        'total_pagado' => $factura->total
    ]);
}
```

---

## Descarga de PDF

### Ruta
```
GET /cliente/facturas/{factura}/pdf
GET /admin/facturas/{factura}/pdf
GET /tecnico/facturas/{factura}/pdf
```

### Código

```php
public function pdf(Factura $factura)
{
    // Validar permisos
    if (auth()->user()->id_usuario !== $factura->id_cliente 
        && auth()->user()->rol->nombre_rol !== 'Administrador') {
        abort(403);
    }

    // Cargar datos
    $factura->load('cliente.usuario', 'orden.vehiculo');

    // Generar PDF
    $pdf = Pdf::loadView('facturas.pdf', [
        'factura' => $factura,
        'empresa' => [
            'nombre' => 'Taller Latonería',
            'ruc' => '1234567890001',
            'direccion' => 'Calle 123, Ciudad',
            'telefono' => '555-1234'
        ]
    ]);

    return $pdf->download("factura_" . $factura->numero_factura . ".pdf");
}
```

### Template PDF

```html
<!-- resources/views/facturas/pdf.blade.php -->
<style>
    body { font-family: Arial; margin: 20px; }
    .header { text-align: center; margin-bottom: 30px; }
    .empresa { font-weight: bold; font-size: 18px; }
    .factura-numero { text-align: right; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th { background: #f0f0f0; padding: 8px; text-align: left; border-bottom: 2px solid #000; }
    td { padding: 8px; border-bottom: 1px solid #ddd; }
    .totales { text-align: right; }
    .total-final { font-weight: bold; font-size: 16px; background: #f0f0f0; padding: 10px; }
</style>

<div class="header">
    <div class="empresa">{{ $empresa['nombre'] }}</div>
    <div>RUC: {{ $empresa['ruc'] }}</div>
    <div>{{ $empresa['direccion'] }}</div>
    <div>Tel: {{ $empresa['telefono'] }}</div>
</div>

<div class="factura-numero">
    <strong>Factura: {{ $factura->numero_factura }}</strong>
    <div>Fecha: {{ $factura->fecha_emision->format('d/m/Y') }}</div>
</div>

<h3>CLIENTE</h3>
<div>
    Nombre: {{ $factura->cliente->usuario->nombre }}<br>
    Documento: {{ $factura->cliente->documento }}<br>
    Correo: {{ $factura->cliente->usuario->correo }}
</div>

<h3>DETALLES</h3>
<table>
    <thead>
        <tr>
            <th>Descripción</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($factura->orden->servicios as $servicio)
            <tr>
                <td>{{ $servicio->nombre }}</td>
                <td>1</td>
                <td>${{ number_format($servicio->precio, 2) }}</td>
                <td>${{ number_format($servicio->precio, 2) }}</td>
            </tr>
        @endforeach
        
        @foreach ($factura->orden->consumoMateriales as $consumo)
            <tr>
                <td>{{ $consumo->producto->nombre }}</td>
                <td>{{ $consumo->cantidad }}</td>
                <td>${{ number_format($consumo->producto->precio_venta, 2) }}</td>
                <td>${{ number_format($consumo->cantidad * $consumo->producto->precio_venta, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="totales">
    <div>Subtotal: ${{ number_format($factura->subtotal, 2) }}</div>
    <div>Impuesto (13%): ${{ number_format($factura->impuesto, 2) }}</div>
    <div class="total-final">
        TOTAL A PAGAR: ${{ number_format($factura->total, 2) }}
    </div>
</div>

<div style="margin-top: 30px; border-top: 1px solid #ddd; padding-top: 10px;">
    <strong>Estado de Pago:</strong> 
    @if ($factura->estado === 'Pagada')
        <span style="color: green;">✓ PAGADA</span>
    @else
        <span style="color: red;">⊗ PENDIENTE</span>
    @endif
</div>
```

---

## Reportes y Consultas

### Facturación del Mes

```sql
SELECT 
    DATE_TRUNC('day', f.fecha_emision) as fecha,
    COUNT(*) as cantidad,
    SUM(f.total) as ingresos
FROM facturas f
WHERE MONTH(f.fecha_emision) = MONTH(NOW())
  AND YEAR(f.fecha_emision) = YEAR(NOW())
GROUP BY DATE_TRUNC('day', f.fecha_emision)
ORDER BY fecha DESC;
```

### Facturas Pendientes de Pago

```sql
SELECT 
    f.numero_factura,
    f.total,
    DATEDIFF(NOW(), f.fecha_emision) as dias_vencida,
    c.documento,
    u.nombre
FROM facturas f
JOIN clientes c ON f.id_cliente = c.id_usuario
JOIN usuarios u ON c.id_usuario = u.id_usuario
WHERE f.estado = 'Pendiente'
  AND f.fecha_vencimiento < NOW()
ORDER BY f.fecha_vencimiento ASC;
```

### Facturación por Cliente

```sql
SELECT 
    c.documento,
    u.nombre,
    COUNT(f.id_factura) as cantidad,
    SUM(f.total) as monto_total
FROM facturas f
JOIN clientes c ON f.id_cliente = c.id_usuario
JOIN usuarios u ON c.id_usuario = u.id_usuario
GROUP BY c.id_usuario, u.nombre, c.documento
ORDER BY SUM(f.total) DESC;
```

---

## Errores Comunes

### ❌ Error: "Cannot generate factura, orden no terminada"
```
Solución: Técnico debe actualizar estado orden a "Terminado" primero
```

### ❌ Error: "Duplicate entry for numero_factura"
```
Solución: Asegurar que generateNumeroFactura() es único y atómico
```

### ❌ Error: "Cliente no puede ver esta factura"
```
Solución: Validar que factura.id_cliente == auth()->user()->id_usuario
```

---

**Última actualización**: Septiembre 2026  
**Versión**: 1.0.0  
**Mantenedor**: Sistema de Taller Latonería
