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
        if (!Schema::hasTable('lessons')) {
            Schema::create('lessons', function (Blueprint $table) {
                $table->id();
                $table->foreignId('course_id')->constrained()->onDelete('cascade');
                $table->unsignedBigInteger('course_detail_id')->nullable();
                $table->unsignedBigInteger('section_id')->nullable();
                $table->string('title');
                $table->string('video_url')->nullable();
                $table->string('duration')->nullable();
                $table->timestamps();

                $table->foreign('course_detail_id')->references('id')->on('course_details')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};


