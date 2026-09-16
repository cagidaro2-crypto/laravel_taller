<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Tecnico\OrdenServicio;

class Servicio extends Model
{
    protected $table = 'servicios';

    protected $primaryKey = 'id_servicio';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio_base',
        'activo',
    ];

    protected $casts = [
        'precio_base' => 'decimal:2',
        'activo'      => 'boolean',
    ];

    public function ordenes(): HasMany
    {
        return $this->hasMany(OrdenServicio::class, 'id_servicio', 'id_servicio');
    }

    public function cotizaciones(): HasMany
    {
        return $this->hasMany(CotizacionServicio::class, 'id_servicio', 'id_servicio');
    }
}
