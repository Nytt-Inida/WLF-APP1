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
        // Only modify table if it exists
        if (Schema::hasTable('quiz_progress')) {
            Schema::table('quiz_progress', function (Blueprint $table) {
                if (!Schema::hasColumn('quiz_progress', 'selected_option')) {
                    $table->integer('selected_option')->nullable()->after('is_correct');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('quiz_progress') && Schema::hasColumn('quiz_progress', 'selected_option')) {
            Schema::table('quiz_progress', function (Blueprint $table) {
                $table->dropColumn('selected_option');
            });
        }
    }
};