<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    use HasFactory;
     protected $table = 'user_progress';

    protected $fillable = [
        'user_id',
        'course_id',
        'payment_status',
        'is_completed',
    ];
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}