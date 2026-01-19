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
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('vtt_path')->nullable()->after('video_url');
            $table->enum('subtitle_status', ['pending', 'processing', 'completed', 'failed'])->default('pending')->after('vtt_path');
            $table->text('subtitle_error')->nullable()->after('subtitle_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['vtt_path', 'subtitle_status', 'subtitle_error']);
        });
    }
};