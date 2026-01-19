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
        // Only create tables if they don't exist
        if (!Schema::hasTable('blogs')) {
            Schema::create('blogs', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('excerpt')->nullable();
                $table->longText('content');
                $table->string('featured_image')->nullable();
                $table->string('author')->default('Admin');
                $table->boolean('is_published')->default(false);
                $table->timestamp('published_at')->nullable();
                $table->integer('views')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('blog_tags')) {
            Schema::create('blog_tags', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('blog_blog_tag')) {
            Schema::create('blog_blog_tag', function (Blueprint $table) {
                $table->foreignId('blog_id')->constrained()->onDelete('cascade');
                $table->foreignId('blog_tag_id')->constrained()->onDelete('cascade');
                $table->primary(['blog_id', 'blog_tag_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_blog_tag');
        Schema::dropIfExists('blog_tags');
        Schema::dropIfExists('blogs');
    }
};
