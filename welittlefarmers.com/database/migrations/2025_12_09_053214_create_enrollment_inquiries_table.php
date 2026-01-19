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
        if (!Schema::hasTable('enrollment_inquiries')) {
            Schema::create('enrollment_inquiries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('course_id')->constrained()->onDelete('cascade');
                $table->string('name');
                $table->string('email');
                $table->string('phone')->nullable();
                $table->enum('status', ['pending', 'contacted', 'enrolled', 'rejected'])->default('pending');
                $table->text('admin_notes')->nullable();
                $table->timestamp('contacted_at')->nullable();
                $table->foreignId('contacted_by')->nullable()->constrained('admins')->onDelete('set null');
                $table->timestamps();

                $table->index(['status', 'created_at']);
                $table->index('course_id');
                $table->index('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollment_inquiries');
    }
};
