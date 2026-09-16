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
}
