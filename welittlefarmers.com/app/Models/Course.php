<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProgress;


class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'age_group',
        'description',
        'price',
        'price_usd',
        'number_of_classes',
        'image',
        'isFavourite', // Add isFavourite to fillable attributes
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected $hidden = [
        'created_at', 
        'updated_at'
    ];

    // Define the relationship with lessons if needed
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
     public function userProgresses()
    {
        return $this->hasMany(UserProgress::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
      // Define the relationship with the Review model
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
       public function details()
    {
        return $this->hasMany(CourseDetail::class);
    }
      public function sections()
    {
        return $this->hasMany(Section::class);
    }
     // Method to get the CSS class based on age group
    public function getAgeGroupClass()
    {
        switch ($this->age_group) {
            case '5-8':
                return 'cat2';
            case '9-12':
                return 'cat3';
            case '13-15':
                return 'cat4';
            default:
                return 'cat5';
        }
    }
    public function scopeByAgeGroup($query, $ageGroup)
{
    return $query->where('age_group', $ageGroup);
}

    public function userHasPaid($user = null)
    {
        $userId = $user ? $user->id : auth()->id();
        if (!$userId) return false;

        return UserProgress::where([
            ['user_id', $userId],
            ['course_id', $this->id],
            ['payment_status', 1]
        ])->exists();
    }

    /**
     * Get dynamic status including CTA text and progress.
     */
    public function getDynamicStatus($user = null)
    {
        $user = $user ?: auth()->user();
        
        // Guest Progress Check via Cookie (JS sets these, so they might not be encrypted)
        $courseId = $this->id;
        $guestProgress = request()->cookie("guest_watched_course_{$courseId}") ?: ($_COOKIE["guest_watched_course_{$courseId}"] ?? null);

        if (!$user) {
            return [
                'cta_text' => $guestProgress ? 'Go to Course details' : 'Start Your Free Trial',
                'is_paid' => false,
                'has_progress' => !!$guestProgress
            ];
        }

        // Check if user is a global paid user (status 2) or has specifically paid for this course
        $isPaidGlobal = ($user->payment_status == 2);
        
        // Specific payment check using user_progress table
        // Note: Earlier logic used both 'user_progress' table and a manual SQL check.
        // We'll stick to the logic used in the controllers for consistency.
        $isPaidSpecific = \DB::table('user_progress')
            ->where('user_id', $user->id)
            ->where('course_id', $this->id)
            ->where('payment_status', 1)
            ->exists();

        $isPaid = $isPaidGlobal || $isPaidSpecific;

        if ($isPaid) {
            return [
                'cta_text' => 'Go to Course',
                'is_paid' => true,
                'has_progress' => true
            ];
        }

        // Check progress (any started video)
        $hasProgress = \DB::table('lesson_progress')
            ->join('lessons', 'lesson_progress.lesson_id', '=', 'lessons.id')
            ->where('lesson_progress.user_id', $user->id)
            ->where('lessons.course_id', $this->id)
            ->exists();

        return [
            'cta_text' => $hasProgress ? 'Go to Course' : 'Start Your Free Trial',
            'is_paid' => false,
            'has_progress' => $hasProgress
        ];
    }
}