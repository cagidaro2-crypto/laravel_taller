<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id('id_cotizacion');

            $table->foreignId('id_cliente')
                ->constrained('clientes', 'id_cliente')
                ->restrictOnDelete();

            $table->foreignId('id_vehiculo')
                ->nullable()
                ->constrained('vehiculos', 'id_vehiculo')
                ->nullOnDelete();

            $table->foreignId('id_usuario')
                ->constrained('usuarios', 'id_usuario')
                ->restrictOnDelete();

            $table->date('fecha');
            $table->date('fecha_vencimiento')->nullable();

            $table->string('estado', 50)->default('Pendiente');

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('impuesto', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};