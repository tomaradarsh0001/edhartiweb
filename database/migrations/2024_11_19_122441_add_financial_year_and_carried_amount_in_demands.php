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
            $table->string('financial_year')->nullable()->after('unique_demand_id');
            $table->integer('status')->nullable()->after('balance_amount')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demands', function (Blueprint $table) {
            $table->dropColumn('status'); // First, drop the column
            $table->dropColumn('financial_year');
        });

        Schema::table('demands', function (Blueprint $table) {
            $table->enum('status', ['pending', 'partially paid', 'paid', 'withdrawn', 'forwarded'])->nullable(); // Then re-add the ENUM column
        });
    }
};
