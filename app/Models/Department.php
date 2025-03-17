<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'village_id',
    ];

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function incomingLetters()
    {
        return $this->hasMany(IncomingLetter::class);
    }

    public function outgoingLetters()
    {
        return $this->hasMany(OutgoingLetter::class);
    }
}
