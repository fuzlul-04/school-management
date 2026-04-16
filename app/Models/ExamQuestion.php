<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamQuestion extends Model
{
    protected $fillable = [
        'exam_id', 'question_text', 'option_a', 'option_b',
        'option_c', 'option_d', 'correct_option',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(LiveExam::class);
    }
}
