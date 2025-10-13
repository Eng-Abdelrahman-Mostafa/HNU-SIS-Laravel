<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $primaryKey = 'department_id';

    public $timestamps = false;

    protected $fillable = [
        'department_code',
        'department_prefix',
        'department_name',
    ];

    // Relationships
    public function students()
    {
        return $this->hasMany(Student::class, 'department_id', 'department_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'department_id', 'department_id');
    }

    public function programRequirements()
    {
        return $this->hasMany(ProgramRequirement::class, 'department_id', 'department_id');
    }

    public function instructors()
    {
        return $this->hasMany(Instructor::class, 'department_id', 'department_id');
    }
}
