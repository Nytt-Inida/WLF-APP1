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
        if (!Schema::hasColumn('review_questions', 'options_json')) {
            Schema::table('review_questions', function (Blueprint $table) {
                $table->json('options_json')->nullable()->after('option_5');
            });
            
            // Migrate existing data
            $questions = DB::table('review_questions')->get();
            foreach ($questions as $question) {
                $options = [];
                if ($question->option_1) $options[] = $question->option_1;
                if ($question->option_2) $options[] = $question->option_2;
                if ($question->option_3) $options[] = $question->option_3;
                if ($question->option_4) $options[] = $question->option_4;
                if ($question->option_5) $options[] = $question->option_5;
                
                DB::table('review_questions')
                    ->where('id', $question->id)
                    ->update(['options_json' => json_encode($options)]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_questions', function (Blueprint $table) {
            $table->dropColumn('options_json');
        });
    }
};
