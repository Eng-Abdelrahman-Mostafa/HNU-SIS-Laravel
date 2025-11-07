<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $primaryKey = 'semester_id';

    public $timestamps = false;

    protected $fillable = [
        'semester_code',
        'semester_name',
        'year',
        'start_date',
        'end_date',
        'is_active',
        'student_registeration_start_at',
        'student_registeration_end_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'student_registeration_start_at' => 'datetime',
        'student_registeration_end_at' => 'datetime',
    ];

    // Relationships
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'semester_id', 'semester_id');
    }

    public function courseInstructorAssignments()
    {
        return $this->hasMany(CourseInstructorAssignment::class, 'semester_id', 'semester_id');
    }
}
