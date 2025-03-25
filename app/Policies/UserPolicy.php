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
        if ($user->role->name === 'Admin') {
            return true;
        }

        // Bagian Umum can only manage Menandatangani Surat signature in their village
        if ($user->role->name === 'Bagian Umum' && $target->role->name === 'Menandatangani Surat') {
            return $user->village_id === $target->village_id;
        }

        return false;
    }
} 