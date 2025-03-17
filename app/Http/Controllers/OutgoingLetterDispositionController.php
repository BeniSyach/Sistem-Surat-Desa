<?php

namespace App\Http\Controllers;

use App\Models\OutgoingLetter;
use App\Models\OutgoingLetterDisposition;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OutgoingLetterDispositionController extends Controller
{
    /**
     * Store a newly created disposition in storage.
     */
    public function store(Request $request, OutgoingLetter $outgoingLetter)
    {
        $this->authorize('createDisposition', $outgoingLetter);

        $validated = $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        // Verify the recipient exists and is active
        $recipient = User::where('id', $validated['to_user_id'])
            ->where('is_active', true)
            ->first();
        
        if (!$recipient) {
            return redirect()
                ->back()
                ->with('error', 'Penerima disposisi yang dipilih tidak valid atau tidak aktif.');
        }

        // Create the disposition
        $disposition = new OutgoingLetterDisposition([
            'outgoing_letter_id' => $outgoingLetter->id,
            'from_user_id' => Auth::id(),
            'to_user_id' => $validated['to_user_id'],
            'notes' => $validated['notes'],
        ]);

        $disposition->save();

        return redirect()
            ->route('outgoing-letters.show', $outgoingLetter)
            ->with('success', 'Disposisi berhasil dibuat.');
    }

    /**
     * Mark a disposition as read.
     */
    public function markAsRead(OutgoingLetterDisposition $disposition)
    {
        // Check if the current user is the recipient of the disposition
        if (Auth::id() != $disposition->to_user_id) {
            return redirect()
                ->back()
                ->with('error', 'Anda tidak memiliki akses untuk menandai disposisi ini sebagai telah dibaca.');
        }

        $disposition->markAsRead();

        return redirect()
            ->back()
            ->with('success', 'Disposisi telah ditandai sebagai dibaca.');
    }

    /**
     * Remove the specified disposition from storage.
     */
    public function destroy(OutgoingLetterDisposition $disposition)
    {
        // Check if the current user is the creator of the disposition
        if (Auth::id() != $disposition->from_user_id) {
            return redirect()
                ->back()
                ->with('error', 'Anda tidak memiliki akses untuk menghapus disposisi ini.');
        }

        $outgoingLetter = $disposition->outgoingLetter;
        $disposition->delete();

        return redirect()
            ->route('outgoing-letters.show', $outgoingLetter)
            ->with('success', 'Disposisi berhasil dihapus.');
    }
}
