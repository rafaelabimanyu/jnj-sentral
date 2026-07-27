<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Akun Owner
        User::create([
            'name' => 'Wibowo Pratikno',
            'email' => 'owner@rooterin.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        // Akun Admin Operasional
        User::create([
            'name' => 'Admin Operasional',
            'email' => 'admin@rooterin.com',
            'password' => Hash::make('password'),
            'role' => 'admin_ops',
        ]);
    }
}
