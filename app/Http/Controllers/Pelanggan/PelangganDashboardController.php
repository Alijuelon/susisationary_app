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

        // Mengambil antrian aktif (jika ada)
        $antrianAktif = \App\Models\Queue::where('customer_id', $userId)
            ->whereIn('status', [\App\Enums\QueueStatus::MENUNGGU, \App\Enums\QueueStatus::DIPROSES])
            ->first();

        // Menghitung statistik
        $totalPesanan = \App\Models\Transaksi::where('id_pelanggan', $userId)->count();
        $pesananSelesai = \App\Models\Transaksi::where('id_pelanggan', $userId)->whereIn('status', ['Selesai', 'Berhasil'])->count();

        return view('pelanggan.dashboard', compact('pesananAktif', 'totalPesanan', 'pesananSelesai', 'antrianAktif'));
    }
}