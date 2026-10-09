<?php

namespace App\Models\Tecnico;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Cliente\Cliente;
use App\Models\Admin\Cotizacion;

class Vehiculo extends Model
{
    protected $table = 'vehiculos';

    protected $primaryKey = 'id_vehiculo';

    protected $fillable = [
        'id_cliente',
        'id_estado',
        'placa',
        'marca',
        'modelo',
        'anio',
        'color',
        'tipo',
        'vin',
        'observaciones',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(EstadoVehiculo::class, 'id_estado', 'id_estado');
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(VehiculoFoto::class, 'id_vehiculo', 'id_vehiculo');
    }

    public function historial(): HasMany
    {
        return $this->hasMany(HistorialVehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }

    public function ordenesTrabajo(): HasMany
    {
        return $this->hasMany(OrdenTrabajo::class, 'id_vehiculo', 'id_vehiculo');
    }

    public function cotizaciones(): HasMany
    {
        return $this->hasMany(Cotizacion::class, 'id_vehiculo', 'id_vehiculo');
    }

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class, 'id_vehiculo', 'id_vehiculo');
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(\App\Models\Admin\Venta::class, 'id_vehiculo', 'id_vehiculo');
    }

    /**
     * Sincroniza las compras (ventas de repuestos/productos) con el vehículo y su historial en tiempo real.
     */
    public function syncCompras(): void
    {
        // 1. Si el cliente tiene ventas sin id_vehiculo y es su único vehículo, asociarlas automáticamente
        $totalVehiculos = self::where('id_cliente', $this->id_cliente)->count();
        if ($totalVehiculos === 1) {
            \App\Models\Admin\Venta::where('id_cliente', $this->id_cliente)
                ->whereNull('id_vehiculo')
                ->update(['id_vehiculo' => $this->id_vehiculo]);
        }

        // 2. Para cada venta asociada al vehículo, sincronizar con el historial
        $ventas = $this->ventas()->with(['detalles.producto', 'usuario'])->get();
        foreach ($ventas as $venta) {
            $existe = $this->historial()
                ->where(function ($q) use ($venta) {
                    $q->where('descripcion', 'like', "%Venta #{$venta->id_venta}%")
                      ->orWhere(function ($sub) use ($venta) {
                          $sub->whereDate('fecha', $venta->fecha)
                              ->where('valor', $venta->total);
                      });
                })
                ->exists();

            if (!$existe) {
                $nombres = $venta->detalles->map(function ($d) {
                    $nombre = $d->producto?->nombre ?? 'Producto';
                    return "{$nombre} (x{$d->cantidad})";
                })->filter()->join(', ');

                $this->historial()->create([
                    'id_usuario'      => $venta->id_usuario,
                    'fecha'           => $venta->fecha,
                    'descripcion'     => 'Compra de repuestos/productos: ' . ($nombres ?: "Venta #{$venta->id_venta}"),
                    'valor'           => $venta->total,
                    'estado'          => $venta->estado ?? 'Completada',
                    'estado_anterior' => $this->estado?->nombre_estado ?? 'Activo',
                    'estado_nuevo'    => $this->estado?->nombre_estado ?? 'Activo',
                ]);
            }
        }

        // 3. Corregir registros con 'Desconocido' o 'Actual' en el historial de este vehículo
        $estadoReal = $this->estado?->nombre_estado ?? 'Ingresado';
        $this->historial()
            ->where(function ($q) {
                $q->where('estado_anterior', 'Desconocido')
                  ->orWhere('estado_nuevo', 'Actual');
            })
            ->get()
            ->each(function ($h) use ($estadoReal) {
                $updates = [];
                if ($h->estado_anterior === 'Desconocido') {
                    $updates['estado_anterior'] = $estadoReal;
                }
                if ($h->estado_nuevo === 'Actual') {
                    $updates['estado_nuevo'] = $estadoReal;
                }
                if (!empty($updates)) {
                    $h->update($updates);
                }
            });

        // 4. Si hay órdenes de trabajo y registros de cambio de estado con valor 0, sincronizar el valor real
        $ultimaOrden = $this->ordenesTrabajo()->latest('fecha_ingreso')->first();
        if ($ultimaOrden && $ultimaOrden->total > 0) {
            $this->historial()
                ->where('descripcion', 'Cambio de estado de vehículo')
                ->where('valor', 0)
                ->whereDate('fecha', $ultimaOrden->fecha_ingreso ?? $ultimaOrden->created_at)
                ->update(['valor' => $ultimaOrden->total]);
        }
    }
}
