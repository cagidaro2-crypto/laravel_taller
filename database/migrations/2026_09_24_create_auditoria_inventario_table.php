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
        Schema::create('auditoria_inventario', function (Blueprint $table) {
            $table->bigIncrements('id_auditoria');
            $table->unsignedBigInteger('id_producto');
            $table->integer('cantidad_cambio'); // Positivo: entrada, Negativo: salida
            $table->enum('tipo_movimiento', ['venta', 'compra', 'ajuste', 'devolución', 'consumo']);
            $table->unsignedBigInteger('id_referencia')->nullable(); // ID de venta, compra, etc.
            $table->unsignedBigInteger('id_usuario')->nullable(); // Quién realizó el cambio
            $table->text('descripcion')->nullable();
            $table->timestamps();

            $table->foreign('id_producto')
                ->references('id_producto')
                ->on('productos')
                ->restrictOnDelete();

            $table->foreign('id_usuario')
                ->references('id_usuario')
                ->on('usuarios')
                ->nullOnDelete();

            $table->index(['id_producto', 'created_at']);
            $table->index(['tipo_movimiento', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditoria_inventario');
    }
};
