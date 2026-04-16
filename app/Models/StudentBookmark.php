<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StudentBookmark extends Model
{
    protected $fillable = [
        'student_id', 'recordable_type', 'recordable_id',
    ];

    public function recordable(): MorphTo
    {
        return $this->morphTo();
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
