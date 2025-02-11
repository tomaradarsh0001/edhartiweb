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
        Schema::table('flat_histories', function (Blueprint $table) {
            $table->string('block')->nullable()->change();
            $table->string('new_block')->nullable()->change();
            $table->string('plot')->nullable()->change();
            $table->string('new_plot')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flat_histories', function (Blueprint $table) {
            $table->string('block')->nullable(false)->change();
            $table->string('new_block')->nullable(false)->change();
            $table->string('plot')->nullable(false)->change();
            $table->string('new_plot')->nullable(false)->change();
        });
    }
};
