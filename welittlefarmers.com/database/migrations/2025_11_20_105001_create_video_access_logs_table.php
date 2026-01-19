<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('video_access_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('lesson_id');
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('token_hash', 64)->unique();
            $table->timestamp('accessed_at')->nullable();  // Changed: added nullable()
            $table->timestamp('expires_at')->nullable();   // Changed: added nullable()
            $table->boolean('is_valid')->default(true);
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'lesson_id']);
            $table->index('token_hash');
            $table->index('expires_at');

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_access_logs');
    }
};
