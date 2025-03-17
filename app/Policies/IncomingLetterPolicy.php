<?php

namespace App\Policies;

use App\Models\IncomingLetter;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IncomingLetterPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Semua user bisa melihat daftar surat masuk
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, IncomingLetter $incomingLetter): bool
    {
        // Semua user bisa melihat detail surat masuk
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Semua user bisa membuat surat masuk
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, IncomingLetter $incomingLetter): bool
    {
        // User hanya bisa mengedit surat masuk yang dibuat oleh dirinya sendiri
        // atau surat yang ditujukan untuk dirinya
        return $user->id === $incomingLetter->created_by || 
               $user->id === $incomingLetter->receiver_user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, IncomingLetter $incomingLetter): bool
    {
        // User hanya bisa menghapus surat masuk yang dibuat oleh dirinya sendiri
        return $user->id === $incomingLetter->created_by;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, IncomingLetter $incomingLetter): bool
    {
        // Semua user bisa mengembalikan surat masuk yang dihapus
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, IncomingLetter $incomingLetter): bool
    {
        // Semua user bisa menghapus permanen surat masuk
        return true;
    }
}
