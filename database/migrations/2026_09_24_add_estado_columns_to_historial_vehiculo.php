<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('historial_vehiculo', function (Blueprint $table) {
            // Add the missing estado_anterior and estado_nuevo columns if they don't exist
            if (!Schema::hasColumn('historial_vehiculo', 'estado_anterior')) {
                $table->string('estado_anterior', 50)->nullable()->after('estado');
            }
            
            if (!Schema::hasColumn('historial_vehiculo', 'estado_nuevo')) {
                $table->string('estado_nuevo', 50)->nullable()->after('estado_anterior');
            }
        });
    }

    public function down(): void
    {
        Schema::table('historial_vehiculo', function (Blueprint $table) {
            if (Schema::hasColumn('historial_vehiculo', 'estado_anterior')) {
                $table->dropColumn('estado_anterior');
            }
            
            if (Schema::hasColumn('historial_vehiculo', 'estado_nuevo')) {
                $table->dropColumn('estado_nuevo');
            }
        });
    }
};
