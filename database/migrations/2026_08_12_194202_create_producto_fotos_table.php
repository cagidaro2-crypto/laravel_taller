<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producto_fotos', function (Blueprint $table) {
            $table->id('id_foto');

            $table->foreignId('id_producto')
                ->constrained('productos', 'id_producto')
                ->cascadeOnDelete();

            $table->string('ruta_foto', 255);
            $table->string('descripcion', 255)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto_fotos');
    }
};