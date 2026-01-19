<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'review_question_id',
        'selected_options'
    ];

    protected $casts = [
        'selected_options' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function question()
    {
        return $this->belongsTo(ReviewQuestion::class);
    }

    /**
     * Check if user has completed review for a course
     */
    public static function hasCompletedReview($userId, $courseId)
    {
        $totalQuestions = ReviewQuestion::where('course_id', $courseId)
            ->where('is_active', true)
            ->count();
        
        $answeredQuestions = self::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->count();
        
        return $totalQuestions > 0 && $answeredQuestions >= $totalQuestions;
    }
}


