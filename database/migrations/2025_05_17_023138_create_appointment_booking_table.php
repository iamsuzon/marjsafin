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
        Schema::create('appointment_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('type')->nullable()->index();
            $table->string('country')->nullable()->default('BD')->comment('country code');
            $table->unsignedInteger('city')->nullable()->comment('wafid city id');
            $table->string('country_traveling_to')->nullable()->comment('country code');
            $table->string('first_name')->nullable()->max(50);
            $table->string('last_name')->nullable()->max(50);
            $table->string('dob')->nullable()->comment('DD/MM/YYYY');
            $table->unsignedInteger('nationality')->nullable()->default(15)->comment('wafid nationality id');
            $table->string('gender')->nullable()->default('male')->comment('male/female');
            $table->string('marital_status')->comment('married/unmarried/divorced/widowed');
            $table->string('passport_number')->index()->max(10);
            $table->string('passport_issue_date')->comment('DD/MM/YYYY');
            $table->string('passport_issue_place')->default('dhaka');
            $table->string('passport_expiry_date')->default('DD/MM/YYYY');
            $table->string('visa_type')->default('wv');
            $table->string('email')->max(20);
            $table->string('phone_number')->max(20);
            $table->string('nid_number')->max(20);
            $table->unsignedInteger('applied_position')->comment('wafid applied position id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
