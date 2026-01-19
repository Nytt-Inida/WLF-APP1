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
        if (!Schema::hasTable('review_answers')) {
            Schema::create('review_answers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('course_id')->constrained()->onDelete('cascade');
                $table->foreignId('review_question_id')->constrained()->onDelete('cascade');
                $table->json('selected_options'); // Store array of selected option indices [0, 1, 2]
                $table->timestamps();
                
                // Ensure one answer per user per question per course
                $table->unique(['user_id', 'course_id', 'review_question_id'], 'user_course_question_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_answers');
    }
};


