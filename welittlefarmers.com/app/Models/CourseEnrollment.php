<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'enrollment_date',
        'payment_status',
        'amount',
        'notes',
        'coupon_code',
        'discount_amount'
    ];

    protected $casts = [
        'enrollment_date' => 'datetime',
    ];

    /**
     * Get the user who enrolled
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the enrolled course
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
