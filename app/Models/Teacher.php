<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id', 'employee_id', 'first_name', 'last_name', 'bangla_name',
        'date_of_birth', 'gender', 'religion', 'blood_group', 'phone',
        'emergency_phone', 'nationality', 'nid_number', 'birth_certificate_number',
        'present_address', 'permanent_address', 'profile_image', 'join_date',
        'designation', 'qualification', 'salary', 'currency', 'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'join_date' => 'date',
        'salary' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function teacherSubjects(): HasMany
    {
        return $this->hasMany(TeacherSubject::class);
    }

    public function teacherClasses(): HasMany
    {
        return $this->hasMany(TeacherClass::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'teacher_subjects')
            ->withTimestamps();
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(Classe::class, 'teacher_classes')
            ->withTimestamps();
    }

    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByDesignation($query, $designation)
    {
        return $query->where('designation', $designation);
    }
}