<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $primaryKey = 'enrollment_id';

    protected $fillable = [
        'student_id',
        'course_id',
        'semester_id',
        'enrollment_date',
        'status',
        'is_retake',
        'grade_points',
        'comment',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'is_retake' => 'boolean',
        'grade_points' => 'decimal:2',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'semester_id');
    }

    public function courseGrade()
    {
        return $this->hasOne(StudentCourseGrade::class, 'enrollment_id', 'enrollment_id');
    }
}

