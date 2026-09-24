<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $primaryKey = 'id_notificacion';

    protected $fillable = [
        'id_usuario_destinatario',
        'id_orden',
        'id_vehiculo',
        'tipo',
        'titulo',
        'descripcion',
        'estado_anterior',
        'estado_nuevo',
        'leida',
        'enviada_por_email',
        'fecha_envio',
    ];

    protected $casts = [
        'leida'             => 'boolean',
        'enviada_por_email' => 'boolean',
        'fecha_envio'       => 'datetime',
    ];

    /**
     * Relación: El usuario que RECIBE la notificación (cliente)
     */
    public function usuarioDestinatario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_destinatario', 'id_usuario');
    }

    /**
     * Relación: La orden de trabajo asociada
     */
    public function orden(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Tecnico\OrdenTrabajo::class, 'id_orden', 'id_orden');
    }

    /**
     * Relación: El vehículo asociado
     */
    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Tecnico\Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }

    /**
     * Marcar notificación como leída
     */
    public function marcarLeida()
    {
        $this->update(['leida' => true]);
    }

    /**
     * Obtener mensaje legible del tipo
     */
    public function getTipoLegible(): string
    {
        $tipos = [
            'estado_orden_cambio' => 'Cambio de Estado de Orden',
            'estado_vehiculo_cambio' => 'Cambio de Estado de Vehículo',
            'orden_completada' => 'Orden Completada',
            'orden_retrasada' => 'Orden Retrasada',
        ];

        return $tipos[$this->tipo] ?? 'Notificación';
    }
}
