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
        Schema::create('slip_medical_center_rates', function (Blueprint $table) {
            $table->id();
            $table->string('medical_center_slug')->unique()->index();
            $table->decimal('rate', 10, 2);
            $table->unsignedInteger('discount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slip_medical_center_rates');
    }
};
