<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            // Employment category shown as filterable job type on careers page.
            // Values: freelance, full_time, internship, part_time, temporary
            $table->string('employment_type')->default('full_time')->after('job_type')
                  ->comment('Employment category: freelance, full_time, internship, part_time, temporary');
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('employment_type');
        });
    }
};
