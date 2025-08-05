<?php

use App\Models\Marca;
use App\Models\Serie;
use App\Models\Categoria;
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
        Schema::create('licencias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignIdFor(Serie::class);
            $table->foreignIdFor(Marca::class);
            $table->foreignIdFor(Categoria::class);
            $table->integer('cantidad');
            $table->decimal('precio', 10, 2);
            $table->decimal('precio_mayorista', 10, 2);
            $table->decimal('precio_renovacion', 10, 2);
            $table->enum('compartida', ['0','1'])->default('0');
            $table->enum('trasladable', ['0','1'])->default('0');
            $table->enum('caducable', ['0','1'])->default('0');
            $table->enum('formateable', ['0','1'])->default('0');
            $table->enum('compra_asistida', ['0','1'])->default('0');
            $table->integer('cantidad_disponible');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licencias');
    }
};
