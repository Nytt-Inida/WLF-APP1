<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoAccessLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'ip_address',
        'user_agent',
        'token_hash',
        'accessed_at',
        'expires_at',
        'is_valid'
    ];

    protected $casts = [
        'accessed_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_valid' => 'boolean',
    ];

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with Lesson
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Check if token is still valid
     */
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    /**
     * Invalidate this access log
     */
    public function invalidate()
    {
        $this->update(['is_valid' => false]);
    }

    /**
     * Clean up expired logs (run this in a scheduled command)
     */
    public static function cleanupExpired()
    {
        return static::where('expires_at', '<', now())
            ->where('is_valid', true)
            ->update(['is_valid' => false]);
    }
}