<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        return; // FORCE BYPASS
        if (!Schema::hasTable('referral_settings')) {
            Schema::create('referral_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });

            // Seed default settings
            $now = now();
            DB::table('referral_settings')->insert([
                ['key' => 'is_enabled', 'value' => '1', 'created_at' => $now, 'updated_at' => $now],
                ['key' => 'referrer_reward_type', 'value' => 'discount', 'created_at' => $now, 'updated_at' => $now],
                ['key' => 'referrer_reward_value', 'value' => '20', 'created_at' => $now, 'updated_at' => $now], // 20% off coupon
                ['key' => 'referee_discount_type', 'value' => 'percent', 'created_at' => $now, 'updated_at' => $now],
                ['key' => 'referee_discount_amount', 'value' => '10', 'created_at' => $now, 'updated_at' => $now], // 10% off for new user
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_settings');
    }
};
