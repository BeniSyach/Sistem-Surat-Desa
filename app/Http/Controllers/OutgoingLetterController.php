<?php

namespace App\Http\Controllers;

use App\Models\OutgoingLetter;
use App\Models\IncomingLetter;
use App\Models\LetterClassification;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class OutgoingLetterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', OutgoingLetter::class);

        $query = OutgoingLetter::with(['classification', 'department', 'village'])
            ->byVillage(auth()->user()->village_id)
            ->where('created_by', auth()->id());

        // Filter berdasarkan tanggal
        if ($request->filled(['start_date', 'end_date'])) {
            $query->byDateRange($request->start_date, $request->end_date);
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $letters = $query->latest()->paginate(10);
        return view('outgoing-letters.index', compact('letters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', OutgoingLetter::class);

        $classifications = LetterClassification::all();
        $departments = Department::all();
        
        // Get potential signers (Kades and other officials who can sign letters)
        $signers = User::whereHas('role', function($query) {
                $query->whereIn('name', [
                    'Menandatangani Surat',
                    'Memparaf Surat',
                    'Pembuat Surat'
                ]);
            })
            ->where('is_active', true)
            ->get();

        if ($signers->isEmpty()) {
            return redirect()
                ->route('outgoing-letters.index')
                ->with('error', 'Tidak dapat membuat surat karena tidak ada penandatangan yang tersedia.');
        }

        // Get all users except the current user as potential recipients
        $recipients = User::with('role')
            ->where('id', '!=', auth()->id())
            ->where('is_active', true)
            ->get();

        return view('outgoing-letters.create', compact('classifications', 'departments', 'signers', 'recipients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', OutgoingLetter::class);

        $validated = $request->validate([
            'letter_date' => 'required|date',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'classification_id' => 'required|exists:letter_classifications,id',
            'confidentiality' => 'required|in:biasa,rahasia,umum',
            'department_id' => 'required|exists:departments,id',
            'recipient_id' => 'required|exists:users,id',
            'signer_id' => 'required|exists:users,id',
            'letter_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:5120', // 5MB max
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120', // 5MB max
        ]);

        DB::beginTransaction();

        try {
            $validated['created_by'] = auth()->id();
            $validated['village_id'] = auth()->user()->village_id;
            $validated['status'] = OutgoingLetter::STATUS_DRAFT;

            // Verify the signer exists and is active
            $signer = User::where('id', $validated['signer_id'])
                ->where('is_active', true)
                ->first();
            
            if (!$signer) {
                return redirect()
                    ->route('outgoing-letters.index')
                    ->with('error', 'Penandatangan yang dipilih tidak valid atau tidak aktif.');
            }

            // Handle letter file upload
            if ($request->hasFile('letter_file')) {
                $letterPath = $request->file('letter_file')->store('letters/outgoing', 'public');
                $validated['letter_file'] = $letterPath;
            }

            // Handle single attachment
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('attachments/outgoing', 'public');
                $validated['attachment'] = $attachmentPath;
            }

            // Create outgoing letter
            $letter = OutgoingLetter::create($validated);

            // Get recipient user
            $recipient = User::findOrFail($request->recipient_id);

            // Buat surat masuk untuk penerima
            $incomingLetter = new IncomingLetter([
                'letter_number' => 'Draft-' . $letter->id,
                'letter_date' => $validated['letter_date'],
                'received_date' => now(),
                'sender' => auth()->user()->name,
                'subject' => $validated['subject'],
                'description' => $validated['content'],
                'classification_id' => $validated['classification_id'],
                'letter_file' => $validated['letter_file'],
                'attachment' => $validated['attachment'] ?? null,
                'notes' => 'Surat ini dibuat oleh ' . auth()->user()->name,
                'created_by' => auth()->id(),
                'village_id' => $recipient->village_id,
                'sender_village_id' => auth()->user()->village_id,
                'receiver_village_id' => $recipient->village_id,
                'receiver_user_id' => $recipient->id,
                'related_outgoing_letter_id' => $letter->id,
                'status' => 'draft',
            ]);

            $incomingLetter->save();

            DB::commit();

            return redirect()
                ->route('outgoing-letters.show', $letter)
                ->with('success', 'Surat keluar berhasil dibuat dan dikirim ke penerima.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('outgoing-letters.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(OutgoingLetter $outgoingLetter)
    {
        $this->authorize('view', $outgoingLetter);
        
        $outgoingLetter->load(['creator', 'sekdes', 'kades', 'processor', 'dispositions.fromUser', 'dispositions.toUser']);
        $users = User::with('role')->where('village_id', auth()->user()->village_id)
            ->whereHas('role', function($query) {
                $query->whereNotIn('name', ['Admin']);
            })->get();

        return view('outgoing-letters.show', [
            'letter' => $outgoingLetter,
            'users' => $users,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OutgoingLetter $outgoingLetter)
    {
        $this->authorize('update', $outgoingLetter);

        $classifications = LetterClassification::all();
        $departments = Department::all();
        
        // Get potential signers (officials who can sign letters)
        $signers = User::whereHas('role', function($query) {
                $query->whereIn('name', [
                    'Menandatangani Surat',
                    'Memparaf Surat',
                    'Pembuat Surat'
                ]);
            })
            ->where('village_id', auth()->user()->village_id)
            ->where('is_active', true)
            ->get();

        return view('outgoing-letters.edit', compact('outgoingLetter', 'classifications', 'departments', 'signers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OutgoingLetter $outgoingLetter)
    {
        $this->authorize('update', $outgoingLetter);

        $validated = $request->validate([
            'letter_date' => 'required|date',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'classification_id' => 'required|exists:letter_classifications,id',
            'confidentiality' => 'required|in:biasa,rahasia,umum',
            'department_id' => 'required|exists:departments,id',
            'signer_id' => 'required|exists:users,id',
            'letter_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120', // 5MB max
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120', // 5MB max per file
        ]);

        DB::beginTransaction();

        try {
            // Verify the signer exists and is active
            $signer = User::where('id', $validated['signer_id'])
                ->where('is_active', true)
                ->first();
            
            if (!$signer) {
                return redirect()
                    ->back()
                    ->with('error', 'Penandatangan yang dipilih tidak valid atau tidak aktif.');
            }

            // Handle letter file upload
            if ($request->hasFile('letter_file')) {
                if ($outgoingLetter->letter_file) {
                    Storage::disk('public')->delete($outgoingLetter->letter_file);
                }
                $letterPath = $request->file('letter_file')->store('letters/outgoing', 'public');
                $validated['letter_file'] = $letterPath;
            }

            // Handle multiple attachments
            if ($request->hasFile('attachments')) {
                // Delete old attachments
                if ($outgoingLetter->attachment) {
                    $oldAttachments = json_decode($outgoingLetter->attachment, true);
                    foreach ($oldAttachments as $oldPath) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }

                // Upload new attachments
                $attachmentPaths = [];
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('attachments/outgoing', 'public');
                    $attachmentPaths[] = $path;
                }
                $validated['attachment'] = json_encode($attachmentPaths);
            }

            $outgoingLetter->update($validated);

            // Check if there's a related incoming letter
            $existingIncomingLetter = IncomingLetter::where('related_outgoing_letter_id', $outgoingLetter->id)->first();
            
            if ($existingIncomingLetter) {
                // Update existing incoming letter with new data
                $existingIncomingLetter->letter_date = $outgoingLetter->letter_date;
                $existingIncomingLetter->subject = $outgoingLetter->subject;
                $existingIncomingLetter->description = $outgoingLetter->content;
                $existingIncomingLetter->classification_id = $outgoingLetter->classification_id;
                $existingIncomingLetter->confidentiality = $outgoingLetter->confidentiality;
                $existingIncomingLetter->letter_file = $outgoingLetter->letter_file;
                if ($outgoingLetter->attachment) {
                    $existingIncomingLetter->attachment = $outgoingLetter->attachment;
                }
                $existingIncomingLetter->save();
            }

            DB::commit();

            return redirect()
                ->route('outgoing-letters.show', $outgoingLetter)
                ->with('success', 'Surat keluar berhasil diperbarui dan surat masuk terkait telah diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OutgoingLetter $outgoingLetter)
    {
        $this->authorize('delete', $outgoingLetter);

        if ($outgoingLetter->attachment) {
            Storage::delete($outgoingLetter->attachment);
        }

        $outgoingLetter->delete();

        return redirect()
            ->route('outgoing-letters.index')
            ->with('success', 'Surat keluar berhasil dihapus.');
    }

    /**
     * Submit the letter for approval.
     */
    public function submit(Request $request, OutgoingLetter $outgoingLetter)
    {
        $this->authorize('submit', $outgoingLetter);
        
        $outgoingLetter->submit();

        return redirect()
            ->route('outgoing-letters.show', $outgoingLetter)
            ->with('success', 'Surat keluar berhasil diajukan untuk mendapat paraf Sekdes.');
    }

    /**
     * Download attachment.
     */
    public function downloadAttachment(OutgoingLetter $outgoingLetter)
    {
        $this->authorize('view', $outgoingLetter);

        if (!$outgoingLetter->attachment) {
            abort(404, 'File tidak ditemukan.');
        }

        // If attachment is a JSON string of multiple files, download the first one
        $attachments = json_decode($outgoingLetter->attachment, true);
        $path = is_array($attachments) ? $attachments[0] : $outgoingLetter->attachment;

        return Storage::disk('public')->download($path);
    }

    /**
     * Download letter file.
     */
    public function downloadLetterFile(OutgoingLetter $outgoingLetter)
    {
        $this->authorize('view', $outgoingLetter);

        if (!$outgoingLetter->letter_file) {
            abort(404, 'File surat tidak ditemukan.');
        }

        return Storage::disk('public')->download($outgoingLetter->letter_file);
    }

    /**
     * Verify the outgoing letter.
     */
    public function verify(OutgoingLetter $outgoingLetter)
    {
        return view('outgoing-letters.verify', compact('outgoingLetter'));
    }

    /**
     * Process the letter by Umum Desa.
     */
    public function process(Request $request, OutgoingLetter $outgoingLetter)
    {
        $this->authorize('process', $outgoingLetter);
        
        $validated = $request->validate([
            'letter_number' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Process the outgoing letter
            $outgoingLetter->process(auth()->user(), $validated['letter_number']);

            // Update the corresponding incoming letter with the letter number
            $incomingLetter = IncomingLetter::where('subject', $outgoingLetter->subject)
                ->where('created_by', $outgoingLetter->created_by)
                ->where('letter_date', $outgoingLetter->letter_date)
                ->first();

            if ($incomingLetter) {
                $incomingLetter->letter_number = $validated['letter_number'];
                $incomingLetter->save();
            }

            DB::commit();

            return redirect()
                ->route('outgoing-letters.show', $outgoingLetter)
                ->with('success', 'Surat keluar berhasil diproses dan diberi nomor.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('outgoing-letters.show', $outgoingLetter)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
