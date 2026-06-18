<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 
        'first_name', 
        'last_name', 
        'email', 
        'password', 
        'role', 
        'facility_id',
        'is_active'
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function facility(){
        return $this->belongsTo(Facility::class, 'facility_id', 'facility_id');
    }
    public function isAdmin(): bool {
        return $this->role === 'admin';
    }
    public function isHealthcareWorker(): bool {
        return $this->role === 'healthcare_worker';
    }
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function scopeHealthcareWorkers($query)
    {
        return $query->where('role', 'healthcare_worker');
    }
}
