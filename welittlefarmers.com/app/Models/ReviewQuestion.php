<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'question',
        'option_1',
        'option_2',
        'option_3',
        'option_4',
        'option_5',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function answers()
    {
        return $this->hasMany(ReviewAnswer::class);
    }

    /**
     * Get all options as an array
     */
    public function getOptionsAttribute()
    {
        return [
            $this->option_1,
            $this->option_2,
            $this->option_3,
            $this->option_4,
            $this->option_5
        ];
    }
}


