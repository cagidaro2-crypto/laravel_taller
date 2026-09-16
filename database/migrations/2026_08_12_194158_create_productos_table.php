<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id('id_producto');

            $table->foreignId('id_categoria')
                ->constrained('categorias_productos', 'id_categoria')
                ->restrictOnDelete();

            $table->foreignId('id_proveedor')
                ->nullable()
                ->constrained('proveedores', 'id_proveedor')
                ->nullOnDelete();

            $table->string('nombre', 150);
            $table->string('codigo', 50)->unique();
            $table->text('descripcion')->nullable();

            $table->string('marca', 100)->nullable();
            $table->string('unidad_medida', 30)->default('unidad');

            $table->decimal('precio_compra', 12, 2)->default(0);
            $table->decimal('precio_venta', 12, 2)->default(0);

            $table->integer('stock_minimo')->default(0);

            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};