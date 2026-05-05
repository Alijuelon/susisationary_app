<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Pengeluaran;
use App\Models\User;
use App\Models\Barang;
use App\Models\Pesanan;
use App\Models\Membership;
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

        // Profit hari ini
        $profitHariIni = $pemasukanHariIni - $pengeluaranHariIni;

        // Member aktif
        $totalMemberAktif = Membership::where('status', 'aktif')->count();

        // 2. DATA GRAFIK TRANSAKSI MINGGUAN (7 Hari Terakhir) — Jumlah transaksi
        $transaksiMingguan = [];
        $maxTransaksi = 0;

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::today()->subDays($i);
            $jumlahTransaksi = Transaksi::whereDate('created_at', $tanggal)->count();
            
            $transaksiMingguan[] = [
                'hari' => $tanggal->locale('id')->translatedFormat('D'),
                'jumlah' => $jumlahTransaksi
            ];

            if ($jumlahTransaksi > $maxTransaksi) {
                $maxTransaksi = $jumlahTransaksi;
            }
        }
        
        $maxTransaksi = $maxTransaksi == 0 ? 1 : $maxTransaksi;

        // 3. DATA GRAFIK REVENUE MINGGUAN (7 Hari Terakhir) — Pendapatan per hari
        $revenueMingguan = [];
        $maxRevenue = 0;

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::today()->subDays($i);
            $revenue = Transaksi::whereDate('created_at', $tanggal)
                        ->where('status', 'Berhasil')
                        ->sum('total_harga');
            
            $revenueMingguan[] = [
                'hari' => $tanggal->locale('id')->translatedFormat('D'),
                'jumlah' => (float) $revenue,
            ];

            if ($revenue > $maxRevenue) {
                $maxRevenue = (float) $revenue;
            }
        }

        $maxRevenue = $maxRevenue == 0 ? 1 : $maxRevenue;

        // 4. DISTRIBUSI STATUS PESANAN ONLINE
        $pesananPerStatus = [
            'Menunggu' => Pesanan::where('status', 'Menunggu')->count(),
            'Diproses' => Pesanan::where('status', 'Diproses')->count(),
            'Siap Diambil' => Pesanan::where('status', 'Siap Diambil')->count(),
            'Selesai' => Pesanan::where('status', 'Selesai')->count(),
            'Dibatalkan' => Pesanan::where('status', 'Dibatalkan')->count(),
        ];
        $totalPesanan = array_sum($pesananPerStatus);

        // 5. RIWAYAT TRANSAKSI TERBARU (5 Transaksi Terakhir)
        $transaksiTerbaru = Transaksi::with('kasir')
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
            'profitHariIni',
            'totalMemberAktif',
            'transaksiMingguan',
            'maxTransaksi',
            'revenueMingguan',
            'maxRevenue',
            'pesananPerStatus',
            'totalPesanan',
            'transaksiTerbaru',
            'totalTransaksiHariIni'
        ));
    }
}