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
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('application_id')->nullable();
            $table->string('payment_type')->comment('deposit, payment');
            $table->string('payment_method')->nullable();
            $table->string('amount');
            $table->string('reference_no')->nullable();
            $table->date('deposit_date');
            $table->string('deposit_slip')->nullable();
            $table->string('remarks')->nullable();
            $table->string('status')->nullable()->default('pending');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
    }
};
