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
        if (Schema::hasTable('discount_codes')) {
            Schema::table('discount_codes', function (Blueprint $table) {
                // Rename existing columns to be specific to INR
                if (Schema::hasColumn('discount_codes', 'value') && !Schema::hasColumn('discount_codes', 'value_inr')) {
                    $table->renameColumn('value', 'value_inr');
                }
                if (Schema::hasColumn('discount_codes', 'min_order_amount') && !Schema::hasColumn('discount_codes', 'min_order_inr')) {
                    $table->renameColumn('min_order_amount', 'min_order_inr');
                }

                // Add USD specific columns
                if (!Schema::hasColumn('discount_codes', 'value_usd')) {
                    $table->decimal('value_usd', 8, 2)->default(0)->after('value_inr');
                }
                if (!Schema::hasColumn('discount_codes', 'min_order_usd')) {
                    $table->decimal('min_order_usd', 8, 2)->default(0)->after('min_order_inr');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('discount_codes')) {
            Schema::table('discount_codes', function (Blueprint $table) {
                if (Schema::hasColumn('discount_codes', 'value_inr')) {
                    $table->renameColumn('value_inr', 'value');
                }
                if (Schema::hasColumn('discount_codes', 'min_order_inr')) {
                    $table->renameColumn('min_order_inr', 'min_order_amount');
                }
                
                if (Schema::hasColumn('discount_codes', 'value_usd')) {
                    $table->dropColumn('value_usd');
                }
                if (Schema::hasColumn('discount_codes', 'min_order_usd')) {
                    $table->dropColumn('min_order_usd');
                }
            });
        }
    }
};
