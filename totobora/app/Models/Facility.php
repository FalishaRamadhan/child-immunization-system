<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $primaryKey = 'facility_id';

    protected $fillable = ['name', 'location', 'contact'];

    public function users()
    {
        return $this->hasMany(User::class, 'facility_id', 'facility_id');
    }

    public function children()
    {
        return $this->hasMany(Child::class, 'facility_id', 'facility_id');
    }
}