<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeScale extends Model
{
    protected $primaryKey = 'grade_id';

    public $timestamps = false;

    protected $fillable = [
        'grade_letter',
        'min_percentage',
        'max_percentage',
        'grade_points',
        'status',
    ];
}
