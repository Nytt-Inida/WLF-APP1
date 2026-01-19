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
        if (Schema::hasTable('lesson_progress')) {
            Schema::table('lesson_progress', function (Blueprint $table) {
                if (!Schema::hasColumn('lesson_progress', 'last_position_seconds')) {
                    $afterColumn = Schema::hasColumn('lesson_progress', 'is_completed') 
                        ? 'is_completed' 
                        : null;
                    if ($afterColumn) {
                        $table->integer('last_position_seconds')->nullable()->after($afterColumn)->comment('Last watched video position in seconds');
                    } else {
                        $table->integer('last_position_seconds')->nullable()->comment('Last watched video position in seconds');
                    }
                }
                
                if (!Schema::hasColumn('lesson_progress', 'completed_at')) {
                    $afterColumn = Schema::hasColumn('lesson_progress', 'last_position_seconds') 
                        ? 'last_position_seconds' 
                        : null;
                    if ($afterColumn) {
                        $table->timestamp('completed_at')->nullable()->after($afterColumn)->comment('When the lesson was marked as complete');
                    } else {
                        $table->timestamp('completed_at')->nullable()->comment('When the lesson was marked as complete');
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->dropColumn(['last_position_seconds', 'completed_at']);
        });
    }
};
