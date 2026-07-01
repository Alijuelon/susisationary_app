<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pesanan;
use App\Models\User;
use App\Models\Layanan;
use Carbon\Carbon;

class PesananSeeder extends Seeder
{
    public function run(): void
    {
        $pelanggans = User::where('role', 'pelanggan')->pluck('id')->toArray();
        $layanans = Layanan::pluck('id')->toArray();
        
        if (empty($pelanggans) || empty($layanans)) {
            return;
        }

        $now = Carbon::now();

        $pesanans = [
            // Status Menunggu (Paling Lama -> Prioritas)
            ['pelanggan' => 0, 'layanan' => 0, 'status' => 'Menunggu', 'time' => $now->copy()->subMinutes(60), 'catatan' => 'Rangkap: 2 | Warna: B&W | Sisi: Simplex'],
            ['pelanggan' => 1, 'layanan' => 1, 'status' => 'Menunggu', 'time' => $now->copy()->subMinutes(45), 'catatan' => 'Tolong dijilid sekalian pakai mika bening ya.'],
            ['pelanggan' => 2, 'layanan' => 2, 'status' => 'Menunggu', 'time' => $now->copy()->subMinutes(30), 'catatan' => 'Kertasnya A4 biasa saja, butuh cepat.'],
            ['pelanggan' => 3, 'layanan' => 3, 'status' => 'Menunggu', 'time' => $now->copy()->subMinutes(15), 'catatan' => 'Rangkap: 5 | Warna: Berwarna | Sisi: Duplex'],
            ['pelanggan' => 4, 'layanan' => 4, 'status' => 'Menunggu', 'time' => $now->copy()->subMinutes(5), 'catatan' => 'Cover warna biru tua, tebal.'],
            
            // Status Diproses
            ['pelanggan' => 1, 'layanan' => 5, 'status' => 'Diproses', 'time' => $now->copy()->subHours(2), 'catatan' => 'Jilid hardcover warna merah marun dengan tulisan emas.'],
            ['pelanggan' => 0, 'layanan' => 6, 'status' => 'Diproses', 'time' => $now->copy()->subHours(1)->subMinutes(30), 'catatan' => 'Print foto di kertas glossy ya.'],

            // Status Siap Diambil
            ['pelanggan' => 2, 'layanan' => 0, 'status' => 'Siap Diambil', 'time' => $now->copy()->subHours(4), 'catatan' => 'Nanti sore saya ambil.'],
            ['pelanggan' => 3, 'layanan' => 7, 'status' => 'Siap Diambil', 'time' => $now->copy()->subHours(5), 'catatan' => 'Foto paspor ukuran 4x6, 10 lembar.'],

            // Status Selesai
            ['pelanggan' => 4, 'layanan' => 1, 'status' => 'Selesai', 'time' => $now->copy()->subDays(1), 'catatan' => 'Sudah diambil dan dibayar.'],
            ['pelanggan' => 0, 'layanan' => 8, 'status' => 'Selesai', 'time' => $now->copy()->subDays(2), 'catatan' => 'Poster event lomba anak.'],
            
            // Status Dibatalkan
            ['pelanggan' => 1, 'layanan' => 2, 'status' => 'Dibatalkan', 'time' => $now->copy()->subDays(3), 'catatan' => 'Maaf salah file, saya cancel dulu.'],
        ];

        foreach ($pesanans as $p) {
            $id_pelanggan = $pelanggans[$p['pelanggan'] % count($pelanggans)];
            $id_layanan = $layanans[$p['layanan'] % count($layanans)];

            // Create a dummy document file entry
            $file_dokumen = 'dokumen_pesanan/dummy_' . time() . rand(100, 999) . '.pdf';

            $pesanan = Pesanan::create([
                'id_pelanggan' => $id_pelanggan,
                'id_layanan'   => $id_layanan,
                'file_dokumen' => $file_dokumen,
                'catatan'      => $p['catatan'],
                'status'       => $p['status'],
                'created_at'   => $p['time'],
                'updated_at'   => $p['time'],
            ]);
            
            // Untuk memastikan urutan queue berfungsi, karena created_at bisa ditimpa Eloquent saat create
            $pesanan->created_at = $p['time'];
            $pesanan->updated_at = $p['time'];
            $pesanan->save(['timestamps' => false]);
        }
    }
}
