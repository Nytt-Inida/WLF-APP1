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
        Schema::create('live_courses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 225);
            $table->string('email')->index();
            $table->string('school_name', 225);
            $table->unsignedInteger('age');
            $table->date('date');
            $table->string('course_name', 225)->index();
            $table->timestamps();

            $table->unique(['email', 'course_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_courses');
    }
};
