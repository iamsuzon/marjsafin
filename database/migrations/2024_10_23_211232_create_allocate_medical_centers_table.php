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
        Schema::create('allocate_medical_centers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medical_center_id');
            $table->unsignedBigInteger('application_id');
            $table->string('allocated_medical_center')->nullable();
            $table->boolean('status')->default(0);

            $table->foreign('medical_center_id')->references('id')->on('medical_centers')->onDelete('cascade');
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allocate_medical_centers');
    }
};
