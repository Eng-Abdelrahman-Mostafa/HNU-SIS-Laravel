<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentCourseGrade extends Model
{
    protected $primaryKey = 'grade_id';

    protected $fillable = [
        'enrollment_id',
        'marks',
        'grade_percent',
        'grade_letter',
        'points',
        'credit_hours',
        'act_credit_hours',
    ];

    protected $casts = [
        'marks' => 'decimal:2',
        'grade_percent' => 'decimal:2',
        'points' => 'decimal:2',
        'credit_hours' => 'integer',
        'act_credit_hours' => 'integer',
    ];

    // Relationships
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id', 'enrollment_id');
    }
}
