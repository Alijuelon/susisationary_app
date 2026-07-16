<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BarangSeeder::class,
            LayananSeeder::class,
            TransaksiSeeder::class,
        ]);

        \App\Models\Pengaturan::firstOrCreate([
            'nama_toko' => 'Susi Stationary',
        ], [
            'alamat' => 'Alamat Susi Stationary',
            'no_telp' => '081234567890',
            'pesan_penutup' => 'Terima Kasih',
            'membership_aktif' => true,
            'antrian_aktif' => true,
            'waktu_proses_antrian' => 5,
        ]);
    }
}