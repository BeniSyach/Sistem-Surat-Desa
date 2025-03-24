<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'signature',
        'is_active',
        'role_id',
        'village_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the roles for the user.
     * This is a compatibility method to work with hasRole() method.
     */
    public function roles()
    {
        if ($this->role) {
            return collect([$this->role]);
        }
        return collect([]);
    }

    /**
     * Check if user has the given role.
     */
    public function hasRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }

    /**
     * Get the village that owns the user.
     */
    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    /**
     * Check if user has a signature.
     */
    public function hasSignature()
    {
        return !empty($this->signature);
    }

    /**
     * Delete user's signature file and update the signature field to null.
     */
    public function deleteSignature()
    {
        if ($this->signature && Storage::exists($this->signature)) {
            Storage::delete($this->signature);
        }
        
        $this->signature = null;
        $this->save();
    }

    /**
     * Update user's signature with a new file path.
     */
    public function updateSignature($path)
    {
        $this->deleteSignature();
        $this->signature = $path;
        $this->save();
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function outgoingLetters()
    {
        return $this->hasMany(OutgoingLetter::class, 'created_by');
    }

    public function approvedOutgoingLettersAsSekdes()
    {
        return $this->hasMany(OutgoingLetter::class, 'sekdes_id')
            ->whereNotNull('sekdes_approved_at');
    }

    public function approvedOutgoingLettersAsKades()
    {
        return $this->hasMany(OutgoingLetter::class, 'kades_id')
            ->whereNotNull('kades_approved_at');
    }

    public function processedOutgoingLetters()
    {
        return $this->hasMany(OutgoingLetter::class, 'processed_by');
    }

    public function isKasi(): bool
    {
        return $this->role->name === 'Pembuat Surat';
    }

    public function isSekdes(): bool
    {
        return $this->role->name === 'Memparaf Surat';
    }

    public function isKades(): bool
    {
        return $this->role->name === 'Menandatangani Surat';
    }

    public function isUmumDesa()
    {
        return $this->role->name === 'Bagian Umum';
    }

    public function isAdmin(): bool
    {
        return $this->role->name === 'Admin';
    }
}
