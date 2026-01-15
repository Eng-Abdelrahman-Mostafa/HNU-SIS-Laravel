<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Student extends Authenticatable implements FilamentUser, HasName
{
    use Notifiable;

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

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'student';
    }

    public function getFilamentName(): string
    {
        return $this->full_name_arabic ?? $this->student_id ?? 'Student';
    }

    public function getAuthPassword(): string
    {
        return $this->password_hash ?? '';
    }

    public function setPasswordHashAttribute(?string $value): void
    {
        if ($value === null || $value === '') {
            $this->attributes['password_hash'] = $value;

            return;
        }

        $this->attributes['password_hash'] = Hash::needsRehash($value)
            ? Hash::make($value)
            : $value;
    }

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

    // Helper methods for course registration
    public function getMaxAllowedCourses(): int
    {
        $cgpa = $this->cgpa ?? 0;

        if ($cgpa <= 1) {
            return 4;
        } elseif ($cgpa > 1 && $cgpa < 2) {
            return 5;
        } elseif ($cgpa >= 2 && $cgpa < 3) {
            return 6;
        } else {
            return 7;
        }
    }

    public function getEnrollmentsForSemester(int $semesterId)
    {
        return $this->enrollments()
            ->where('semester_id', $semesterId)
            ->where('status', '!=', 'dropped')
            ->get();
    }

    public function getCompletedCourses()
    {
        return \App\Models\StudentCourseGrade::whereHas('enrollment', function ($query) {
            $query->where('student_id', $this->student_id);
        })
            ->whereNotIn('grade_letter', ['F', 'DN', 'W'])
            ->with('enrollment.course')
            ->get()
            ->pluck('enrollment.course');
    }
}
