<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListaUserTable extends Migration
{
    public function up()
    {
        Schema::create('lista_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_lista');
            $table->unsignedBigInteger('user_id');
            $table->string('role')->default('editor');
            $table->timestamps();

            $table->foreign('id_lista')
                  ->references('id_lista')
                  ->on('listas')
                  ->onDelete('cascade');
            
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Evitar duplicados
            $table->unique(['id_lista', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('lista_user');
    }
}

