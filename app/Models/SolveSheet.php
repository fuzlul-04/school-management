<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolveSheet extends Model
{
    protected $fillable = [
        'class_name', 'subject', 'exam_type', 'year', 'title', 'file_path',
    ];

    public function scopeForClass($query, $className)
    {
        return $query->where('class_name', $className);
    }

    public function scopeBySubject($query, $subject)
    {
        return $query->where('subject', $subject);
    }

    public function scopeByExamType($query, $examType)
    {
        return $query->where('exam_type', $examType);
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }
}
