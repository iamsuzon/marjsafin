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
        Schema::table('applications', function (Blueprint $table) {
            $table->string('marital_status')->nullable()->change();
            $table->string('father_name')->nullable()->change();
            $table->string('mother_name')->nullable()->change();
            $table->string('pp_issue_place')->nullable()->change();
            $table->date('date_of_birth')->nullable()->change();
            $table->unsignedBigInteger('nid_no')->nullable()->change();
            $table->date('passport_issue_date')->nullable()->change();
            $table->date('passport_expiry_date')->nullable()->change();
            $table->string('contact_no')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            //
        });
    }
};
