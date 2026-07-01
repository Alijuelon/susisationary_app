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
        User::updateOrCreate(
            ['email' => 'admin@susistationary.com'],
            [
                'nama_lengkap' => 'Bpk. Admin Utama',
                'username'     => 'admin',
                'password'     => Hash::make('password123'),
                'role'         => 'admin',
            ]
        );

        // 2. Akun Kasir
        User::updateOrCreate(
            ['email' => 'kasir@susistationary.com'],
            [
                'nama_lengkap' => 'Siti (Kasir 1)',
                'username'     => 'kasir',
                'password'     => Hash::make('password123'),
                'role'         => 'kasir',
            ]
        );

        // 3. Akun Pelanggan (Asli)
        User::updateOrCreate(
            ['email' => 'andi@gmail.com'],
            [
                'nama_lengkap' => 'Andi Saputra',
                'username'     => 'pelanggan',
                'password'     => Hash::make('password123'),
                'role'         => 'pelanggan',
            ]
        );

        // 4. Tambahan Pelanggan Dummy untuk Antrean
        $dummyPelanggan = [
            ['Budi Santoso', 'budi_s', 'budi@gmail.com'],
            ['Citra Lestari', 'citra_l', 'citra@gmail.com'],
            ['Dewi Sartika', 'dewi_s', 'dewi@gmail.com'],
            ['Eko Prasetyo', 'eko_p', 'eko@gmail.com'],
            ['Fajar Hidayat', 'fajar_h', 'fajar@gmail.com']
        ];

        foreach ($dummyPelanggan as $plg) {
            User::updateOrCreate(
                ['email' => $plg[2]],
                [
                    'nama_lengkap' => $plg[0],
                    'username'     => $plg[1],
                    'password'     => Hash::make('password123'),
                    'role'         => 'pelanggan',
                ]
            );
        }
    }
}