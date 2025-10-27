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
        Schema::create('lista_compartida', function (Blueprint $table) {
            $table->string('id_usuario', 10);
            $table->string('id_lista', 10);
            $table->timestamps();

            $table->primary(['id_usuario', 'id_lista']);

            $table->foreign('id_usuario')
                  ->references('id_usuario')
                  ->on('usuarios')
                  ->onDelete('cascade');

            $table->foreign('id_lista')
                  ->references('id_lista')
                  ->on('listas')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('lista_compartida');
    }
};
