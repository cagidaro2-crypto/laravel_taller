<?php

namespace App\Models\Tecnico;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Admin\EstadoOt;
use App\Models\Admin\Factura;
use App\Models\Admin\Usuario;

class OrdenTrabajo extends Model
{
    protected $table = 'ordenes_trabajo';

    protected $primaryKey = 'id_orden';

    protected $fillable = [
        'id_vehiculo',
        'id_estado',
        'id_usuario',
        'fecha_ingreso',
        'fecha_salida',
        'descripcion_problema',
        'diagnostico',
        'observaciones',
        'subtotal',
        'impuesto',
        'total',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'fecha_salida'  => 'date',
        'subtotal'      => 'decimal:2',
        'impuesto'      => 'decimal:2',
        'total'         => 'decimal:2',
    ];

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(EstadoOt::class, 'id_estado', 'id_estado');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function servicios(): HasMany
    {
        return $this->hasMany(OrdenServicio::class, 'id_orden', 'id_orden');
    }

    public function productos(): HasMany
    {
        return $this->hasMany(OrdenProducto::class, 'id_orden', 'id_orden');
    }

    public function factura(): HasOne
    {
        return $this->hasOne(Factura::class, 'id_orden', 'id_orden');
    }
}
