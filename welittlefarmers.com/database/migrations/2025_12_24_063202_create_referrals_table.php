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
        return; // FORCE BYPASS
        if (!Schema::hasTable('referrals')) {
            Schema::create('referrals', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('referrer_id');
                $table->unsignedBigInteger('referred_user_id');
                
                // Status: pending (registered), completed (paid)
                $table->string('status')->default('pending'); 
                
                // Details about the reward given to the referrer
                $table->string('reward_type')->nullable(); // 'discount'
                $table->string('reward_value')->nullable(); // val of coupon
                $table->string('reward_coupon_code')->nullable(); // The actual code generated
                
                $table->foreign('referrer_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('referred_user_id')->references('id')->on('users')->onDelete('cascade');
                
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
