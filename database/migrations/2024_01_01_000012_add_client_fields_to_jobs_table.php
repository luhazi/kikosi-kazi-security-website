<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->enum('job_type', ['kikosi_kazi', 'client'])->default('kikosi_kazi')->after('status');
            $table->string('client_name')->nullable()->after('job_type');
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn(['job_type', 'client_name']);
        });
    }
};
