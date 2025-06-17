<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('appointment_bookings')) {
            if (!Schema::hasColumn('appointment_bookings', 'reference')) {
                Schema::table('appointment_bookings', function (Blueprint $table) {
                    $table->string('reference')->after('type')->nullable();
                });
            }

            if (!Schema::hasColumn('appointment_bookings', 'center_name')) {
                Schema::table('appointment_bookings', function (Blueprint $table) {
                    $table->string('center_name')->after('reference')->nullable();
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
