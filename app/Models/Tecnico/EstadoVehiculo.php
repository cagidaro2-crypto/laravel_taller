<?php

namespace App\Models\Tecnico;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoVehiculo extends Model
{
    protected $table = 'estado_vehiculo';

    protected $primaryKey = 'id_estado';

    protected $fillable = [
        'nombre_estado',
        'descripcion',
    ];

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class, 'id_estado', 'id_estado');
    }
}
