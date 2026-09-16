<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Tecnico\OrdenProducto;

class Producto extends Model
{
    protected $table = 'productos';

    protected $primaryKey = 'id_producto';

    protected $fillable = [
        'id_categoria',
        'id_proveedor',
        'nombre',
        'codigo',
        'descripcion',
        'marca',
        'unidad_medida',
        'precio_compra',
        'precio_venta',
        'stock_minimo',
        'activo',
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta'  => 'decimal:2',
        'stock_minimo'  => 'integer',
        'activo'        => 'boolean',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaProducto::class, 'id_categoria', 'id_categoria');
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor');
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(ProductoFoto::class, 'id_producto', 'id_producto');
    }

    public function inventario(): HasOne
    {
        return $this->hasOne(Inventario::class, 'id_producto', 'id_producto');
    }

    public function ordenes(): HasMany
    {
        return $this->hasMany(OrdenProducto::class, 'id_producto', 'id_producto');
    }

    public function cotizaciones(): HasMany
    {
        return $this->hasMany(CotizacionProducto::class, 'id_producto', 'id_producto');
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(DetalleVenta::class, 'id_producto', 'id_producto');
    }
}
