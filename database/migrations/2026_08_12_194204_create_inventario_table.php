<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventario', function (Blueprint $table) {
            $table->id('id_inventario');

            $table->foreignId('id_producto')
                ->unique()
                ->constrained('productos', 'id_producto')
                ->cascadeOnDelete();

            $table->integer('cantidad')->default(0);
            $table->integer('stock_minimo')->default(0);

            $table->timestamp('ultima_actualizacion')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario');
    }
};