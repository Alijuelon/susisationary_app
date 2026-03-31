<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Pengeluaran;
use App\Models\User;
use App\Models\Barang;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $hariIni = Carbon::today();

        // 1. STATISTIK KARTU UTAMA
        $pemasukanHariIni = Transaksi::whereDate('created_at', $hariIni)
                            ->where('status', 'Berhasil')
                            ->sum('total_harga');

        $pengeluaranHariIni = Pengeluaran::whereDate('tanggal_pengeluaran', $hariIni)
                              ->sum('nominal');

        $totalPelanggan = User::where('role', 'pelanggan')->count();

        $stokMenipis = Barang::whereColumn('stok', '<=', 'stok_minimum')->count();

        // 2. DATA GRAFIK TRANSAKSI MINGGUAN (7 Hari Terakhir)
        $transaksiMingguan = [];
        $maxTransaksi = 0; // Untuk menentukan tinggi bar chart secara proporsional

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::today()->subDays($i);
            $jumlahTransaksi = Transaksi::whereDate('created_at', $tanggal)->count();
            
            $transaksiMingguan[] = [
                'hari' => $tanggal->locale('id')->translatedFormat('D'), // Nama hari (Sen, Sel, dll)
                'jumlah' => $jumlahTransaksi
            ];

            // Cari nilai tertinggi untuk skala grafik
            if ($jumlahTransaksi > $maxTransaksi) {
                $maxTransaksi = $jumlahTransaksi;
            }
        }
        
        // Mencegah pembagian dengan nol pada saat merender grafik di blade
        $maxTransaksi = $maxTransaksi == 0 ? 1 : $maxTransaksi;

        // 3. RIWAYAT TRANSAKSI TERBARU (5 Transaksi Terakhir)
        $transaksiTerbaru = Transaksi::with('kasir') // Load relasi dengan kasir
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        // Hitung total transaksi berhasil hari ini
        $totalTransaksiHariIni = Transaksi::whereDate('created_at', $hariIni)
                                 ->where('status', 'Berhasil')
                                 ->count();

        // Kirim semua data ke view
        return view('admin.dashboard', compact(
            'pemasukanHariIni', 
            'pengeluaranHariIni', 
            'totalPelanggan', 
            'stokMenipis',
            'transaksiMingguan',
            'maxTransaksi',
            'transaksiTerbaru',
            'totalTransaksiHariIni'
        ));
    }
}