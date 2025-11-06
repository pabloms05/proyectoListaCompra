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
            $table->id('id_lista');
            $table->unsignedBigInteger('owner_id');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->boolean('compartida')->default(false);
            $table->timestamps();

            $table->foreign('owner_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('listas');
    }
};
