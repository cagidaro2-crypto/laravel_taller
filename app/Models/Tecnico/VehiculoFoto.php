<?php

namespace App\Models\Tecnico;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehiculoFoto extends Model
{
    protected $table = 'vehiculo_fotos';

    protected $primaryKey = 'id_foto';

    protected $fillable = [
        'id_vehiculo',
        'ruta_foto',
        'descripcion',
    ];

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }
}
