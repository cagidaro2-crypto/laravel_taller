<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->bigIncrements('id_notificacion');
            $table->unsignedBigInteger('id_usuario_destinatario'); // Quien recibe
            $table->unsignedBigInteger('id_orden')->nullable();
            $table->unsignedBigInteger('id_vehiculo')->nullable();
            $table->enum('tipo', [
                'estado_orden_cambio',
                'estado_vehiculo_cambio',
                'orden_completada',
                'orden_retrasada',
            ])->default('estado_vehiculo_cambio');
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('estado_anterior')->nullable();
            $table->string('estado_nuevo')->nullable();
            $table->boolean('leida')->default(false);
            $table->boolean('enviada_por_email')->default(false);
            $table->timestamp('fecha_envio')->nullable();
            $table->timestamps();

            // Índices y relaciones
            $table->foreign('id_usuario_destinatario')
                ->references('id_usuario')
                ->on('usuarios')
                ->cascadeOnDelete();

            $table->foreign('id_orden')
                ->references('id_orden')
                ->on('ordenes_trabajo')
                ->nullOnDelete();

            $table->foreign('id_vehiculo')
                ->references('id_vehiculo')
                ->on('vehiculos')
                ->nullOnDelete();

            $table->index(['id_usuario_destinatario', 'leida']);
            $table->index(['id_orden', 'tipo']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
