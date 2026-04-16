<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LiveExam extends Model
{
    protected $fillable = [
        'class_name', 'subject', 'start_time', 'duration_minutes',
        'total_marks', 'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'duration_minutes' => 'integer',
        'total_marks' => 'integer',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }

    public function scopeForClass($query, $className)
    {
        return $query->where('class_name', $className);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['live', 'upcoming']);
    }

    public function isAvailable(): bool
    {
        $now = now();
        $start = $this->start_time;
        $end = $start->copy()->addMinutes($this->duration_minutes);

        return $now->between($start, $end);
    }
}
