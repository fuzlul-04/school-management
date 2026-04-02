<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeeStructure extends Model
{
    protected $table = 'fee_structures';

    protected $fillable = ['fee_type_id', 'class_id', 'academic_year_id', 'amount', 'is_mandatory'];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_mandatory' => 'boolean',
    ];

    public function feeType(): BelongsTo
    {
        return $this->belongsTo(FeeType::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}