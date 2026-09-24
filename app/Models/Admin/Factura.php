<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Cliente\Cliente;
use App\Models\Tecnico\OrdenTrabajo;

class Factura extends Model
{
    protected $table = 'facturas';

    protected $primaryKey = 'id_factura';

    protected $fillable = [
        'id_cliente',
        'id_orden',
        'id_cotizacion',
        'numero_factura',
        'fecha',
        'subtotal',
        'impuesto',
        'total',
        'estado',
    ];

    protected $casts = [
        'fecha'    => 'date',
        'subtotal' => 'decimal:2',
        'impuesto' => 'decimal:2',
        'total'    => 'decimal:2',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function orden(): BelongsTo
    {
        return $this->belongsTo(OrdenTrabajo::class, 'id_orden', 'id_orden');
    }

    public function cotizacion(): BelongsTo
    {
        return $this->belongsTo(Cotizacion::class, 'id_cotizacion', 'id_cotizacion');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'id_factura', 'id_factura');
    }

    public function getTotalPagadoAttribute(): float
    {
        return (float) $this->pagos->sum('monto');
    }

    public function getSaldoPendienteAttribute(): float
    {
        return max(0, (float) $this->total - $this->total_pagado);
    }
}
