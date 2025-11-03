<?php

namespace App\Policies;

use App\Models\Prerequisite;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrerequisitePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_Prerequisite');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Prerequisite $prerequisite): bool
    {
        return $user->can('view_Prerequisite');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_Prerequisite');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Prerequisite $prerequisite): bool
    {
        return $user->can('update_Prerequisite');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Prerequisite $prerequisite): bool
    {
        return $user->can('delete_Prerequisite');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_Prerequisite');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Prerequisite $prerequisite): bool
    {
        return $user->can('create_Prerequisite');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->can('update_Prerequisite');
    }
}
