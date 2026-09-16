<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_vehiculo', function (Blueprint $table) {
            $table->id('id_historial');

            $table->foreignId('id_vehiculo')
                ->constrained('vehiculos', 'id_vehiculo')
                ->cascadeOnDelete();

            $table->date('fecha');
            $table->string('descripcion', 500);
            $table->decimal('valor', 12, 2)->default(0);
            $table->string('estado', 50)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_vehiculo');
    }
};