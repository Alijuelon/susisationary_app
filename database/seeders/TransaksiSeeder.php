<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\User;
use App\Models\Layanan;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $pelanggans = User::where('role', 'pelanggan')->get();
        $layanans = Layanan::all();
        
        if ($pelanggans->isEmpty() || $layanans->isEmpty()) {
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

            // Status Berhasil (Checkout Kasir)
            ['pelanggan' => 4, 'layanan' => 1, 'status' => 'Berhasil', 'time' => $now->copy()->subDays(1), 'catatan' => 'Sudah diambil dan dibayar.'],
            ['pelanggan' => 0, 'layanan' => 8, 'status' => 'Berhasil', 'time' => $now->copy()->subDays(2), 'catatan' => 'Poster event lomba anak.'],
            
            // Status Dibatalkan
            ['pelanggan' => 1, 'layanan' => 2, 'status' => 'Dibatalkan', 'time' => $now->copy()->subDays(3), 'catatan' => 'Maaf salah file, saya cancel dulu.'],
        ];

        foreach ($pesanans as $p) {
            $pelanggan = $pelanggans[$p['pelanggan'] % $pelanggans->count()];
            $layanan = $layanans[$p['layanan'] % $layanans->count()];

            $kodeUnik = 'TRX-' . $p['time']->format('Ymd') . '-' . strtoupper(Str::random(5));
            $file_dokumen = 'dokumen_pesanan/dummy_' . time() . rand(100, 999) . '.pdf';
            
            $transaksi = Transaksi::create([
                'kode_transaksi'    => $kodeUnik,
                'id_pelanggan'      => $pelanggan->id,
                'tipe_transaksi'    => 'Online',
                'status'            => $p['status'],
                'nama_pelanggan'    => $pelanggan->nama_lengkap ?? $pelanggan->name,
                'total_harga'       => $layanan->harga_satuan,
                'uang_bayar'        => $p['status'] === 'Berhasil' ? $layanan->harga_satuan : 0,
                'kembalian'         => 0,
                'metode_pembayaran' => 'Cash',
                'created_at'        => $p['time'],
                'updated_at'        => $p['time'],
            ]);

            DetailTransaksi::create([
                'id_transaksi' => $transaksi->id,
                'tipe_item'    => 'Layanan',
                'id_item'      => $layanan->id,
                'nama_item'    => $layanan->nama_layanan,
                'harga_satuan' => $layanan->harga_satuan,
                'qty'          => 1,
                'subtotal'     => $layanan->harga_satuan,
                'file_dokumen' => $file_dokumen,
                'catatan'      => $p['catatan'],
                'created_at'   => $p['time'],
                'updated_at'   => $p['time'],
            ]);
        }
    }
}
