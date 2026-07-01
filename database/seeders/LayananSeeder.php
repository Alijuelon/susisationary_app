<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Layanan;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        $layanans = [
            ['Print Dokumen (Hitam Putih)', 'Lembar', 500],
            ['Print Dokumen (Warna Full)', 'Lembar', 2500],
            ['Print Dokumen (Warna Standar)', 'Lembar', 1500],
            ['Fotocopy (Hitam Putih)', 'Lembar', 300],
            ['Jilid Mika/Spiral', 'Buku', 15000],
            ['Jilid Hardcover', 'Buku', 45000],
            ['Print Foto 3x4', 'Lembar', 1500],
            ['Print Foto 4x6', 'Lembar', 2000],
            ['Print Poster A3+', 'Lembar', 8000],
            ['Laminating A4/F4', 'Lembar', 4000],
        ];

        foreach ($layanans as $item) {
            Layanan::updateOrCreate(
                ['nama_layanan' => $item[0]],
                [
                    'satuan'       => $item[1],
                    'harga_satuan' => $item[2],
                ]
            );
        }
    }
}
