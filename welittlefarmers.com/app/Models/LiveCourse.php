<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveCourse extends Model
{
    use HasFactory;

    protected $table = 'live_courses';

    protected $fillable = [
        'name',
        'email',
        'school_name',
        'age',
        'date',
        'course_name',
    ];
}
