<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Cliente\Cliente;
use App\Models\Tecnico\OrdenTrabajo;
use App\Models\Admin\Cotizacion;
use App\Models\Tecnico\Cita;
use App\Models\Admin\Venta;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';

    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'id_rol',
        'nombre',
        'correo',
        'password',
        'telefono',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function getAuthIdentifierName(): string
    {
        return 'id_usuario';
    }

    /**
     * Laravel necesita este campo para Auth::attempt con 'email'.
     * Aquí redirigimos 'email' => 'correo'.
     */
    public function getAuthPassword(): string
    {
        return $this->password;
    }

    /**
     * Nombre del campo que actúa como "username" para Auth.
     */
    public static function getAuthField(): string
    {
        return 'correo';
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class, 'id_usuario', 'id_usuario');
    }

    public function ordenesTrabajo(): HasMany
    {
        return $this->hasMany(OrdenTrabajo::class, 'id_usuario', 'id_usuario');
    }

    public function cotizaciones(): HasMany
    {
        return $this->hasMany(Cotizacion::class, 'id_usuario', 'id_usuario');
    }

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class, 'id_usuario', 'id_usuario');
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class, 'id_usuario', 'id_usuario');
    }
}
