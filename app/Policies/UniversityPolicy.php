<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class UniversityPolicy
{
    /**
     * Create a new policy instance.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }
    public function update(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {

        return $user->isAdmin();
    }
}
