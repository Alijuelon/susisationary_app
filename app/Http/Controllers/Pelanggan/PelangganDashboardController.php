<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Mengambil pesanan yang masih berjalan (belum selesai/batal)
        $pesananAktif = \App\Models\Transaksi::with('detail.layanan')
            ->where('tipe_transaksi', 'Online')
            ->where('id_pelanggan', $userId)
            ->whereIn('status', ['Menunggu', 'Diproses', 'Siap Diambil'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Mengambil antrian aktif berdasarkan pesanan online paling awal yang belum selesai
        $antrianAktif = \App\Models\Transaksi::where('tipe_transaksi', 'Online')
            ->where('id_pelanggan', $userId)
            ->whereIn('status', ['Menunggu', 'Diproses'])
            ->orderBy('created_at', 'asc')
            ->first();

        if ($antrianAktif) {
            // Hitung nomor antrian (urutan dari seluruh pesanan online yang Menunggu/Diproses)
            $antrianAktif->queue_number = \App\Models\Transaksi::where('tipe_transaksi', 'Online')
                ->whereIn('status', ['Menunggu', 'Diproses'])
                ->where('created_at', '<=', $antrianAktif->created_at)
                ->count();
            
            // Estimasi waktu tunggu (misal: 5 menit per antrian di depan)
            $antrianAktif->estimated_wait_time = max(0, ($antrianAktif->queue_number - 1) * 5);
        }

        // Menghitung statistik
        $totalPesanan = \App\Models\Transaksi::where('id_pelanggan', $userId)->count();
        $pesananSelesai = \App\Models\Transaksi::where('id_pelanggan', $userId)->whereIn('status', ['Selesai', 'Berhasil'])->count();

        return view('pelanggan.dashboard', compact('pesananAktif', 'totalPesanan', 'pesananSelesai', 'antrianAktif'));
    }
}