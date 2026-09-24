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
        Schema::create('consumo_materiales', function (Blueprint $table) {
            $table->id('id_consumo');
            $table->unsignedBigInteger('id_orden');
            $table->unsignedBigInteger('id_producto');
            $table->integer('cantidad_usada');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->string('observaciones')->nullable();
            $table->timestamp('fecha_consumo')->useCurrent();
            $table->timestamps();
            
            $table->foreign('id_orden')
                ->references('id_orden')
                ->on('ordenes_trabajo')
                ->onDelete('cascade');
            
            $table->foreign('id_producto')
                ->references('id_producto')
                ->on('productos')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumo_materiales');
    }
};
