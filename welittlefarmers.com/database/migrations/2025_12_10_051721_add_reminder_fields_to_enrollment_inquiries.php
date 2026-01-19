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
        if (Schema::hasTable('enrollment_inquiries')) {
            Schema::table('enrollment_inquiries', function (Blueprint $table) {
                // Check if admin_notes exists to determine placement
                $afterColumn = Schema::hasColumn('enrollment_inquiries', 'admin_notes') 
                    ? 'admin_notes' 
                    : 'status';
                
                if (!Schema::hasColumn('enrollment_inquiries', 'reminder_count')) {
                    $table->integer('reminder_count')->default(0)->after($afterColumn);
                }
                
                if (!Schema::hasColumn('enrollment_inquiries', 'last_reminder_sent_at')) {
                    $table->timestamp('last_reminder_sent_at')->nullable()->after('reminder_count');
                }
                
                if (!Schema::hasColumn('enrollment_inquiries', 'reminder_notes')) {
                    $table->text('reminder_notes')->nullable()->after('last_reminder_sent_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollment_inquiries', function (Blueprint $table) {
            $table->dropColumn(['reminder_count', 'last_reminder_sent_at', 'reminder_notes']);
        });
    }
};
