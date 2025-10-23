<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('cantidad')->default(1);
            $table->string('imagen')->nullable();
            $table->boolean('completed')->default(false);
            $table->foreignId('categoria_id')
                  ->constrained('categorias')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
