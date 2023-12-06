<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class RevisorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function revisor(User $user)
    {
        return $user->rol === 3 || $user->rol === 4;
    }

}
