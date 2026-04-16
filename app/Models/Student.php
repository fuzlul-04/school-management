<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'academic_year_id', 'class_id', 'section_id', 'student_id',
        'first_name', 'last_name', 'bangla_name', 'date_of_birth', 'birth_certificate_number',
        'gender', 'religion', 'blood_group', 'phone', 'present_address', 'permanent_address',
        'profile_image', 'admission_date', 'previous_school', 'ssc_roll', 'ssc_registration',
        'ssc_passing_year', 'ssc_gpa', 'status', 'concession_type', 'concession_amount',
        'concession_reason',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'admission_date' => 'date',
        'ssc_passing_year' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'class_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function guardians(): BelongsToMany
    {
        return $this->belongsToMany(Guardian::class, 'student_guardian')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function liveExamResults(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }

    public function contentProgress(): HasMany
    {
        return $this->hasMany(StudentContentProgress::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(StudentBookmark::class);
    }

    public function practiceResults(): HasMany
    {
        return $this->hasMany(PracticeResult::class);
    }

    public function qnaQuestions(): HasMany
    {
        return $this->hasMany(QnaQuestion::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(StudentPayment::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeBySection($query, $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }
}
