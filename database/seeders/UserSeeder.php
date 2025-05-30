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
        User::firstOrCreate([
            'name' => 'د/مجدي ذكي ',
            'email' => 'magdy_zaki@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'doctor',
            'national_id' => '123456789',
            'phone' => '01296024733',
        ]);

        User::firstOrCreate([
            'name' => 'د/راشد زكريا ',
            'email' => 'rashed_zakria@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'doctor',
            'national_id' => '987654321',
            'phone' => '01587432147',
        ]);
        User::firstOrCreate([
            'name' => 'د/أحمد صالح ',
            'email' => 'ahmed_saleh@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'doctor',
            'national_id' => '36954321',
            'phone' => '01198732177',
        ]);
        User::firstOrCreate([
            'name' => 'د/يوسف طه ',
            'email' => 'yousef_taha@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'doctor',
            'national_id' => '145354321',
            'phone' => '01298732125',
        ]);

        // Create 2 admins
        User::firstOrCreate([
            'name' => 'eyad elwakeel',
            'email' => 'eyad_elwakeel@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'national_id' => '111222333',
            'phone' => '01011588455',
        ]);

        User::firstOrCreate([
            'name' => 'dina essam',
            'email' => 'dina_essam@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'national_id' => '444555666',
            'phone' => '0129854387',
        ]);

        //create 8 patiant
        User::firstOrCreate([
            'name' => 'Ahmed Hassan',
            'email' => 'ahmed.hassan@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'patient',
        ]);

        User::firstOrCreate([
            'name' => 'Mona Adel',
            'email' => 'mona.adel@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'patient',
        ]);

        User::firstOrCreate([
            'name' => 'Youssef Tarek',
            'email' => 'youssef.tarek@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'patient',
        ]);

        User::firstOrCreate([
            'name' => 'Nourhan Ahmed',
            'email' => 'nourhan.ahmed@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'patient',
        ]);

        User::firstOrCreate([
            'name' => 'Salma Mahmoud',
            'email' => 'salma.mahmoud@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'patient',
        ]);

        User::firstOrCreate([
            'name' => 'Karim Nabil',
            'email' => 'karim.nabil@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'patient',
        ]);

        User::firstOrCreate([
            'name' => 'Aya Mostafa',
            'email' => 'aya.mostafa@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'patient',
        ]);

        User::firstOrCreate([
            'name' => 'Omar Samir',
            'email' => 'omar.samir@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'patient',
        ]);
    }
}
