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
        Schema::table('review_questions', function (Blueprint $table) {
            $table->string('option_4')->nullable()->after('option_3');
            $table->string('option_5')->nullable()->after('option_4');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_questions', function (Blueprint $table) {
            $table->dropColumn(['option_4', 'option_5']);
        });
    }
};
