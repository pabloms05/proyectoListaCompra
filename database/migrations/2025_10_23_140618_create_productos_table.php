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
        Schema::create('productos', function (Blueprint $table) {
            $table->string('id_producto', 10)->primary(); // tu PK string
            $table->string('nombre', 50);
            $table->unsignedBigInteger('id_categoria')->nullable(); // 👈 Debe ser numérico, igual que categorias.id_categoria
            $table->string('unidad_medida', 10)->nullable();
            $table->timestamps();

            $table->foreign('id_categoria')
                ->references('id_categoria')
                ->on('categorias')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
