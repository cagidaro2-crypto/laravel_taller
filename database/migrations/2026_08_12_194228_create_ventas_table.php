<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id('id_venta');

            $table->foreignId('id_cliente')
                ->nullable()
                ->constrained('clientes', 'id_cliente')
                ->nullOnDelete();

            $table->foreignId('id_usuario')
                ->constrained('usuarios', 'id_usuario')
                ->restrictOnDelete();

            $table->date('fecha');

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('impuesto', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            $table->string('estado', 50)->default('Completada');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};