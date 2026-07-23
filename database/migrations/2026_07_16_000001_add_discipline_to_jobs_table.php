<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            // Discipline restricts which candidates can apply.
            // NULL / 'any' = open to all disciplines.
            $table->string('discipline')->nullable()->default(null)->after('department')
                  ->comment('Required candidate discipline, or NULL for open-to-all');
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('discipline');
        });
    }
};
