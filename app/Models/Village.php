<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'logo',
        'village_head',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function incomingLettersAsSender()
    {
        return $this->hasMany(IncomingLetter::class, 'sender_village_id');
    }

    public function incomingLettersAsReceiver()
    {
        return $this->hasMany(IncomingLetter::class, 'receiver_village_id');
    }

    public function outgoingLettersAsSender()
    {
        return $this->hasMany(OutgoingLetter::class, 'sender_village_id');
    }

    public function outgoingLettersAsReceiver()
    {
        return $this->hasMany(OutgoingLetter::class, 'receiver_village_id');
    }
}
