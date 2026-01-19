<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollmentInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'name',
        'email',
        'phone',
        'status',
        'admin_notes',
        'contacted_at',
        'contacted_by',
        'reminder_count',
        'last_reminder_sent_at',
        'reminder_notes',
    ];

    protected $casts = [
        'contacted_at' => 'datetime',
        'last_reminder_sent_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function contactedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'contacted_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeContacted($query)
    {
        return $query->where('status', 'contacted');
    }

    public function scopeEnrolled($query)
    {
        return $query->where('status', 'enrolled');
    }

    // Helper methods
    public function markAsContacted($adminId, $notes = null)
    {
        $this->update([
            'status' => 'contacted',
            'contacted_at' => now(),
            'contacted_by' => $adminId,
            'admin_notes' => $notes,
        ]);
    }

    public function markAsEnrolled($notes = null)
    {
        $this->update([
            'status' => 'enrolled',
            'admin_notes' => $notes,
        ]);
    }

    public function markAsRejected($notes = null)
    {
        $this->update([
            'status' => 'rejected',
            'admin_notes' => $notes,
        ]);
    }

    // Status badge color helper
    public function getStatusBadgeClass()
    {
        return match ($this->status) {
            'pending' => 'bg-warning',
            'contacted' => 'bg-info',
            'enrolled' => 'bg-success',
            'rejected' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    // Status display text
    public function getStatusText()
    {
        return ucfirst($this->status);
    }

    // Check if eligible for reminder (not enrolled and contacted/pending)
    public function isEligibleForReminder()
    {
        return in_array($this->status, ['pending', 'contacted']) &&
            $this->status !== 'enrolled' &&
            $this->status !== 'rejected';
    }

    // Check if reminder was sent recently (within last 3 days)
    public function wasReminderSentRecently($days = 3)
    {
        if (!$this->last_reminder_sent_at) {
            return false;
        }

        return $this->last_reminder_sent_at->gt(now()->subDays($days));
    }

    // Get days since last reminder
    public function getDaysSinceLastReminder()
    {
        if (!$this->last_reminder_sent_at) {
            return null;
        }

        return $this->last_reminder_sent_at->diffInDays(now());
    }
}
