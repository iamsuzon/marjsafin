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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedInteger('user_type');
            $table->string('masked_card_number');
            $table->longText('card_number');
            $table->string('card_holder_name');
            $table->longText('card_expiration_date');
            $table->longText('card_cvv');
            $table->boolean('is_otp_disabled')->default(true);
            $table->longText('access_key');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
