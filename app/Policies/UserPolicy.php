<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can manage signatures.
     */
    public function manageSignature(User $user, User $target): bool
    {
        // Admin can manage any user's signature
        if ($user->isAdmin()) {
            return true;
        }

        // Umum Desa can only manage Kades signature in their village
        if ($user->isUmumDesa() && $target->isKades()) {
            return $user->village_id === $target->village_id;
        }

        return false;
    }
} 