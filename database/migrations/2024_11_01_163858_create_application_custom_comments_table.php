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
        Schema::create('application_custom_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');
            $table->text('health_condition')->nullable();
            $table->text('medical_history')->nullable();

            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_custom_comments');
    }
};
