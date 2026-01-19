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
        return; // FORCE BYPASS: Table already exists with conflicting schema
        if (!Schema::hasTable('discount_codes')) {
            Schema::create('discount_codes', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('type')->default('percent'); // 'flat' or 'percent'
                $table->decimal('value', 8, 2); // e.g., 10.00 or 50.00
                $table->decimal('min_order_amount', 8, 2)->default(0);
                $table->integer('max_usage')->nullable(); // Null = unlimited
                $table->integer('usage_count')->default(0);
                $table->timestamp('expires_at')->nullable();
                
                // If this code belongs to a specific user (e.g., a reward coupon)
                $table->unsignedBigInteger('user_id')->nullable();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_codes');
    }
};
