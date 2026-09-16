<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('id_pago');

            $table->foreignId('id_factura')
                ->constrained('facturas', 'id_factura')
                ->cascadeOnDelete();

            $table->decimal('monto', 12, 2);

            $table->string('metodo_pago', 50);

            $table->date('fecha_pago');

            $table->string('referencia', 100)->nullable();

            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};