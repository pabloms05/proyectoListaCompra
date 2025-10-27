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
        Schema::create('item_lista', function (Blueprint $table) {
            $table->string('id_lista', 10);
            $table->string('id_producto', 10);
            $table->decimal('cantidad', 8, 2)->default(1);
            $table->boolean('comprado')->default(false);
            $table->string('notas', 200)->nullable();
            $table->timestamps();

            $table->primary(['id_lista', 'id_producto']);

            $table->foreign('id_lista')
                  ->references('id_lista')
                  ->on('listas')
                  ->onDelete('cascade');

            $table->foreign('id_producto')
                  ->references('id_producto')
                  ->on('productos')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('item_lista');
    }
};
