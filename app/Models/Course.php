<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $primaryKey = 'course_id';

    public $timestamps = false;

    protected $fillable = [
        'course_code',
        'course_name',
        'credit_hours',
        'department_id',
        'course_type',
        'category',
    ];

    // Relationships
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'course_id', 'course_id');
    }

    public function prerequisites()
    {
        return $this->hasMany(Prerequisite::class, 'course_id', 'course_id');
    }

    public function isPrerequisiteFor()
    {
        return $this->hasMany(Prerequisite::class, 'prerequisite_course_id', 'course_id');
    }

    public function programRequirements()
    {
        return $this->hasMany(ProgramRequirement::class, 'course_id', 'course_id');
    }

    public function courseInstructorAssignments()
    {
        return $this->hasMany(CourseInstructorAssignment::class, 'course_id', 'course_id');
    }
}
