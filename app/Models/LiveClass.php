<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveClass extends Model
{
    protected $fillable = [
        'class_name', 'subject', 'teacher_name', 'join_link',
        'start_time', 'end_time', 'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    protected $dates = ['start_time', 'end_time'];

    public function scopeForClass($query, $className)
    {
        return $query->where('class_name', $className);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('start_time', now()->toDateString());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', now())->orderBy('start_time');
    }
}
