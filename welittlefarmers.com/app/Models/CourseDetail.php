<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'course_id',
        'chapter_title',
        'description',
        'video_url',
        'user_id',
    ];
     public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
     protected $hidden = [
        'created_at', 
        'updated_at'
    ];
}