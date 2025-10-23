<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('productos', function (Blueprint $table) {
            $table->string('id_producto', 10)->primary();
            $table->string('nombre', 50);
            $table->string('id_categoria', 10)->nullable();
            $table->string('unidad_medida', 5)->nullable();
            $table->timestamps();

            $table->foreign('id_categoria')
                  ->references('id_categoria')
                  ->on('categorias')
                  ->nullOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('productos');
    }
};
