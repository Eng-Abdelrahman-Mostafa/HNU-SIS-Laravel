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
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
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
