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
        Schema::create('ventas', function (Blueprint $table) {
             $table->id();
             $table->foreignId('comprador_id')->constrained('personas');
             $table->foreignId('vendedor_id')->constrained('personas');
             $table->decimal('montototal', 10, 2)->default(0.00);
             $table->integer('estado')->default(0); // 1: Activo, 0: Inactivo
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
