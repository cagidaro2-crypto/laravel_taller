<?php

namespace App\Models\Cliente;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Admin\Usuario;
use App\Models\Admin\Cotizacion;
use App\Models\Admin\Factura;
use App\Models\Admin\Venta;
use App\Models\Tecnico\Vehiculo;
use App\Models\Tecnico\Cita;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $primaryKey = 'id_cliente';

    protected $fillable = [
        'id_usuario',
        'documento',
        'direccion',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class, 'id_cliente', 'id_cliente');
    }

    public function cotizaciones(): HasMany
    {
        return $this->hasMany(Cotizacion::class, 'id_cliente', 'id_cliente');
    }

    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class, 'id_cliente', 'id_cliente');
    }

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class, 'id_cliente', 'id_cliente');
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class, 'id_cliente', 'id_cliente');
    }
}
