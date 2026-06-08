<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@mango.com'], // Agar email pehle se hai to update karega, nahi to naya banayega
            [
                'name' => 'Royal Admin',
                'password' => Hash::make('admin123'), // Aapka password
                'role' => 'admin', // Role column mein admin set ho gaya
                'phone' => '03001234567',
                'address' => 'Multan Cantt'
            ]
        );
    }
}