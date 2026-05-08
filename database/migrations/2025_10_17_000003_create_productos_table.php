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
            $table->id('id_producto');
            $table->string('name', 255);
            $table->unsignedBigInteger('categoria_id')->nullable();
            $table->string('unidad_medida', 50)->nullable();
            $table->timestamps();

            $table->foreign('categoria_id')
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
