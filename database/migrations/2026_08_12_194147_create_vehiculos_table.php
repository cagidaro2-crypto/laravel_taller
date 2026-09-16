<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id('id_vehiculo');

            $table->foreignId('id_cliente')
                ->constrained('clientes', 'id_cliente')
                ->cascadeOnDelete();

            $table->foreignId('id_estado')
                ->constrained('estado_vehiculo', 'id_estado')
                ->restrictOnDelete();

            $table->string('placa', 20)->unique();
            $table->string('marca', 80);
            $table->string('modelo', 80);
            $table->year('anio')->nullable();
            $table->string('color', 50)->nullable();
            $table->string('tipo', 50)->nullable();
            $table->string('vin', 100)->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};