<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    protected $fillable = ['student_id', 'exam_id', 'academic_year_id', 'total_mark', 'gpa', 'grade', 'rank', 'remarks'];

    protected $casts = [
        'total_mark' => 'decimal:2',
        'gpa' => 'decimal:2',
        'rank' => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function scopeByExam($query, $examId)
    {
        return $query->where('exam_id', $examId);
    }

    public function scopeByRank($query, $direction = 'asc')
    {
        return $query->orderBy('rank', $direction);
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }
}