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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('ref_ledger', 255)->default('Raju-Marj-Air');
            $table->string('passport_number', 255)->index();
            $table->string('gender');
            $table->string('traveling_to');
            $table->string('marital_status');
            $table->string('center_name', 255)->index();
            $table->string('surname');
            $table->string('given_name');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('religion');
            $table->string('pp_issue_place');
            $table->string('profession');
            $table->string('nationality');
            $table->date('date_of_birth');
            $table->string('contact_no');
            $table->unsignedBigInteger('nid_no');
            $table->date('passport_issue_date');
            $table->date('passport_expiry_date');
            $table->string('ref_no');
            $table->string('health_status')->nullable();
            $table->longText('health_status_details')->nullable();
            $table->string('serial_number')->unique()->nullable();
            $table->string('ems_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
