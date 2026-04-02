<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ClassSubject extends Model
{
    protected $table = 'class_subject';

    protected $fillable = ['class_id', 'subject_id', 'academic_year_id', 'credit_hour'];

    protected $casts = [
        'credit_hour' => 'integer',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}