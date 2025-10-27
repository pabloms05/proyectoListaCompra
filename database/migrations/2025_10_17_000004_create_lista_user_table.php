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
            $table->foreignId('lista_id')
                  ->constrained('listas')
                  ->onDelete('cascade');
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->string('role')->default('editor');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lista_user');
    }
}

