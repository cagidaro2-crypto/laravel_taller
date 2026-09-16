<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id('id_cita');

            $table->foreignId('id_cliente')
                ->constrained('clientes', 'id_cliente')
                ->cascadeOnDelete();

            $table->foreignId('id_vehiculo')
                ->constrained('vehiculos', 'id_vehiculo')
                ->cascadeOnDelete();

            $table->foreignId('id_usuario')
                ->nullable()
                ->constrained('usuarios', 'id_usuario')
                ->nullOnDelete();

            $table->date('fecha');
            $table->time('hora');

            $table->string('motivo', 255)->nullable();

            $table->string('estado', 50)->default('Pendiente');

            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};