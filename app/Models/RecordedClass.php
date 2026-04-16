<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecordedClass extends Model
{
    protected $fillable = [
        'class_name', 'subject', 'chapter', 'title',
        'video_url', 'thumbnail', 'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'date',
    ];

    public function bookmarks(): HasMany
    {
        return $this->hasMany(StudentBookmark::class, 'recordable_id')->where('recordable_type', self::class);
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
