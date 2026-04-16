<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PracticeResult extends Model
{
    protected $fillable = [
        'student_id', 'subject', 'chapter', 'score', 'total', 'accuracy', 'taken_at',
    ];

    protected $casts = [
        'taken_at' => 'datetime',
        'score' => 'integer',
        'total' => 'integer',
        'accuracy' => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
