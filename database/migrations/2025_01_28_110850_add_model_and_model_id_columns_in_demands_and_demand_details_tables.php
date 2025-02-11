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
        Schema::table('demands', function (Blueprint $table) {
            $table->string('model')->nullable()->after('app_no');
            $table->bigInteger('model_id')->nullable()->after('model');
        });
        Schema::table('demand_details', function (Blueprint $table) {
            $table->string('model')->nullable()->after('subhead_id');
            $table->bigInteger('model_id')->nullable()->after('model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demands', function (Blueprint $table) {
            $table->dropColumn('model');
            $table->dropColumn('model_id');
        });
        Schema::table('demand_details', function (Blueprint $table) {
            $table->dropColumn('model');
            $table->dropColumn('model_id');
        });
    }
};
