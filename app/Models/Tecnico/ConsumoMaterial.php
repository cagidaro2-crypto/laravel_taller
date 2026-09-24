<?php

namespace App\Models\Tecnico;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Admin\Producto;

class ConsumoMaterial extends Model
{
    protected $table = 'consumo_materiales';

    protected $primaryKey = 'id_consumo';

    protected $fillable = [
        'id_orden',
        'id_producto',
        'cantidad_usada',
        'precio_unitario',
        'subtotal',
        'observaciones',
        'fecha_consumo',
    ];

    protected $casts = [
        'cantidad_usada'   => 'integer',
        'precio_unitario'  => 'decimal:2',
        'subtotal'         => 'decimal:2',
        'fecha_consumo'    => 'datetime',
    ];

    public function orden(): BelongsTo
    {
        return $this->belongsTo(OrdenTrabajo::class, 'id_orden', 'id_orden');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}
