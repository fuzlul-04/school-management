<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = ['name', 'bangla_name', 'code', 'type', 'full_mark', 'pass_mark', 'status'];

    protected $casts = [
        'full_mark' => 'decimal:2',
        'pass_mark' => 'decimal:2',
    ];

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(Classe::class, 'class_subject', 'subject_id', 'class_id')
            ->withPivot('credit_hour')
            ->withTimestamps();
    }

    public function teacherSubjects(): HasMany
    {
        return $this->hasMany(TeacherSubject::class);
    }

    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}