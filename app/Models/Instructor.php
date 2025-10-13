<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $primaryKey = 'instructor_id';

    public $timestamps = false;

    protected $fillable = [
        'instructor_code',
        'first_name',
        'last_name',
        'full_name_arabic',
        'email',
        'phone',
        'department_id',
        'title',
        'status',
    ];

    // Relationships
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function courseInstructorAssignments()
    {
        return $this->hasMany(CourseInstructorAssignment::class, 'instructor_id', 'instructor_id');
    }
}
