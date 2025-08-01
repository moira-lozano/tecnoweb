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
        Schema::create('tipo_personas', function (Blueprint $table) {
            $table->id();
              $table->foreignId('tipo_id')->constrained('tipos')->onDelete('cascade');
             $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_personas');
    }
};
