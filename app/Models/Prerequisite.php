<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prerequisite extends Model
{
    protected $primaryKey = 'prerequisite_id';

    public $timestamps = false;

    protected $fillable = [
        'course_id',
        'prerequisite_course_id',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function prerequisiteCourse()
    {
        return $this->belongsTo(Course::class, 'prerequisite_course_id', 'course_id');
    }
}
