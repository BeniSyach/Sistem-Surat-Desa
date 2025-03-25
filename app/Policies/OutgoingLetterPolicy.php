<?php

namespace App\Policies;

use App\Models\OutgoingLetter;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class OutgoingLetterPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Semua user bisa melihat daftar surat keluar di desa mereka
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OutgoingLetter $outgoingLetter): bool
    {
        // User hanya bisa melihat surat keluar yang mereka buat
        return $user->id === $outgoingLetter->created_by;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Semua user bisa membuat surat keluar
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OutgoingLetter $outgoingLetter): bool
    {
        // Hanya pembuat surat yang bisa mengedit, dan hanya jika masih draft
        return $user->id === $outgoingLetter->created_by &&
            $outgoingLetter->status === OutgoingLetter::STATUS_DRAFT;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OutgoingLetter $outgoingLetter): bool
    {
        // Hanya pembuat surat yang bisa menghapus, dan hanya jika masih draft
        return $user->id === $outgoingLetter->created_by &&
            $outgoingLetter->status === OutgoingLetter::STATUS_DRAFT;
    }

    /**
     * Determine whether the user can submit the letter for approval.
     */
    public function submit(User $user, OutgoingLetter $outgoingLetter): bool
    {
        // Hanya pembuat surat yang bisa submit, dan hanya jika masih draft
        return $user->id === $outgoingLetter->created_by &&
            $outgoingLetter->canBeSubmitted();
    }

    /**
     * Determine whether the user can approve as Sekdes.
     */
    public function approveSekdes(User $user, OutgoingLetter $outgoingLetter): bool
    {
        // Hanya Sekdes yang bisa menyetujui surat yang menunggu persetujuan Sekdes
        return $user->role->name === 'Memparaf Surat' &&
            $user->village_id === $outgoingLetter->village_id &&
            $outgoingLetter->canBeApprovedBySekdes();
    }

    /**
     * Determine whether the user can approve as Kades.
     */
    public function approveKades(User $user, OutgoingLetter $outgoingLetter): bool
    {
        // Hanya Kades yang bisa menyetujui surat yang menunggu persetujuan Kades
        return $user->role->name === 'Menandatangani Surat' &&
            $user->village_id === $outgoingLetter->village_id &&
            $outgoingLetter->canBeApprovedByKades();
    }

    /**
     * Determine whether the user can process the letter.
     */
    public function process(User $user, OutgoingLetter $outgoingLetter): bool
    {
        // Hanya Umum Desa yang bisa memproses surat yang sudah disetujui Kades
        return $user->role->name === 'Bagian Umum' &&
            $user->village_id === $outgoingLetter->village_id &&
            $outgoingLetter->canBeProcessed();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, OutgoingLetter $outgoingLetter): bool
    {
        // Hanya Admin yang bisa restore surat yang terhapus
        return $user->role->name === 'Admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, OutgoingLetter $outgoingLetter): bool
    {
        // Hanya Admin yang bisa menghapus permanen
        return $user->role->name === 'Admin';
    }

    /**
     * Determine whether the user can create a disposition for the letter.
     */
    public function createDisposition(User $user, OutgoingLetter $outgoingLetter): bool
    {
        // Only Kades can create dispositions, and only for processed letters
        return $user->role->name === 'Menandatangani Surat' && 
               $outgoingLetter->status === OutgoingLetter::STATUS_PROCESSED;
    }
} 