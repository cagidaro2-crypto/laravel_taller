<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orden_productos', function (Blueprint $table) {
            $table->id('id_orden_producto');

            $table->foreignId('id_orden')
                ->constrained('ordenes_trabajo', 'id_orden')
                ->cascadeOnDelete();

            $table->foreignId('id_producto')
                ->constrained('productos', 'id_producto')
                ->restrictOnDelete();

            $table->integer('cantidad')->default(1);
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('subtotal', 12, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orden_productos');
    }
};