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
        Schema::create('appointment_booking_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_booking_id')->index();
            $table->longText('url')->nullable();
            $table->string('type')->nullable()->index();
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid')->index();

            $table->foreign('appointment_booking_id')->references('id')->on('appointment_bookings')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_options');
    }
};
