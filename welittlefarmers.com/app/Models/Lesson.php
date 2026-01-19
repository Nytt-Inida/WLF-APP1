<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'course_detail_id',  // Foreign key to associate this lesson with a course detail
        'title',
        'video_url',
        'duration',
        'section_id',
        'poster',
        'vtt_path',
        'subtitle_status',
        'subtitle_error'
    ];

    // Define the relationship between Lesson and Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Define the relationship between Lesson and CourseDetail
    public function courseDetail()
    {
        return $this->belongsTo(CourseDetail::class);
    }
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    // app/Models/Lesson.php

    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function lessonProgress()
    {
        return $this->hasOne(LessonProgress::class);
    }
}
