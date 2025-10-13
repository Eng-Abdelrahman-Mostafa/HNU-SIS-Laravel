<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramRequirement extends Model
{
    protected $primaryKey = 'requirement_id';

    public $timestamps = false;

    protected $fillable = [
        'department_id',
        'course_id',
        'level_id',
        'requirement_type',
    ];

    // Relationships
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function academicLevel()
    {
        return $this->belongsTo(AcademicLevel::class, 'level_id', 'level_id');
    }
}
