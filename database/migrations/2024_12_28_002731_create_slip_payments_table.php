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
        Schema::create('slip_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('slip_id');
            $table->double('slip_rate');
            $table->double('discount')->nullable();
            $table->string('payment_status')->default('pending');

            $table->foreign('slip_id')->references('id')->on('slips')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slip_payments');
    }
};
