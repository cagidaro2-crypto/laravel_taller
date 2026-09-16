<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Cliente\Cliente;
use App\Models\Tecnico\Vehiculo;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';

    protected $primaryKey = 'id_cotizacion';

    protected $fillable = [
        'id_cliente',
        'id_vehiculo',
        'id_usuario',
        'fecha',
        'fecha_vencimiento',
        'estado',
        'subtotal',
        'impuesto',
        'total',
        'observaciones',
    ];

    protected $casts = [
        'fecha'            => 'date',
        'fecha_vencimiento'=> 'date',
        'subtotal'         => 'decimal:2',
        'impuesto'         => 'decimal:2',
        'total'            => 'decimal:2',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function servicios(): HasMany
    {
        return $this->hasMany(CotizacionServicio::class, 'id_cotizacion', 'id_cotizacion');
    }

    public function productos(): HasMany
    {
        return $this->hasMany(CotizacionProducto::class, 'id_cotizacion', 'id_cotizacion');
    }
}
