<?php
// app/Models/Test.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'title'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
      protected $hidden = [
        'created_at', 
        'updated_at'
    ];
}