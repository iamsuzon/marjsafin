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
        Schema::create('slips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('slip_type')->nullable();
            $table->string('ref_ledger', 255)->default('Raju');
            $table->string('passport_number', 255)->index();
            $table->string('gender');
            $table->unsignedBigInteger('city_id');
            $table->string('center_slug', 255)->index();
            $table->string('marital_status');
            $table->string('surname');
            $table->string('given_name');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('religion')->nullable();
            $table->string('pp_issue_place');
            $table->string('profession');
            $table->string('nationality');
            $table->date('date_of_birth');
            $table->string('contact_no')->nullable();
            $table->unsignedBigInteger('nid_no');
            $table->date('passport_issue_date');
            $table->date('passport_expiry_date');
            $table->string('ref_no');
            $table->string('pdf_code')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slips');
    }
};
