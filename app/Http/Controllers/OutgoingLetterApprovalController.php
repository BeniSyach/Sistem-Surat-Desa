<?php

namespace App\Http\Controllers;

use App\Models\OutgoingLetter;
use App\Models\IncomingLetter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OutgoingLetterApprovalController extends Controller
{
    /**
     * Display a listing of letters pending Sekdes approval.
     */
    public function sekdesApprovalList(Request $request)
    {
        $query = OutgoingLetter::with(['classification', 'department', 'village', 'creator'])
            ->where('status', OutgoingLetter::STATUS_PENDING_SEKDES)
            ->byVillage(auth()->user()->village_id);

        // Filter berdasarkan tanggal
        if ($request->filled(['start_date', 'end_date'])) {
            $query->byDateRange($request->start_date, $request->end_date);
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $letters = $query->latest()->paginate(10);
        return view('outgoing-letters.sekdes-approval', compact('letters'));
    }

    /**
     * Approve letter as Sekdes.
     */
    public function sekdesApprove(Request $request, OutgoingLetter $letter)
    {
        $this->authorize('approveSekdes', $letter);

        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $letter->approveBySekdes(auth()->user(), $validated['notes'] ?? null);

        return redirect()
            ->route('outgoing-letters.sekdes-approval')
            ->with('success', 'Surat keluar berhasil diparaf dan diteruskan ke Kades.');
    }

    /**
     * Reject letter as Sekdes.
     */
    public function sekdesReject(Request $request, OutgoingLetter $letter)
    {
        $this->authorize('approveSekdes', $letter);

        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $letter->rejectBySekdes(auth()->user(), $validated['rejection_reason']);

        return redirect()
            ->route('outgoing-letters.sekdes-approval')
            ->with('success', 'Surat keluar berhasil ditolak.');
    }

    /**
     * Display a listing of letters pending Kades approval.
     */
    public function kadesApprovalList(Request $request)
    {
        $query = OutgoingLetter::with(['classification', 'department', 'village', 'creator'])
            ->where('status', OutgoingLetter::STATUS_PENDING_KADES)
            ->byVillage(auth()->user()->village_id);

        // Filter berdasarkan tanggal
        if ($request->filled(['start_date', 'end_date'])) {
            $query->byDateRange($request->start_date, $request->end_date);
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $letters = $query->latest()->paginate(10);
        return view('outgoing-letters.kades-approval', compact('letters'));
    }

    /**
     * Approve letter as Kades.
     */
    public function kadesApprove(Request $request, OutgoingLetter $letter)
    {
        $this->authorize('approveKades', $letter);

        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $letter->approveByKades(auth()->user(), $validated['notes'] ?? null);

        return redirect()
            ->route('outgoing-letters.kades-approval')
            ->with('success', 'Surat keluar berhasil ditandatangani dan diteruskan ke Umum Desa.');
    }

    /**
     * Reject letter as Kades.
     */
    public function kadesReject(Request $request, OutgoingLetter $letter)
    {
        $this->authorize('approveKades', $letter);

        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $letter->rejectByKades(auth()->user(), $validated['rejection_reason']);

        return redirect()
            ->route('outgoing-letters.kades-approval')
            ->with('success', 'Surat keluar berhasil ditolak.');
    }

    /**
     * Display a listing of letters pending processing by Umum Desa.
     */
    public function umumProcessingList(Request $request)
    {
        $query = OutgoingLetter::with(['classification', 'department', 'village', 'creator'])
            ->where('status', OutgoingLetter::STATUS_PENDING_PROCESS)
            ->byVillage(auth()->user()->village_id);

        // Filter berdasarkan tanggal
        if ($request->filled(['start_date', 'end_date'])) {
            $query->byDateRange($request->start_date, $request->end_date);
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $letters = $query->latest()->paginate(10);
        return view('outgoing-letters.umum-processing', compact('letters'));
    }

    /**
     * Process letter as Umum Desa.
     */
    public function umumProcess(OutgoingLetter $letter)
    {
        $user = auth()->user();
        $village = $user->village;

        if (!$village) {
            return redirect()->back()->with('error', 'Anda belum terdaftar di instansi manapun.');
        }

        $kadesUser = User::whereHas('role', function ($query) {
            $query->where('name', 'Menandatangani Surat');
        })->where('village_id', $village->id)->first();

        if (!$kadesUser || !$kadesUser->signature) {
            return redirect()->back()
                ->with('error', 'Kepala Desa belum memiliki tanda tangan. Silakan tambahkan tanda tangan Kepala Desa terlebih dahulu.');
        }

        try {
            $letter->process($user);
            return redirect()->back()->with('success', 'Surat berhasil diproses.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses surat. ' . $e->getMessage());
        }
    }
}
