<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    protected $primaryKey = 'guardian_id';

    protected $fillable = [
        'child_id',
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'relationship',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class, 'child_id', 'child_id');
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class, 'guardian_id', 'guardian_id');
    }

    // Get all children belonging to this guardian via phone number
    public function allChildren()
    {
        return Child::whereHas('guardians', function ($q) {
            $q->where('phone_number', $this->phone_number);
        })->get();
    }
}