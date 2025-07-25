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
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->string('serial');
            $table->integer('cantidad_equipo')->default(0);
            $table->integer('estado')->default(0); // 1: Activo, 0: Inactivo
            $table->decimal('precio', 10, 2)->default(0.00);
            $table->integer('duracion')->default(0);
            $table->string('fecha_inicio')->nullable();
            $table->string('fecha_finalizacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('series');
    }
};
