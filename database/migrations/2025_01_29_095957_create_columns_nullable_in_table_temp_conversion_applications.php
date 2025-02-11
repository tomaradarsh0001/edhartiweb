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
        Schema::table('temp_conversion_applications', function (Blueprint $table) {
            $table->string('case_no')->nullable()->change();
            $table->string('case_detail')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temp_conversion_applications', function (Blueprint $table) {
            $table->string('case_no')->nullable(false)->change();
            $table->string('case_detail')->nullable(false)->change();
        });
    }
};
