<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'title'];

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
     public function articles()
    {
        return $this->hasMany(Article::class);
    }
     public function questions()
    {
        return $this->hasMany(Question::class);
    }
}