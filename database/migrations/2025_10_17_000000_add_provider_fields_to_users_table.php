<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProviderFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('provider_name')->nullable()->after('remember_token');
            $table->string('provider_id')->nullable()->after('provider_name');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('provider_id');
            $table->dropColumn('provider_name');
        });
    }
}
