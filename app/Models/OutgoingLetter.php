<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class OutgoingLetter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'letter_number',
        'letter_date',
        'subject',
        'content',
        'letter_file',
        'attachment',
        'classification_id',
        'confidentiality',
        'department_id',
        'village_id',
        'created_by',
        'signer_id',
        'status',
        'sekdes_notes',
        'kades_notes',
        'rejection_reason',
    ];

    protected $casts = [
        'letter_date' => 'date',
        'submitted_at' => 'datetime',
        'sekdes_approved_at' => 'datetime',
        'kades_approved_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    // Status Constants
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING_SEKDES = 'pending_sekdes';
    const STATUS_APPROVED_SEKDES = 'approved_sekdes';
    const STATUS_REJECTED_SEKDES = 'rejected_sekdes';
    const STATUS_PENDING_KADES = 'pending_kades';
    const STATUS_APPROVED_KADES = 'approved_kades';
    const STATUS_REJECTED_KADES = 'rejected_kades';
    const STATUS_PENDING_PROCESS = 'pending_process';
    const STATUS_PROCESSED = 'processed';

    // Relationships
    public function classification()
    {
        return $this->belongsTo(LetterClassification::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function signer()
    {
        return $this->belongsTo(User::class, 'signer_id');
    }

    public function sekdes()
    {
        return $this->belongsTo(User::class, 'sekdes_approved_by');
    }

    public function kades()
    {
        return $this->belongsTo(User::class, 'kades_approved_by');
    }

    public function umum()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function rejector()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function dispositions()
    {
        return $this->hasMany(OutgoingLetterDisposition::class);
    }

    // Scopes
    public function scopeByVillage($query, $villageId)
    {
        return $query->where('village_id', $villageId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('letter_date', [$startDate, $endDate]);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('letter_number', 'like', "%{$search}%")
                ->orWhere('subject', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%");
        });
    }

    // Status Methods
    public function submit()
    {
        $this->status = self::STATUS_PENDING_SEKDES;
        $this->submitted_at = now();
        $this->save();

        $existingIncomingLetter = IncomingLetter::where('related_outgoing_letter_id', $this->id)->first();
        $existingIncomingLetter->status = 'pending_approval';

        $existingIncomingLetter->save();

        // Get Sekdes user from the same village
        $sekdes = User::whereHas('role', function($query) {
                $query->where('name', 'Sekdes');
            })
            ->where('village_id', $this->village_id)
            ->where('is_active', 1)
            ->first();

        if ($sekdes) {
            // Check if an incoming letter already exists for this outgoing letter
            $existingIncomingLetter = IncomingLetter::where('related_outgoing_letter_id', $this->id)->first();
            
            if (!$existingIncomingLetter) {
                // Buat surat masuk untuk Sekdes sebagai penerima
                $incomingLetter = new IncomingLetter([
                    'letter_number' => 'Draft-' . $this->id,
                    'letter_date' => $this->letter_date,
                    'received_date' => now(),
                    'sender' => $this->creator->name,
                    'subject' => $this->subject,
                    'description' => $this->content,
                    'classification_id' => $this->classification_id,
                    'confidentiality' => $this->confidentiality,
                    'attachment' => $this->attachment,
                    'notes' => 'Surat ini memerlukan paraf Sekdes',
                    'created_by' => $this->created_by,
                    'village_id' => $sekdes->village_id,
                    'sender_village_id' => $this->village_id,
                    'receiver_village_id' => $sekdes->village_id,
                    'receiver_user_id' => $sekdes->id,
                    'related_outgoing_letter_id' => $this->id,
                    'status' => 'pending_approval',
                ]);

                $incomingLetter->save();
            } else {
                // Update existing incoming letter with current data
                $existingIncomingLetter->letter_date = $this->letter_date;
                $existingIncomingLetter->subject = $this->subject;
                $existingIncomingLetter->description = $this->content;
                $existingIncomingLetter->classification_id = $this->classification_id;
                $existingIncomingLetter->confidentiality = $this->confidentiality;
                if ($this->attachment) {
                    $existingIncomingLetter->attachment = $this->attachment;
                }
                $existingIncomingLetter->save();
            }
        }

        // TODO: Send notification to Sekdes
    }

    public function approveBySekdes(User $sekdes, ?string $notes = null)
    {
        $this->status = self::STATUS_PENDING_KADES;
        $this->sekdes_approved_at = now();
        $this->sekdes_approved_by = $sekdes->id;
        $this->sekdes_notes = $notes;
        $this->save();

        // TODO: Send notification to Kades
    }

    public function rejectBySekdes(User $sekdes, string $reason)
    {
        $this->status = self::STATUS_REJECTED_SEKDES;
        $this->rejected_by = $sekdes->id;
        $this->rejection_reason = $reason;
        $this->save();

        // TODO: Send notification to creator
    }

    public function approveByKades(User $kades, ?string $notes = null)
    {
        $this->status = self::STATUS_PENDING_PROCESS;
        $this->kades_approved_at = now();
        $this->kades_approved_by = $kades->id;
        $this->kades_notes = $notes;
        $this->save();

        // TODO: Send notification to Umum Desa
    }

    public function rejectByKades(User $kades, string $reason)
    {
        $this->status = self::STATUS_REJECTED_KADES;
        $this->rejected_by = $kades->id;
        $this->rejection_reason = $reason;
        $this->save();

        // TODO: Send notification to creator
    }

    public function process(User $processor, string $letterNumber)
    {
        if (!$this->letter_date) {
            $this->letter_date = now();
        }
        
        // Update status dan informasi processing
        $this->status = self::STATUS_PROCESSED;
        $this->letter_number = $letterNumber;
        $this->processed_at = now();
        $this->processed_by = $processor->id;
        
        // Simpan dulu untuk mendapatkan ID
        $this->save();
        
        // Generate QR Code setelah model disimpan
        $url = route('outgoing-letters.verify', ['outgoingLetter' => $this]);
        $qrcode = QrCode::format('png')
            ->size(200)
            ->margin(1)
            ->generate($url);
            
        $this->qr_code = $qrcode;
        
        // Simpan QR code
        $this->save();

        // TODO: Send notification to creator
    }

    // Helper Methods
    public function canBeSubmitted(): bool
    {
        return $this->status === self::STATUS_DRAFT || 
               $this->status === self::STATUS_REJECTED_SEKDES || 
               $this->status === self::STATUS_REJECTED_KADES;
    }

    public function canBeApprovedBySekdes(): bool
    {
        return $this->status === self::STATUS_PENDING_SEKDES;
    }

    public function canBeApprovedByKades(): bool
    {
        return $this->status === self::STATUS_PENDING_KADES;
    }

    public function canBeProcessed(): bool
    {
        return $this->status === self::STATUS_PENDING_PROCESS;
    }

    public function isRejected(): bool
    {
        return in_array($this->status, [
            self::STATUS_REJECTED_SEKDES,
            self::STATUS_REJECTED_KADES,
        ]);
    }

    public function isProcessed(): bool
    {
        return $this->status === self::STATUS_PROCESSED;
    }

    public function isApproved(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING_PROCESS,
            self::STATUS_PROCESSED,
        ]);
    }

    public function getVerificationUrl()
    {
        return route('outgoing-letters.verify', ['outgoingLetter' => $this]);
    }
}
