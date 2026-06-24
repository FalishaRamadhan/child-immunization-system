<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'email' => 'admin1@totobora.com',
                'name' => 'System Admin',
                'first_name' => 'System',
                'last_name' => 'Admin',
                'role' => 'admin',
                'facility_id' => null,
            ],
            [
                'email' => 'sarah@totobora.com',
                'name' => 'Dr Sarah Wanjiku',
                'first_name' => 'Sarah',
                'last_name' => 'Wanjiku',
                'role' => 'healthcare_worker',
                'facility_id' => 2,
            ],
            [
                'email' => 'john@totobora.com',
                'name' => 'Nurse John Mwangi',
                'first_name' => 'John',
                'last_name' => 'Mwangi',
                'role' => 'healthcare_worker',
                'facility_id' => 1,
            ],
            [
                'email' => 'amina@totobora.com',
                'name' => 'Nurse Amina Ali',
                'first_name' => 'Amina',
                'last_name' => 'Ali',
                'role' => 'healthcare_worker',
                'facility_id' => 3,
            ],
            [
                'email' => 'mary@totobora.com',
                'name' => ' Mary Njeri',
                'first_name' => 'Mary',
                'last_name' => 'Njeri',
                'role' => 'caregiver',
                'facility_id' => 1,
            ],
            [
                'email' => 'peter@totobora.com',
                'name' => ' Peter Otieno',
                'first_name' => 'Peter',
                'last_name' => 'Otieno',
                'role' => 'caregiver',
                'facility_id' => 2,
            ],
            [
                'email' => 'grace@totobora.com',
                'name' => ' Grace Wairimu',
                'first_name' => 'Grace',
                'last_name' => 'Wairimu',
                'role' => 'caregiver',
                'facility_id' => 3,
            ],
            [
                'email' => 'david@totobora.com',
                'name' => ' David Kimani',
                'first_name' => 'David',
                'last_name' => 'Kimani',
                'role' => 'healthcare_worker',
                'facility_id' => 1,
            ],
            [
                'email' => 'ahmed@totobora.com',
                'name' => 'Ahmed Hassan',
                'first_name' => 'Ahmed',
                'last_name' => 'Hassan',
                'role' => 'caregiver',
                'facility_id' => 2,
            ],
            [
                'email' => 'tester@totobora.com',
                'name' => 'System Admin',
                'first_name' => 'System',
                'last_name' => 'Admin',
                'role' => 'admin',
                'facility_id' => null,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'role' => $user['role'],
                    'facility_id' => $user['facility_id'],
                    'password' => Hash::make('password123'),
                ]
            );
        }
    }
}