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
        // 1. Akun Admin
        User::create([
            'nama_lengkap' => 'Bpk. Admin Utama',
            'username'     => 'admin',
            'email'        => 'admin@susistationary.com',
            'password'     => Hash::make('password123'),
            'role'         => 'admin',
        ]);

        // 2. Akun Kasir
        User::create([
            'nama_lengkap' => 'Siti (Kasir 1)',
            'username'     => 'kasir',
            'email'        => 'kasir@susistationary.com',
            'password'     => Hash::make('password123'),
            'role'         => 'kasir',
        ]);

        // 3. Akun Pelanggan
        User::create([
            'nama_lengkap' => 'Andi Saputra',
            'username'     => 'pelanggan',
            'email'        => 'andi@gmail.com',
            'password'     => Hash::make('password123'),
            'role'         => 'pelanggan',
        ]);
    }
}