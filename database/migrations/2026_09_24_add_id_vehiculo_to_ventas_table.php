<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            // Agregar columna id_vehiculo (opcional para ventas sin vehículo)
            if (!Schema::hasColumn('ventas', 'id_vehiculo')) {
                $table->unsignedBigInteger('id_vehiculo')->nullable()->after('id_cliente');
                
                $table->foreign('id_vehiculo')
                    ->references('id_vehiculo')
                    ->on('vehiculos')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            if (Schema::hasColumn('ventas', 'id_vehiculo')) {
                $table->dropForeign(['id_vehiculo']);
                $table->dropColumn('id_vehiculo');
            }
        });
    }
};
