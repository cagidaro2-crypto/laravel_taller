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
        Schema::table('facturas', function (Blueprint $table) {
            $table->foreignId('id_cotizacion')
                ->nullable()
                ->after('id_orden')
                ->constrained('cotizaciones', 'id_cotizacion')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropForeign(['id_cotizacion']);
            $table->dropColumn('id_cotizacion');
        });
    }
};
