<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicLevel extends Model
{
    protected $primaryKey = 'level_id';

    public $timestamps = false;

    protected $fillable = [
        'level_name',
        'level_number',
        'min_credit_hours',
        'max_credit_hours',
    ];

    // Relationships
    public function students()
    {
        return $this->hasMany(Student::class, 'current_level_id', 'level_id');
    }

    public function programRequirements()
    {
        return $this->hasMany(ProgramRequirement::class, 'level_id', 'level_id');
    }
}
