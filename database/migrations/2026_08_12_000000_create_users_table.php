<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');

            $table->foreignId('id_rol')
                ->constrained('roles', 'id_rol')
                ->restrictOnDelete();

            $table->string('nombre', 100);
            $table->string('correo', 150)->unique();
            $table->string('password');
            $table->string('telefono', 20)->nullable();
            $table->boolean('activo')->default(true);
            $table->rememberToken();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};