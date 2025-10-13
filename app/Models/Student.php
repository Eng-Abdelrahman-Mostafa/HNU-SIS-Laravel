<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $primaryKey = 'student_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'student_id',
        'full_name_arabic',
        'email',
        'password_hash',
        'department_id',
        'current_level_id',
        'cgpa',
        'total_points',
        'earned_credit_hours',
        'studied_credit_hours',
        'actual_credit_hours',
        'status',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'cgpa' => 'decimal:2',
        'total_points' => 'decimal:2',
    ];

    // Relationships
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function academicLevel()
    {
        return $this->belongsTo(AcademicLevel::class, 'current_level_id', 'level_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id', 'student_id');
    }
}
