<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create 2 doctors
        User::create([
            'name' => 'Dr. John Doe',
            'email' => 'john.doe@doctor.com',
            'password' => Hash::make('password123'), // Use a strong password or a placeholder for testing
            'role' => 'doctor',
            'national_id' => '123456789',
            'phone' => '0123456789',
        ]);

        User::create([
            'name' => 'Dr. Jane Smith',
            'email' => 'jane.smith@doctor.com',
            'password' => Hash::make('password123'),
            'role' => 'doctor',
            'national_id' => '987654321',
            'phone' => '0987654321',
        ]);

        // Create 2 admins
        User::create([
            'name' => 'Admin User 1',
            'email' => 'admin1@admin.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'national_id' => '111222333',
            'phone' => '01234526789',
        ]);

        User::create([
            'name' => 'Admin User 2',
            'email' => 'admin2@admin.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'national_id' => '444555666',
            'phone' => '0987654387',
        ]);
    }
}
