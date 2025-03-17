<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterClassification extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    public function incomingLetters()
    {
        return $this->hasMany(IncomingLetter::class, 'classification_id');
    }

    public function outgoingLetters()
    {
        return $this->hasMany(OutgoingLetter::class, 'classification_id');
    }
}
