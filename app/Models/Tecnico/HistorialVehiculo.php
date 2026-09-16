<?php

namespace App\Models\Tecnico;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialVehiculo extends Model
{
    protected $table = 'historial_vehiculo';

    protected $primaryKey = 'id_historial';

    protected $fillable = [
        'id_vehiculo',
        'fecha',
        'descripcion',
        'valor',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'date',
        'valor' => 'decimal:2',
    ];

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }
}
