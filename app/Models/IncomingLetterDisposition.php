<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingLetterDisposition extends Model
{
    use HasFactory;

    protected $fillable = [
        'incoming_letter_id',
        'from_user_id',
        'to_user_id',
        'notes',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Get the incoming letter that owns the disposition.
     */
    public function incomingLetter()
    {
        return $this->belongsTo(IncomingLetter::class);
    }

    /**
     * Get the user who created the disposition.
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Get the user who received the disposition.
     */
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
} 