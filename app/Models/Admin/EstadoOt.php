<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Tecnico\OrdenTrabajo;

class EstadoOt extends Model
{
    protected $table = 'estados_ot';

    protected $primaryKey = 'id_estado';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function ordenes(): HasMany
    {
        return $this->hasMany(OrdenTrabajo::class, 'id_estado', 'id_estado');
    }
}
