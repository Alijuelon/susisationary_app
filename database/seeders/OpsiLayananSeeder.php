<?php

namespace Database\Seeders;

use App\Models\Layanan;
use App\Models\OpsiLayanan;
use Illuminate\Database\Seeder;

class OpsiLayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some services to attach options to
        $layananCetak = Layanan::where('nama_layanan', 'like', '%Cetak%')->first();
        if (!$layananCetak) {
            $layananCetak = Layanan::first();
        }

        if ($layananCetak) {
            $opsi = [
                // Warna Cetak
                ['kategori' => 'Warna Cetak', 'nama_opsi' => 'Hitam Putih', 'harga' => 0],
                ['kategori' => 'Warna Cetak', 'nama_opsi' => 'Warna (Full Color)', 'harga' => 500],
                
                // Ukuran Kertas
                ['kategori' => 'Ukuran Kertas', 'nama_opsi' => 'A4', 'harga' => 0],
                ['kategori' => 'Ukuran Kertas', 'nama_opsi' => 'F4', 'harga' => 0],
                ['kategori' => 'Ukuran Kertas', 'nama_opsi' => 'A3', 'harga' => 1000],
                
                // Jenis Kertas
                ['kategori' => 'Jenis Kertas', 'nama_opsi' => 'HVS 70gr', 'harga' => 0],
                ['kategori' => 'Jenis Kertas', 'nama_opsi' => 'HVS 80gr', 'harga' => 100],
                ['kategori' => 'Jenis Kertas', 'nama_opsi' => 'Art Paper 120gr', 'harga' => 2000],
                
                // Sisi Cetak
                ['kategori' => 'Sisi Cetak', 'nama_opsi' => 'Satu Sisi', 'harga' => 0],
                ['kategori' => 'Sisi Cetak', 'nama_opsi' => 'Bolak Balik (Duplex)', 'harga' => 0],
                
                // Finishing
                ['kategori' => 'Finishing', 'nama_opsi' => 'Tanpa Finishing', 'harga' => 0],
                ['kategori' => 'Finishing', 'nama_opsi' => 'Jilid Mika/Klip', 'harga' => 5000],
                ['kategori' => 'Finishing', 'nama_opsi' => 'Jilid Lakban', 'harga' => 3000],
                ['kategori' => 'Finishing', 'nama_opsi' => 'Jilid Spiral', 'harga' => 15000],
            ];

            foreach ($opsi as $o) {
                OpsiLayanan::updateOrCreate([
                    'id_layanan' => $layananCetak->id,
                    'kategori' => $o['kategori'],
                    'nama_opsi' => $o['nama_opsi'],
                ], [
                    'harga' => $o['harga'],
                ]);
            }
        }
    }
}
