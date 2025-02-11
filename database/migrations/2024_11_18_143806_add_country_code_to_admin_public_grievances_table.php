<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('admin_public_grievances', function (Blueprint $table) {
            $table->integer('country_code')->after('mobile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('admin_public_grievances', function (Blueprint $table) {
            $table->dropColumn('country_code');
        });
    }
};
