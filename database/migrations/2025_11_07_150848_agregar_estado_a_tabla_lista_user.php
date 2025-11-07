<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lista_user', function (Blueprint $table) {
            // Campo para estados futuros: 'aceptado', 'pendiente', 'rechazado'
            $table->string('estado')->default('aceptado')->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('lista_user', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};