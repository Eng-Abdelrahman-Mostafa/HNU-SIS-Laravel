<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EnrollmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_Enrollment');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Enrollment $enrollment): bool
    {
        return $user->can('view_Enrollment');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_Enrollment');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Enrollment $enrollment): bool
    {
        return $user->can('update_Enrollment');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Enrollment $enrollment): bool
    {
        return $user->can('delete_Enrollment');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_Enrollment');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Enrollment $enrollment): bool
    {
        return $user->can('create_Enrollment');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->can('update_Enrollment');
    }

    /**
     * Determine whether the user can approve enrollment.
     */
    public function approve(User $user, Enrollment $enrollment): bool
    {
        return $user->can('approve_Enrollment');
    }

    /**
     * Determine whether the user can reject enrollment.
     */
    public function reject(User $user, Enrollment $enrollment): bool
    {
        return $user->can('reject_Enrollment');
    }

    /**
     * Determine whether the user can export enrollments.
     */
    public function export(User $user): bool
    {
        return $user->can('export_Enrollment');
    }
}
