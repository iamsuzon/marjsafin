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
        if (Schema::hasTable('medical_centers')) {
            if (! Schema::hasColumn('medical_centers', 'note')) {
                Schema::table('medical_centers', function (Blueprint $table) {
                    $table->longText('note')->nullable();
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
