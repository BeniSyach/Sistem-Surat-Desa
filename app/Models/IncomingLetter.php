<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'letter_number',
        'letter_date',
        'received_date',
        'sender',
        'subject',
        'description',
        'classification_id',
        'confidentiality',
        'attachment',
        'notes',
        'created_by',
        'village_id',
        'sender_village_id',
        'receiver_village_id',
        'receiver_user_id',
        'related_outgoing_letter_id',
        'status',
        'approval_notes',
        'rejection_reason',
        'sekdes_id',
        'kades_id',
        'sekdes_approved_at',
        'kades_approved_at',
        'submitted_at',
    ];

    protected $casts = [
        'letter_date' => 'date',
        'received_date' => 'date',
        'sekdes_approved_at' => 'datetime',
        'kades_approved_at' => 'datetime',
        'submitted_at' => 'datetime',
        'kades_signed_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    // Status constants
    const STATUS_RECEIVED = 'received';
    const STATUS_PENDING_APPROVAL = 'pending_approval';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_DISPOSITIONED = 'dispositioned';
    const STATUS_REJECTED_SEKDES = 'rejected_sekdes';
    const STATUS_REJECTED_KADES = 'rejected_kades';

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function classification()
    {
        return $this->belongsTo(LetterClassification::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function senderVillage()
    {
        return $this->belongsTo(Village::class, 'sender_village_id');
    }

    public function receiverVillage()
    {
        return $this->belongsTo(Village::class, 'receiver_village_id');
    }

    public function receiverUser()
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }

    public function relatedOutgoingLetter()
    {
        return $this->belongsTo(OutgoingLetter::class, 'related_outgoing_letter_id');
    }

    public function sekdes()
    {
        return $this->belongsTo(User::class, 'sekdes_id');
    }

    public function kades()
    {
        return $this->belongsTo(User::class, 'kades_id');
    }

    public function dispositions()
    {
        return $this->hasMany(IncomingLetterDisposition::class);
    }

    /**
     * Get the user who processed the letter.
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Scopes
    public function scopeByVillage($query, $villageId)
    {
        return $query->where('village_id', $villageId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('letter_date', [$startDate, $endDate]);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('letter_number', 'like', "%{$search}%")
                ->orWhere('sender', 'like', "%{$search}%")
                ->orWhere('subject', 'like', "%{$search}%");
        });
    }

    // Helper methods
    public function canBeSubmitted(): bool
    {
        return $this->status === self::STATUS_RECEIVED || 
               $this->status === self::STATUS_REJECTED_SEKDES || 
               $this->status === self::STATUS_REJECTED_KADES;
    }

    public function isPendingApproval(): bool
    {
        return $this->status === self::STATUS_PENDING_APPROVAL;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED || 
               $this->status === self::STATUS_REJECTED_SEKDES || 
               $this->status === self::STATUS_REJECTED_KADES;
    }

    public function isDispositioned(): bool
    {
        return $this->status === self::STATUS_DISPOSITIONED;
    }

    // Methods for approval workflow
    public function approve($notes = null)
    {
        $this->status = self::STATUS_APPROVED;
        $this->approval_notes = $notes;
        $this->save();

        // If this is related to an outgoing letter, update its status
        if ($this->related_outgoing_letter_id) {
            $outgoingLetter = $this->relatedOutgoingLetter;
            
            // Check if the receiver is Sekdes
            if ($this->receiverUser->role->name === 'Sekdes') {
                $outgoingLetter->approveBySekdes($this->receiverUser, $notes);
            }
            // Check if the receiver is Kades
            elseif ($this->receiverUser->role->name === 'Kades') {
                $outgoingLetter->approveByKades($this->receiverUser, $notes);
            }
        }
    }

    public function reject($notes)
    {
        $this->status = self::STATUS_REJECTED;
        $this->approval_notes = $notes;
        $this->save();

        // If this is related to an outgoing letter, update its status
        if ($this->related_outgoing_letter_id) {
            $outgoingLetter = $this->relatedOutgoingLetter;
            
            // Check if the receiver is Sekdes
            if ($this->receiverUser->role->name === 'Sekdes') {
                $outgoingLetter->rejectBySekdes($this->receiverUser, $notes);
            }
            // Check if the receiver is Kades
            elseif ($this->receiverUser->role->name === 'Kades') {
                $outgoingLetter->rejectByKades($this->receiverUser, $notes);
            }

            // Create a new incoming letter for the original creator
            $creator = $outgoingLetter->creator;
            
            $incomingLetter = new self([
                'letter_number' => 'Rejected-' . $outgoingLetter->id,
                'letter_date' => now(),
                'received_date' => now(),
                'sender' => $this->receiverUser->name . ' (' . $this->receiverUser->role->name . ')',
                'subject' => '[PERLU PERBAIKAN] ' . $outgoingLetter->subject,
                'description' => "Surat Anda dengan nomor draft " . $outgoingLetter->id . " telah ditolak.\n\n" .
                                "Alasan Penolakan:\n" . $notes . "\n\n" .
                                "Silakan perbaiki surat sesuai dengan catatan di atas dan kirim ulang untuk mendapatkan persetujuan.",
                'classification_id' => $outgoingLetter->classification_id,
                'confidentiality' => $outgoingLetter->confidentiality,
                'attachment' => $outgoingLetter->attachment,
                'notes' => 'Surat ini ditolak dan perlu perbaikan. Silakan edit surat keluar terkait dan kirim ulang.',
                'created_by' => $this->receiverUser->id,
                'village_id' => $creator->village_id,
                'sender_village_id' => $this->receiverUser->village_id,
                'receiver_village_id' => $creator->village_id,
                'receiver_user_id' => $creator->id,
                'related_outgoing_letter_id' => $outgoingLetter->id,
                'status' => self::STATUS_RECEIVED,
            ]);

            $incomingLetter->save();
        }
    }
}
