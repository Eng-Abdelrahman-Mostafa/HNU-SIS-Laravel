<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Student extends Authenticatable implements FilamentUser
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
}
