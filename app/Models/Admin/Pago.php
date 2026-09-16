<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $primaryKey = 'id_pago';

    protected $fillable = [
        'id_factura',
        'monto',
        'metodo_pago',
        'fecha_pago',
        'referencia',
        'observaciones',
    ];

    protected $casts = [
        'monto'     => 'decimal:2',
        'fecha_pago'=> 'date',
    ];

    public function factura(): BelongsTo
    {
        return $this->belongsTo(Factura::class, 'id_factura', 'id_factura');
    }
}
