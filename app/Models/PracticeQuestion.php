<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PracticeQuestion extends Model
{
    protected $fillable = [
        'class_name', 'subject', 'chapter', 'question_text',
        'option_a', 'option_b', 'option_c', 'option_d',
        'correct_option', 'explanation', 'difficulty',
    ];

    public function scopeForClass($query, $className)
    {
        return $query->where('class_name', $className);
    }

    public function scopeBySubject($query, $subject)
    {
        return $query->where('subject', $subject);
    }

    public function scopeByChapter($query, $chapter)
    {
        return $query->where('chapter', $chapter);
    }

    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }
}
