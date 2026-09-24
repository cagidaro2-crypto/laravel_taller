<?php

namespace App\Models\Tecnico;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Admin\Usuario;

class HistorialVehiculo extends Model
{
    protected $table = 'historial_vehiculo';

    protected $primaryKey = 'id_historial';

    protected $fillable = [
        'id_vehiculo',
        'id_usuario',
        'fecha',
        'descripcion',
        'valor',
        'estado',
        'estado_anterior',
        'estado_nuevo',
    ];

    protected $casts = [
        'fecha' => 'date',
        'valor' => 'decimal:2',
    ];

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
