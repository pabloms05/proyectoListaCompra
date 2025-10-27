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
        Schema::create('listas', function (Blueprint $table) {
            $table->string('id_lista', 10)->primary();
            $table->string('id_usuario_creador', 10);
            $table->string('nombre', 25);
            $table->boolean('compartida')->default(false);
            $table->timestamps();

            $table->foreign('id_usuario_creador')
                  ->references('id_usuario')
                  ->on('usuarios')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('listas');
    }
};
