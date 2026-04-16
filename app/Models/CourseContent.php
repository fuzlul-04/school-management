<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseContent extends Model
{
    protected $fillable = [
        'class_name', 'subject', 'chapter_number', 'chapter_title',
        'notes_text', 'pdf_path', 'video_url',
    ];

    public function progress(): HasMany
    {
        return $this->hasMany(StudentContentProgress::class, 'content_id');
    }

    public function scopeForClass($query, $className)
    {
        return $query->where('class_name', $className);
    }

    public function scopeBySubject($query, $subject)
    {
        return $query->where('subject', $subject);
    }
}
