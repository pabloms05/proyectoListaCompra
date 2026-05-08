<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Ensure columns exist before creating index
            if (Schema::hasColumn('productos', 'name') && Schema::hasColumn('productos', 'categoria_id')) {
                $table->unique(['name', 'categoria_id'], 'productos_name_categoria_unique');
            }
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropUnique('productos_name_categoria_unique');
        });
    }
};
