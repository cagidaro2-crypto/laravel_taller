<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductoFoto extends Model
{
    protected $table = 'producto_fotos';

    protected $primaryKey = 'id_foto';

    protected $fillable = [
        'id_producto',
        'ruta_foto',
        'descripcion',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}
