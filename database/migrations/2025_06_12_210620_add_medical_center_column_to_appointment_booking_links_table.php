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
        if (Schema::hasTable('appointment_booking_links')) {
            if (! Schema::hasColumn('appointment_booking_links', 'medical_center')) {
                Schema::table('appointment_booking_links', function (Blueprint $table) {
                    $table->string('medical_center')->nullable()->after('status');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
