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
        Schema::create('union_medical_center_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('union_id');
            $table->unsignedBigInteger('medical_center_id');

            $table->foreign('union_id')->references('id')->on('union_accounts')->onDelete('cascade');
            $table->foreign('medical_center_id')->references('id')->on('medical_centers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('union_medical_center_lists');
    }
};
