<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $barangs = [
            ['ATK-001', 'Kertas HVS A4 80gsm Sinar Dunia', 55000, 50, 10],
            ['ATK-002', 'Kertas HVS F4 70gsm PaperOne', 60000, 45, 10],
            ['ATK-003', 'Tinta Printer Epson T6641 Black', 85000, 20, 5],
            ['ATK-004', 'Tinta Printer Epson T6642 Cyan', 85000, 15, 5],
            ['ATK-005', 'Pulpen Faster C600 Hitam (Pack)', 30000, 100, 20],
            ['ATK-006', 'Spidol Boardmarker Snowman Hitam', 8000, 150, 30],
            ['ATK-007', 'Buku Tulis Sinar Dunia 58 Lembar (Pack)', 45000, 80, 15],
            ['ATK-008', 'Isi Staples Kenko No. 10', 3500, 200, 50],
            ['ATK-009', 'Map Sneilhecter Plastik Merah', 5000, 120, 25],
            ['ATK-010', 'Lakban Bening Daimaru 2 inch', 12000, 60, 15],
        ];

        foreach ($barangs as $item) {
            Barang::updateOrCreate(
                ['kode_barang'  => $item[0]],
                [
                    'nama_barang'  => $item[1],
                    'harga_jual'   => $item[2],
                    'stok'         => $item[3],
                    'stok_minimum' => $item[4],
                ]
            );
        }
    }
}
