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
        // Only create table if it doesn't exist
        if (!Schema::hasTable('courses')) {
            Schema::create('courses', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('instructor_name')->nullable();
                $table->integer('instructor_exp')->nullable();
                $table->text('instructor_desc')->nullable();
                $table->string('duration')->nullable();
                $table->string('skill_level')->nullable();
                $table->string('languages')->nullable();
                $table->boolean('certificate')->default(false);
                $table->decimal('rating_value', 3, 1)->default(0);
                $table->integer('rating_count')->default(0);
                $table->text('description')->nullable();
                $table->string('image_url')->nullable();
                $table->string('preview_video_url')->nullable();
                $table->timestamps();
            });
        } else {
            // Table exists, add missing columns if they don't exist
            Schema::table('courses', function (Blueprint $table) {
                $columns = [
                    'instructor_name',
                    'instructor_exp',
                    'instructor_desc',
                    'duration',
                    'skill_level',
                    'languages',
                    'certificate',
                    'rating_value',
                    'rating_count',
                    'image_url',
                    'preview_video_url'
                ];
                
                foreach ($columns as $column) {
                    if (!Schema::hasColumn('courses', $column)) {
                        switch ($column) {
                            case 'instructor_name':
                            case 'duration':
                            case 'skill_level':
                            case 'languages':
                            case 'image_url':
                            case 'preview_video_url':
                                $table->string($column)->nullable();
                                break;
                            case 'instructor_exp':
                            case 'rating_count':
                                $table->integer($column)->nullable();
                                break;
                            case 'instructor_desc':
                                $table->text($column)->nullable();
                                break;
                            case 'certificate':
                                $table->boolean($column)->default(false);
                                break;
                            case 'rating_value':
                                $table->decimal($column, 3, 1)->default(0);
                                break;
                        }
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
        Schema::dropIfExists('courses');
    }
};
