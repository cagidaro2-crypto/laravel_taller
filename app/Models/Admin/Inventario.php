<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventario extends Model
{
    protected $table = 'inventario';

    protected $primaryKey = 'id_inventario';

    protected $fillable = [
        'id_producto',
        'cantidad',
        'stock_minimo',
        'ultima_actualizacion',
    ];

    protected $casts = [
        'cantidad'            => 'integer',
        'stock_minimo'        => 'integer',
        'ultima_actualizacion'=> 'datetime',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    public function tieneStockBajo(): bool
    {
        return $this->cantidad <= $this->stock_minimo;
    }
}
