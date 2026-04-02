<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admission extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'application_id', 'academic_year_id', 'class_id', 'section_id',
        'first_name', 'last_name', 'bangla_name', 'date_of_birth', 'gender', 'religion',
        'phone', 'email', 'present_address', 'permanent_address',
        'father_name', 'father_phone', 'father_nid', 'mother_name', 'mother_phone', 'mother_nid',
        'previous_school', 'ssc_roll', 'ssc_passing_year', 'ssc_gpa', 'profile_image',
        'status', 'remarks', 'interview_date', 'merit_position',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'interview_date' => 'date',
        'ssc_passing_year' => 'integer',
        'ssc_gpa' => 'decimal:2',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}