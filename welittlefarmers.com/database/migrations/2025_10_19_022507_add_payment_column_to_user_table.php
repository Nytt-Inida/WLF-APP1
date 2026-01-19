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
        if (!Schema::hasColumn('users', 'pending_course_id')) {
            Schema::table('users', function (Blueprint $table) {
                // Course they want to purchase
                $table->unsignedBigInteger('pending_course_id')->nullable()->after('email');
                
                // Payment verification status
                // 0 = No payment, 1 = Payment pending verification, 2 = Payment verified
                $table->tinyInteger('payment_status')->default(0)->after('pending_course_id');
                
                // When user submitted payment
                $table->timestamp('payment_submitted_at')->nullable()->after('payment_status');
                
                // When admin verified payment
                $table->timestamp('payment_verified_at')->nullable()->after('payment_submitted_at');
                
                // Admin who verified
                $table->unsignedBigInteger('verified_by')->nullable()->after('payment_verified_at');
            });
        }

        // Add foreign keys separately to ensure tables exist and have correct structure
        Schema::table('users', function (Blueprint $table) {
            // Only add foreign key if courses table exists and has id column
            if (Schema::hasTable('courses') && Schema::hasColumn('courses', 'id')) {
                try {
                    $table->foreign('pending_course_id')
                        ->references('id')
                        ->on('courses')
                        ->onDelete('set null');
                } catch (\Exception $e) {
                    // Foreign key might already exist, ignore
                }
            }
            
            // Only add foreign key if admins table exists and has id column
            if (Schema::hasTable('admins') && Schema::hasColumn('admins', 'id')) {
                try {
                    $table->foreign('verified_by')
                        ->references('id')
                        ->on('admins')
                        ->onDelete('set null');
                } catch (\Exception $e) {
                    // Foreign key might already exist, ignore
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['pending_course_id']);
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'pending_course_id',
                'payment_status',
                'payment_submitted_at',
                'payment_verified_at',
                'verified_by'
            ]);
        });
    }
};
