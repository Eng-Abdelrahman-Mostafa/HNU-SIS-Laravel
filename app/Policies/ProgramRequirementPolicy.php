<?php

namespace App\Policies;

use App\Models\ProgramRequirement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProgramRequirementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_ProgramRequirement');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProgramRequirement $programRequirement): bool
    {
        return $user->can('view_ProgramRequirement');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_ProgramRequirement');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProgramRequirement $programRequirement): bool
    {
        return $user->can('update_ProgramRequirement');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProgramRequirement $programRequirement): bool
    {
        return $user->can('delete_ProgramRequirement');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_ProgramRequirement');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, ProgramRequirement $programRequirement): bool
    {
        return $user->can('create_ProgramRequirement');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->can('update_ProgramRequirement');
    }
}
