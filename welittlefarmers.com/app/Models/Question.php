<?php

// app/Models/Question.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'question',
        'option1',
        'option2',
        'option3',
        'option4',
        'correct_option',
    ];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
      protected $hidden = [
        'created_at', 
        'updated_at'
    ];
      public function section()
    {
        return $this->belongsTo(Section::class);
    }
}