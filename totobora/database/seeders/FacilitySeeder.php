<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        Facility::insert([
            [
                'name' => 'Kibera Clinic',
                'location' => 'Nairobi',
                'contact' => '0700000001'
            ],
            [
                'name' => 'Kisumu Health Center',
                'location' => 'Kisumu',
                'contact' => '0700000002'
            ],
            [
                'name' => 'Mtwapa Clinic',
                'location' => 'Mombasa',
                'contact' => '0700000003'
            ],
            
        ]);
    }
}