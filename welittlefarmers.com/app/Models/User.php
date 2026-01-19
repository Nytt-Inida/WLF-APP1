<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'age',
        'category',
        'otp',
        'otp_created_at',
        'school_name',
        'country',
        'referral_code',
        'pending_course_id',
        'payment_status',
        'payment_submitted_at',
        'payment_verified_at',
        'verified_by',
    ];

    protected $casts = [
        'created_at' => 'datetime:UTC',
        'updated_at' => 'datetime:UTC',
        'otp_created_at' => 'datetime:UTC',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'payment_submitted_at' => 'datetime',
            'payment_verified_at' => 'datetime',
        ];
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Course::class, 'favorites', 'user_id', 'course_id');
    }

    // New Method to Check Payment Status
    // Relationship with UserProgress
    public function userProgress()
    {
        return $this->hasMany(UserProgress::class);
    }

    // Method to check if the user has paid for a specific course
    public function hasPaid($courseId)
    {
        return $this->userProgress()
            ->where('course_id', $courseId)
            ->where('payment_status', 'completed')
            ->exists();
    }



    // Payment and verify by admins
    public function pendingCourse()
    {
        return $this->belongsTo(Course::class, 'pending_course_id');
    }

    /**
     * Get user's course enrollments
     */
    public function courseEnrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    /**
     * Get the admin who verified the payment
     */
    public function verifiedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }

    /**
     * Get user's course progress
     */
    public function courseProgress()
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * Check if user has pending payment
     */
    public function hasPendingPayment()
    {
        return $this->payment_status == 1;
    }

    /**
     * Check if user payment is verified
     */
    public function hasVerifiedPayment()
    {
        return $this->payment_status == 2;
    }

    /**
     * Get the specific pending enrollment record
     */
    public function pendingEnrollment()
    {
        return $this->hasOne(CourseEnrollment::class)
            ->where('course_id', $this->pending_course_id)
            ->where('payment_status', 'pending')
            ->latest();
    }

    /**
     * Get payment status text
     */
    public function getPaymentStatusTextAttribute()
    {
        switch ($this->payment_status) {
            case 1:
                return 'Pending';
            case 2:
                return 'Verified';
            default:
                return 'Unpaid';
        }
    }

    // Boot method to auto-generate referral code
    protected static function booted()
    {
        static::created(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = self::generateUniqueReferralCode();
                $user->save();
            }
        });
    }

    public static function generateUniqueReferralCode()
    {
        do {
            $code = strtoupper('LFA-' . \Illuminate\Support\Str::random(6));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    // Relationships for Referral System
    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function references()
    {
        return $this->hasMany(Referral::class, 'referred_user_id');
    }

    public function discountCodes()
    {
        return $this->hasMany(DiscountCode::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
