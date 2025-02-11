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
        Schema::create('conversion_application_histories', function (Blueprint $table) {
            $table->id();
            $table->string('application_no');
            $table->smallInteger('status')->nullable();
            $table->smallInteger('new_status')->nullable();
            $table->smallInteger('status_of_applicant')->nullable();
            $table->smallInteger('new_status_of_applicant')->nullable();
            $table->string('applicant_name')->nullable();
            $table->string('new_applicant_name')->nullable();
            $table->string('relation_prefix')->nullable();
            $table->string('new_relation_prefix')->nullable();
            $table->string('relation_name')->nullable();
            $table->string('new_relation_name')->nullable();
            $table->date('executed_on')->nullable();
            $table->date('new_executed_on')->nullable();
            $table->integer('reg_no')->nullable();
            $table->integer('new_reg_no')->nullable();
            $table->integer('book_no')->nullable();
            $table->integer('new_book_no')->nullable();
            $table->integer('volume_no')->nullable();
            $table->integer('new_volume_no')->nullable();
            $table->string('page_no')->nullable();
            $table->string('new_page_no')->nullable();
            $table->date('reg_date')->nullable();
            $table->date('new_reg_date')->nullable();
            $table->boolean('is_court_order')->nullable();
            $table->boolean('new_is_court_order')->nullable();
            $table->string('case_no')->nullable();
            $table->string('new_case_no')->nullable();
            $table->string('case_detail')->nullable();
            $table->string('new_case_detail')->nullable();
            $table->boolean('is_mortgaged')->nullable();
            $table->boolean('new_is_mortgaged')->nullable();
            $table->boolean('is_Lease_deed_lost')->nullable();
            $table->boolean('new_is_Lease_deed_lost')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversion_application_histories');
    }
};
