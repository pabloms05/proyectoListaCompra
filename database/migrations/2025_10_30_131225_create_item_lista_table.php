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
        Schema::create('item_lista', function (Blueprint $table) {
            $table->foreignId('lista_id')->constrained('listas')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->decimal('cantidad', 8, 2)->default(1);
            $table->boolean('comprado')->default(false);
            $table->string('notas', 200)->nullable();
            $table->timestamps();

            $table->primary(['lista_id', 'producto_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_lista');
    }
};
