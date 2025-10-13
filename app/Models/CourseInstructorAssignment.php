<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseInstructorAssignment extends Model
{
    protected $primaryKey = 'assignment_id';

    public $timestamps = false;

    protected $fillable = [
        'course_id',
        'instructor_id',
        'semester_id',
        'section_number',
        'student_count',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id', 'instructor_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'semester_id');
    }
}
