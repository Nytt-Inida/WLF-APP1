<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value_inr',
        'value_usd',
        'min_order_inr',
        'min_order_usd',
        'max_usage',
        'usage_count',
        'expires_at',
        'user_id',
        'is_active',
    ];
    
    // Accessor for backward compatibility (if code uses usage_limit)
    public function getUsageLimitAttribute()
    {
        return $this->max_usage;
    }

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isValid()
    {
        if (!$this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->max_usage && $this->usage_count >= $this->max_usage) return false;
        
        return true;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
