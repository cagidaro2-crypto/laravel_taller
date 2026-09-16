<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordenes_trabajo', function (Blueprint $table) {
            $table->id('id_orden');

            $table->foreignId('id_vehiculo')
                ->constrained('vehiculos', 'id_vehiculo')
                ->restrictOnDelete();

            $table->foreignId('id_estado')
                ->constrained('estados_ot', 'id_estado')
                ->restrictOnDelete();

            $table->foreignId('id_usuario')
                ->constrained('usuarios', 'id_usuario')
                ->restrictOnDelete();

            $table->date('fecha_ingreso');
            $table->date('fecha_salida')->nullable();

            $table->text('descripcion_problema')->nullable();
            $table->text('diagnostico')->nullable();
            $table->text('observaciones')->nullable();

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('impuesto', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordenes_trabajo');
    }
};