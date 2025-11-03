<?php

namespace App\Policies;

use App\Models\AcademicLevel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AcademicLevelPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_AcademicLevel');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AcademicLevel $academicLevel): bool
    {
        return $user->can('view_AcademicLevel');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_AcademicLevel');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AcademicLevel $academicLevel): bool
    {
        return $user->can('update_AcademicLevel');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AcademicLevel $academicLevel): bool
    {
        return $user->can('delete_AcademicLevel');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_AcademicLevel');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, AcademicLevel $academicLevel): bool
    {
        return $user->can('create_AcademicLevel');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->can('update_AcademicLevel');
    }
}
