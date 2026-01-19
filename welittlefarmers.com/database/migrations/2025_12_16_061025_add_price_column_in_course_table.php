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
        if (Schema::hasTable('courses') && !Schema::hasColumn('courses', 'price')) {
            Schema::table('courses', function (Blueprint $table) {
                // Determine placement - check if course_poster exists, otherwise add at end
                $afterColumn = Schema::hasColumn('courses', 'course_poster') 
                    ? 'course_poster' 
                    : null;
                
                if ($afterColumn) {
                    $table->integer('price')->default(0)->after($afterColumn);
                } else {
                    $table->integer('price')->default(0);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
