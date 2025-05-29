<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create 4 doctors
        User::create([
            'name' => 'د/مجدي ذكي ',
            'email' => 'magdy_zaki@doctor.com',
            'password' => Hash::make('12345678'),
            'role' => 'doctor',
            'national_id' => '123456789',
            'phone' => '0123456789',
        ]);

        User::create([
            'name' => 'د/راشد زكريا ',
            'email' => 'rashed_zakria@doctor.com',
            'password' => Hash::make('12345678'),
            'role' => 'doctor',
            'national_id' => '987654321',
            'phone' => '0987654321',
        ]);
        User::create([
            'name' => 'د/أحمد صالح ',
            'email' => 'ahmed_saleh@doctor.com',
            'password' => Hash::make('12345678'),
            'role' => 'doctor',
            'national_id' => '36954321',
            'phone' => '0987654321',
        ]);
        User::create([
            'name' => 'د/يوسف طه ',
            'email' => 'yousef_taha@doctor.com',
            'password' => Hash::make('12345678'),
            'role' => 'doctor',
            'national_id' => '145354321',
            'phone' => '0987654321',
        ]);

        // Create 2 admins
        User::create([
            'name' => 'eyad elwakeel',
            'email' => 'eyad_elwakeel@admin.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'national_id' => '111222333',
            'phone' => '01234526789',
        ]);

        User::create([
            'name' => 'dina essam',
            'email' => 'dina_essam@admin.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'national_id' => '444555666',
            'phone' => '0987654387',
        ]);

        //create 8 patiant
        User::create([
            'name' => 'Ahmed Hassan',
            'email' => 'ahmed.hassan@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'patient',
        ]);

        User::create([
            'name' => 'Mona Adel',
            'email' => 'mona.adel@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'patient',
        ]);

        User::create([
            'name' => 'Youssef Tarek',
            'email' => 'youssef.tarek@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'patient',
        ]);

        User::create([
            'name' => 'Nourhan Ahmed',
            'email' => 'nourhan.ahmed@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'patient',
        ]);

        User::create([
            'name' => 'Salma Mahmoud',
            'email' => 'salma.mahmoud@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'patient',
        ]);

        User::create([
            'name' => 'Karim Nabil',
            'email' => 'karim.nabil@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'patient',
        ]);

        User::create([
            'name' => 'Aya Mostafa',
            'email' => 'aya.mostafa@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'patient',
        ]);

        User::create([
            'name' => 'Omar Samir',
            'email' => 'omar.samir@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'patient',
        ]);
    }
}
