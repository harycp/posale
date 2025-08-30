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
        // Membuat User Admin
        User::create([
            'name' => 'Admin Toko',
            'email' => 'admin@tokobangunan.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Membuat User Kasir
        User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@tokobangunan.com',
            'password' => Hash::make('password'),
            'role' => 'cashier',
        ]);
    }
}
