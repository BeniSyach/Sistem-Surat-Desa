<?php

namespace App\Http\Controllers;

use App\Models\IncomingLetter;
use App\Models\LetterClassification;
use App\Models\Village;
use App\Models\User;
use App\Models\IncomingLetterDisposition;
use App\Models\OutgoingLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IncomingLetterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = IncomingLetter::with(['classification'])
            ->byVillage(auth()->user()->village_id)->where('receiver_user_id', auth()->id());

        // Filter berdasarkan tanggal
        if ($request->filled(['start_date', 'end_date'])) {
            $query->byDateRange($request->start_date, $request->end_date);
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $incomingLetters = $query->latest()->paginate(10);
        return view('incoming-letters.index', compact('incomingLetters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', IncomingLetter::class);
        $classifications = LetterClassification::all();
        $villages = Village::all();
        $users = User::with(['role', 'village'])
            ->where('is_active', true)
            ->whereIn('role_id', [1, 2, 3]) // 1=kades, 2=sekdes, 3=kasi
            ->get();

        return view('incoming-letters.create', compact('classifications', 'villages', 'users'));
    }

    public function getUsersByVillage(Village $village)
    {
        $users = User::where('is_active', true)
            ->whereIn('role_id', [1, 2, 3]) // 1=kades, 2=sekdes, 3=kasi
            ->with(['role', 'village']) // eager load role and village relations
            ->get()
            ->map(function ($user) {
                // Tambahkan informasi desa ke nama user
                $user->name = $user->name . ' (' . $user->village->name . ')';
                return $user;
            });

        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', IncomingLetter::class);

        $validated = $request->validate([
            'letter_number' => 'required|string|max:255',
            'letter_date' => 'required|date',
            'received_date' => 'required|date',
            'sender' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'classification_id' => 'required|exists:letter_classifications,id',
            'confidentiality' => 'required|in:biasa,rahasia,umum',
            'attachment' => 'required|file|mimes:pdf|max:2048',
            'notes' => 'nullable|string',
            'sender_village_id' => 'required|exists:villages,id',
            'receiver_user_id' => 'required|exists:users,id',
        ]);

        // Get receiver's village_id from selected user
        $receiverUser = User::findOrFail($validated['receiver_user_id']);
        $validated['receiver_village_id'] = $receiverUser->village_id;
        
        $validated['created_by'] = auth()->id();
        $validated['village_id'] = auth()->user()->village_id;

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments/incoming', 'public');
            $validated['attachment'] = $path;
        }

        $incomingLetter = IncomingLetter::create($validated);

        return redirect()
            ->route('incoming-letters.show', $incomingLetter)
            ->with('success', 'Surat masuk berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(IncomingLetter $incomingLetter)
    {
        $this->authorize('view', $incomingLetter);
        
        $incomingLetter->load(['creator', 'classification']);
        
        // Get users for disposition
        $users = User::with('role')
            ->whereHas('role', function($query) {
                $query->whereNotIn('name', ['Admin']);
            })->get();
            
        return view('incoming-letters.show', [
            'incomingLetter' => $incomingLetter,
            'users' => $users,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IncomingLetter $incomingLetter)
    {
        $this->authorize('update', $incomingLetter);
        $classifications = LetterClassification::all();
        return view('incoming-letters.edit', compact('incomingLetter', 'classifications'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IncomingLetter $incomingLetter)
    {
        $this->authorize('update', $incomingLetter);

        $validated = $request->validate([
            'letter_number' => 'required|string|max:255',
            'letter_date' => 'required|date',
            'received_date' => 'required|date',
            'sender' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'classification_id' => 'required|exists:letter_classifications,id',
            'confidentiality' => 'required|in:biasa,rahasia,umum',
            'attachment' => 'nullable|file|mimes:pdf|max:2048',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('attachment')) {
            if ($incomingLetter->attachment) {
                Storage::delete($incomingLetter->attachment);
            }
            $path = $request->file('attachment')->store('attachments/incoming');
            $validated['attachment'] = $path;
        }

        $incomingLetter->update($validated);

        return redirect()
            ->route('incoming-letters.show', $incomingLetter)
            ->with('success', 'Surat masuk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IncomingLetter $incomingLetter)
    {
        $this->authorize('delete', $incomingLetter);

        if ($incomingLetter->attachment) {
            Storage::delete($incomingLetter->attachment);
        }

        $incomingLetter->delete();

        return redirect()
            ->route('incoming-letters.index')
            ->with('success', 'Surat masuk berhasil dihapus.');
    }

    public function downloadAttachment(IncomingLetter $incomingLetter)
    {
        $this->authorize('view', $incomingLetter);

        if (!$incomingLetter->attachment) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($incomingLetter->attachment);
    }

    /**
     * Approve an incoming letter.
     */
    public function approve(Request $request, IncomingLetter $incomingLetter)
    {
        $this->authorize('update', $incomingLetter);

        $validated = $request->validate([
            'approval_notes' => 'nullable|string',
        ]);

        $incomingLetter->approve($validated['approval_notes'] ?? null);

        return redirect()
            ->route('incoming-letters.show', $incomingLetter)
            ->with('success', 'Surat berhasil disetujui.');
    }

    /**
     * Reject an incoming letter.
     */
    public function reject(Request $request, IncomingLetter $incomingLetter)
    {
        $this->authorize('update', $incomingLetter);

        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $incomingLetter->reject($validated['rejection_reason']);

        return redirect()
            ->route('incoming-letters.show', $incomingLetter)
            ->with('success', 'Surat berhasil ditolak dan pemberitahuan telah dikirim ke pengirim.');
    }

    /**
     * Create a disposition for an incoming letter.
     */
    public function createDisposition(Request $request, IncomingLetter $incomingLetter)
    {
        // Validate request
        $validated = $request->validate([
            'to_user_id' => 'required|array',
            'to_user_id.*' => 'exists:users,id',
            'notes' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        try {
            // Process for each recipient
            foreach ($validated['to_user_id'] as $userId) {
                // Get the recipient user
                $toUser = User::findOrFail($userId);
                
                // Create a new incoming letter as a disposition for the recipient
                $dispositionLetter = new IncomingLetter([
                    'letter_number' => $incomingLetter->letter_number,
                    'letter_date' => now(),
                    'received_date' => now(),
                    'sender' => auth()->user()->name . ' (' . auth()->user()->role->name . ')',
                    'subject' => 'Disposisi: ' . $incomingLetter->subject,
                    'description' => $incomingLetter->description,
                    'classification_id' => $incomingLetter->classification_id,
                    'confidentiality' => $incomingLetter->confidentiality,
                    'notes' => $validated['notes'] ?? 'Disposisi dari ' . auth()->user()->name,
                    'created_by' => auth()->id(),
                    'village_id' => $toUser->village_id,
                    'sender_village_id' => auth()->user()->village_id,
                    'receiver_village_id' => $toUser->village_id,
                    'receiver_user_id' => $userId,
                    'status' => 'processed', // Set status to finish
                ]);

                // Handle attachment
                if ($request->hasFile('attachment')) {
                    // Store attachment once and reuse the path
                    if (!isset($attachmentPath)) {
                        $attachmentPath = $request->file('attachment')->store('attachments/dispositions', 'public');
                    }
                    $dispositionLetter->attachment = $attachmentPath;
                } else if ($incomingLetter->attachment) {
                    // Use the original letter's attachment if no new one is provided
                    $dispositionLetter->attachment = $incomingLetter->attachment;
                }

                $dispositionLetter->save();

                // Create a new outgoing letter for the sender
                $outgoingLetter = new OutgoingLetter([
                    'letter_number' => $incomingLetter->letter_number,
                    'letter_date' => now(),
                    'subject' => 'Disposisi: ' . $incomingLetter->subject,
                    'content' => $incomingLetter->description,
                    'classification_id' => $incomingLetter->classification_id,
                    'confidentiality' => $incomingLetter->confidentiality,
                    'department_id' => auth()->user()->department_id ?? 1,
                    'village_id' => auth()->user()->village_id,
                    'created_by' => auth()->id(),
                    'status' => OutgoingLetter::STATUS_PROCESSED,
                    'processed_at' => now(),
                    'processed_by' => auth()->id(),
                    'signer_id' => auth()->id(),
                ]);

                // Handle attachment for outgoing letter
                if (isset($attachmentPath)) {
                    $outgoingLetter->attachment = $attachmentPath;
                } else if ($incomingLetter->attachment) {
                    $outgoingLetter->attachment = $incomingLetter->attachment;
                }

                $outgoingLetter->save();

                // Link the incoming and outgoing letters
                $dispositionLetter->related_outgoing_letter_id = $outgoingLetter->id;
                $dispositionLetter->save();
            }

            // Update original letter status to finish
            $incomingLetter->status = 'processed';
            $incomingLetter->save();

            $recipientCount = count($validated['to_user_id']);
            $message = $recipientCount > 1 
                ? "Disposisi berhasil dibuat dan dikirim ke {$recipientCount} penerima"
                : 'Disposisi berhasil dibuat dan dikirim ke ' . User::find($validated['to_user_id'][0])->name;

            return redirect()
                ->route('incoming-letters.show', $incomingLetter)
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Store a disposition for an incoming letter.
     */
    public function storeDisposition(Request $request, IncomingLetter $incomingLetter)
    {
        // Verify that the user is a Kades
        if (!auth()->user()->isKades()) {
            return redirect()->back()->with('error', 'Hanya Kades yang dapat membuat disposisi surat.');
        }
        
        $this->authorize('update', $incomingLetter);

        $validated = $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'notes' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Create a new incoming letter as a disposition
        $toUser = User::findOrFail($validated['to_user_id']);
        
        $dispositionLetter = new IncomingLetter([
            'letter_number' => $incomingLetter->letter_number,
            'letter_date' => now(),
            'received_date' => now(),
            'sender' => auth()->user()->name,
            'subject' => 'Disposisi: ' . $incomingLetter->subject,
            'description' => $incomingLetter->description,
            'classification_id' => $incomingLetter->classification_id,
            'confidentiality' => $incomingLetter->confidentiality,
            'notes' => $validated['notes'],
            'created_by' => auth()->id(),
            'village_id' => $toUser->village_id,
            'sender_village_id' => auth()->user()->village_id,
            'receiver_village_id' => $toUser->village_id,
            'receiver_user_id' => $toUser->id,
            'related_outgoing_letter_id' => $incomingLetter->related_outgoing_letter_id,
            'status' => 'finish',
        ]);

        // Handle attachment
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments/dispositions', 'public');
            $dispositionLetter->attachment = $path;
        } else if ($incomingLetter->attachment) {
            // Use the original letter's attachment if no new one is provided
            $dispositionLetter->attachment = $incomingLetter->attachment;
        }

        $dispositionLetter->save();

        // Update the status of the original letter
        $incomingLetter->status = 'finish';
        $incomingLetter->save();

        return redirect()
            ->route('incoming-letters.show', $incomingLetter)
            ->with('success', 'Surat berhasil didisposisikan ke ' . $toUser->name);
    }

    /**
     * Submit letter for approval by Sekdes
     */
    public function submit(IncomingLetter $incomingLetter)
    {
        $this->authorize('update', $incomingLetter);

        $incomingLetter->status = 'pending_approval';
        $incomingLetter->submitted_at = now();
        $incomingLetter->save();

        return redirect()
            ->route('incoming-letters.show', $incomingLetter)
            ->with('success', 'Surat berhasil dikirim untuk disetujui.');
    }

    /**
     * Approve an incoming letter by Sekdes.
     */
    public function sekdesApprove(Request $request, IncomingLetter $incomingLetter)
    {
        // Verify that the user is a Sekdes
        if (!auth()->user()->isSekdes()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }

        // Verify that the letter is in pending_approval status
        if ($incomingLetter->status !== 'pending_approval') {
            return redirect()->back()->with('error', 'Surat tidak dalam status menunggu persetujuan.');
        }

        // Update the letter status
        $incomingLetter->status = 'approved';
        $incomingLetter->approval_notes = $request->approval_notes;
        $incomingLetter->sekdes_approved_at = now();
        $incomingLetter->sekdes_id = auth()->id();
        $incomingLetter->save();
        $this->forwardToUser($request, $incomingLetter);
  

        return redirect()->route('incoming-letters.show', $incomingLetter)
            ->with('success', 'Surat berhasil disetujui dan diparaf.');
    }

    /**
     * Reject an incoming letter by Sekdes.
     */
    public function sekdesReject(Request $request, IncomingLetter $incomingLetter)
    {
        // Verify that the user is a Sekdes
        if (!auth()->user()->isSekdes()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }

        // Verify that the letter is in pending_approval status
        if ($incomingLetter->status !== 'pending_approval') {
            return redirect()->back()->with('error', 'Surat tidak dalam status menunggu persetujuan.');
        }

        // Validate the request
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        // Update the letter status
        $incomingLetter->status = 'rejected';
        $incomingLetter->rejection_reason = $request->rejection_reason;
        $incomingLetter->save();

        // Get the original sender (creator) of the letter
        $sender = User::find($incomingLetter->created_by);
        
        if ($sender) {
            // Create a new incoming letter for the sender to notify about rejection
            $rejectionLetter = new IncomingLetter([
                'letter_number' => 'Rejected-' . $incomingLetter->id,
                'letter_date' => now(),
                'received_date' => now(),
                'sender' => auth()->user()->name . ' (Sekdes)',
                'subject' => 'Penolakan: ' . $incomingLetter->subject,
                'description' => $incomingLetter->description,
                'classification_id' => $incomingLetter->classification_id,
                'confidentiality' => $incomingLetter->confidentiality,
                'attachment' => $incomingLetter->attachment,
                'notes' => 'Surat ditolak dengan alasan: ' . $request->rejection_reason,
                'created_by' => auth()->id(),
                'village_id' => $sender->village_id,
                'sender_village_id' => auth()->user()->village_id,
                'receiver_village_id' => $sender->village_id,
                'receiver_user_id' => $sender->id,
                'status' => 'received',
                'related_incoming_letter_id' => $incomingLetter->id,
            ]);
            $rejectionLetter->save();
        }

        return redirect()->route('incoming-letters.show', $incomingLetter)
            ->with('success', 'Surat berhasil ditolak dan pengirim telah diberitahu.');
    }

    /**
     * Forward an incoming letter to Kades.
     */
    public function forwardToKades(Request $request, IncomingLetter $incomingLetter)
    {
        // Verify that the user is a Sekdes
        if (!auth()->user()->isSekdes()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }

        // Verify that the letter is in pending_approval status
        if ($incomingLetter->status !== 'pending_approval') {
            return redirect()->back()->with('error', 'Surat tidak dalam status menunggu persetujuan.');
        }

        // Find Kades user from the same village
        $kades = User::whereHas('role', function($query) {
                $query->where('name', 'Kades');
            })
            ->where('village_id', auth()->user()->village_id)
            ->where('is_active', true)
            ->first();

        if (!$kades) {
            return redirect()->back()->with('error', 'Tidak dapat menemukan Kades yang aktif di desa Anda.');
        }

        // Update the letter status
        $incomingLetter->status = 'approved';
        $incomingLetter->approval_notes = $request->notes;
        $incomingLetter->sekdes_approved_at = now();
        $incomingLetter->sekdes_id = auth()->id();
        $incomingLetter->save();

        // Create a new incoming letter for Kades
        $kadesLetter = new IncomingLetter([
            'letter_number' => $incomingLetter->letter_number,
            'letter_date' => $incomingLetter->letter_date,
            'received_date' => now(),
            'sender' => auth()->user()->name . ' (Sekdes)',
            'subject' => $incomingLetter->subject,
            'description' => $incomingLetter->description,
            'classification_id' => $incomingLetter->classification_id,
            'confidentiality' => $incomingLetter->confidentiality,
            'attachment' => $incomingLetter->attachment,
            'notes' => $request->notes ?? 'Diteruskan dari Sekdes',
            'created_by' => auth()->id(),
            'village_id' => $kades->village_id,
            'sender_village_id' => auth()->user()->village_id,
            'receiver_village_id' => $kades->village_id,
            'receiver_user_id' => $kades->id,
            'status' => 'pending_approval',
            'related_incoming_letter_id' => $incomingLetter->id,
        ]);
        $kadesLetter->save();

        return redirect()->route('incoming-letters.show', $incomingLetter)
            ->with('success', 'Surat berhasil diparaf dan diteruskan ke Kades.');
    }

    /**
     * Forward an approved letter to another user by Sekdes.
     */
    public function forwardToUser(Request $request, IncomingLetter $incomingLetter)
    {

        // Validate request
        $validated = $request->validate([
            'forward_to' => 'required|exists:users,id',
            'approval_notes' => 'nullable|string',
        ]);

        // Get the recipient user
        $toUser = User::findOrFail($validated['forward_to']);

        // Begin transaction to ensure data consistency
        DB::beginTransaction();

        try {
            // Create a new outgoing letter as a record
            $outgoingLetter = new OutgoingLetter([
                'letter_number' => $incomingLetter->letter_number,
                'letter_date' => now(),
                'subject' => 'Diteruskan: ' . $incomingLetter->subject,
                'content' => $incomingLetter->description,
                'classification_id' => $incomingLetter->classification_id,
                'confidentiality' => $incomingLetter->confidentiality,
                'department_id' => auth()->user()->department_id ?? 1, // Default ke department_id 1 jika tidak ada
                'village_id' => auth()->user()->village_id,
                'created_by' => auth()->id(),
                'status' => OutgoingLetter::STATUS_PROCESSED, // Langsung diproses karena ini hanya riwayat
                'processed_at' => now(),
                'processed_by' => auth()->id(),
                'signer_id' => auth()->id(),
                'attachment' => $incomingLetter->attachment,
            ]);
            $outgoingLetter->save();

            // Determine the status based on the sender's role
            $status = auth()->user()->isKades() ? 'signed' : 'approved';
            $senderTitle = auth()->user()->isKades() ? ' (Kades)' : ' (Sekdes)';

            // Create a new incoming letter for the recipient
            $forwardedLetter = new IncomingLetter([
                'letter_number' => $incomingLetter->letter_number,
                'letter_date' => $incomingLetter->letter_date,
                'received_date' => now(),
                'sender' => auth()->user()->name . $senderTitle,
                'subject' => $incomingLetter->subject,
                'description' => $incomingLetter->description,
                'classification_id' => $incomingLetter->classification_id,
                'confidentiality' => $incomingLetter->confidentiality,
                'attachment' => $incomingLetter->attachment,
                'notes' => $validated['approval_notes'] ?? 'Diteruskan dari ' . (auth()->user()->isKades() ? 'Kades' : 'Sekdes'),
                'created_by' => auth()->id(),
                'village_id' => $toUser->village_id,
                'sender_village_id' => auth()->user()->village_id,
                'receiver_village_id' => $toUser->village_id,
                'receiver_user_id' => $toUser->id,
                'status' => $status,
                'related_incoming_letter_id' => $incomingLetter->id,
                'related_outgoing_letter_id' => $outgoingLetter->id,
            ]);
            
            // If the sender is Kades, add the signature information
            if (auth()->user()->isKades()) {
                $forwardedLetter->kades_signed_at = now();
                $forwardedLetter->kades_id = auth()->id();
            }
            
            $forwardedLetter->save();

            DB::commit();

            return redirect()->route('incoming-letters.show', $incomingLetter)
                ->with('success', 'Surat berhasil diteruskan ke ' . $toUser->name . '.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Sign an incoming letter by Kades.
     */
    public function kadesSign(Request $request, IncomingLetter $incomingLetter)
    {
        // Verify that the user is a Kades
        if (!auth()->user()->isKades()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }

        // Verify that the letter is in approved or pending_approval status
        if (!in_array($incomingLetter->status, ['approved', 'pending_approval'])) {
            return redirect()->back()->with('error', 'Surat tidak dalam status yang dapat ditandatangani.');
        }

        // Update the letter status
        $incomingLetter->status = 'signed';
        $incomingLetter->kades_signed_at = now();
        $incomingLetter->kades_id = auth()->id();
        $incomingLetter->kades_notes = $request->approval_notes;
        $incomingLetter->save();

        $this->forwardToUser($request, $incomingLetter);

        return redirect()->route('incoming-letters.show', $incomingLetter)
            ->with('success', 'Surat berhasil ditandatangani.');
    }

    /**
     * Process an incoming letter by Umum Desa.
     */
    public function process(Request $request, IncomingLetter $incomingLetter)
    {
        // Verify that the user is an Umum Desa
        if (!auth()->user()->isUmumDesa()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }

        // Verify that the letter is in signed status
        if ($incomingLetter->status !== 'signed') {
            return redirect()->back()->with('error', 'Surat tidak dalam status ditandatangani.');
        }

        // Validate the request
        $request->validate([
            'final_letter_number' => 'required|string',
        ]);

        // Update the letter status
        $incomingLetter->status = 'processed';
        $incomingLetter->final_letter_number = $request->final_letter_number;
        $incomingLetter->letter_number = $request->final_letter_number;
        $incomingLetter->process_notes = $request->process_notes;
        $incomingLetter->processed_at = now();
        $incomingLetter->processed_by = auth()->id();
        $incomingLetter->save();

        // Generate QR Code for the letter
        $qrCodePath = $this->generateQRCode($incomingLetter);
        if ($qrCodePath) {
            $incomingLetter->qr_code = $qrCodePath;
            $incomingLetter->save();
        }

        // Create a new outgoing letter for Umum Desa
        $outgoingLetter = new OutgoingLetter([
            'letter_number' => $request->final_letter_number,
            'letter_date' => now(),
            'subject' => 'Diproses: ' . $incomingLetter->subject,
            'content' => $incomingLetter->description,
            'classification_id' => $incomingLetter->classification_id,
            'confidentiality' => $incomingLetter->confidentiality,
            'department_id' => auth()->user()->department_id,
            'village_id' => auth()->user()->village_id,
            'created_by' => auth()->id(),
            'status' => OutgoingLetter::STATUS_PROCESSED, // Langsung diproses karena ini pemrosesan
            'processed_at' => now(),
            'processed_by' => auth()->id(),
            'signer_id' => auth()->id(),
            'attachment' => $incomingLetter->attachment,
        ]);

        // Jika ada QR Code, tambahkan ke surat keluar
        if ($qrCodePath) {
            $outgoingLetter->qr_code = $qrCodePath;
        }

        $outgoingLetter->save();

        // Link the incoming and outgoing letters
        $incomingLetter->related_outgoing_letter_id = $outgoingLetter->id;
        $incomingLetter->save();

        return redirect()->route('incoming-letters.show', $incomingLetter)
            ->with('success', 'Surat berhasil diproses.');
    }

    /**
     * Generate QR Code for the letter.
     */
    private function generateQRCode(IncomingLetter $incomingLetter)
    {
        // Check if QR Code package is available
        if (!class_exists('SimpleSoftwareIO\QrCode\Facades\QrCode')) {
            return null;
        }

        // Generate QR Code content
        $qrContent = json_encode([
            'id' => $incomingLetter->id,
            'letter_number' => $incomingLetter->final_letter_number,
            'subject' => $incomingLetter->subject,
            'date' => $incomingLetter->letter_date->format('Y-m-d'),
            'processed_at' => $incomingLetter->processed_at ? $incomingLetter->processed_at->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
            'verify_url' => url(route('incoming-letters.verify', $incomingLetter, false)),
        ]);

        // Generate QR Code image
        $qrCodeImage = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
            ->size(200)
            ->errorCorrection('H')
            ->generate($qrContent);

        // Save QR Code image
        $qrCodePath = 'qrcodes/incoming/' . uniqid() . '.png';
        Storage::disk('public')->put($qrCodePath, $qrCodeImage);

        return $qrCodePath;
    }

    /**
     * Mark a disposition as read.
     */
    public function markDispositionAsRead(IncomingLetterDisposition $disposition)
    {
        // Verify that the user is the recipient of the disposition
        if (auth()->id() !== $disposition->to_user_id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }

        // Mark the disposition as read
        $disposition->read_at = now();
        $disposition->save();

        return redirect()->back()->with('success', 'Disposisi berhasil ditandai sebagai dibaca.');
    }

    /**
     * Delete a disposition.
     */
    public function deleteDisposition(IncomingLetterDisposition $disposition)
    {
        // Verify that the user is the sender of the disposition
        if (auth()->id() !== $disposition->from_user_id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }

        // Delete the disposition
        $disposition->delete();

        return redirect()->back()->with('success', 'Disposisi berhasil dihapus.');
    }

    /**
     * Verify an incoming letter.
     * This method is publicly accessible without authentication.
     */
    public function verify(IncomingLetter $incomingLetter)
    {
        // Only allow verification for processed letters
        if ($incomingLetter->status !== 'processed' && $incomingLetter->status !== 'finish') {
            return view('incoming-letters.verify', [
                'incomingLetter' => null,
                'error' => 'Surat belum diproses atau tidak dapat diverifikasi.'
            ]);
        }

        // Load necessary relationships
        $incomingLetter->load(['classification', 'creator']);

        return view('incoming-letters.verify', [
            'incomingLetter' => $incomingLetter,
            'error' => null
        ]);
    }

    /**
     * Download Kades signature as image.
     */
    public function downloadSignature(IncomingLetter $incomingLetter)
    {
        // Verify that the letter has been signed by Kades
        if (!$incomingLetter->kades_signed_at) {
            return redirect()->back()->with('error', 'Surat belum ditandatangani oleh Kades.');
        }

        // Get the Kades user
        $kades = User::find($incomingLetter->kades_id);
        
        if (!$kades || !$kades->signature) {
            return redirect()->back()->with('error', 'Tanda tangan Kades tidak ditemukan.');
        }

        // Return the signature image for download
        return Storage::disk('public')->download(
            $kades->signature, 
            'tanda-tangan-kades-' . $incomingLetter->id . '.png'
        );
    }
}
