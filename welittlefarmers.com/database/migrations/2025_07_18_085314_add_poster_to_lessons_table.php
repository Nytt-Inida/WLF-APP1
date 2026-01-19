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
        // Only add column if lessons table exists
        if (Schema::hasTable('lessons')) {
            Schema::table('lessons', function (Blueprint $table) {
                // Check if column doesn't already exist
                if (!Schema::hasColumn('lessons', 'poster')) {
                    //Adding poster column for SEO
                    $table->string('poster')->nullable()->after('video_url');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('lessons') && Schema::hasColumn('lessons', 'poster')) {
            Schema::table('lessons', function (Blueprint $table) {
                $table->dropColumn('poster');
            });
        }
    }
};
