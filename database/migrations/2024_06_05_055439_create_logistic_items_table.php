<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('logistic_items', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();

            //foreign key
            $table->foreign('category_id')->references('id')->on('logistic_categories');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('logistic_items', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();

            //foreign key
            $table->foreign('category_id')->references('id')->on('logistic_categories');

        });
    }
};
