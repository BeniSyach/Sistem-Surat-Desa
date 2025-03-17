<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutgoingLetterDisposition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'outgoing_letter_id',
        'from_user_id',
        'to_user_id',
        'notes',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function outgoingLetter()
    {
        return $this->belongsTo(OutgoingLetter::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }
} 