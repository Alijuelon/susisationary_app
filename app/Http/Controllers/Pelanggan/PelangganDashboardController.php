<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Mengambil pesanan yang masih berjalan (belum selesai/batal)
        $pesananAktif = Pesanan::with('layanan')
            ->where('id_pelanggan', $userId)
            ->whereIn('status', ['Menunggu', 'Diproses', 'Siap Diambil'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Menghitung statistik
        $totalPesanan = Pesanan::where('id_pelanggan', $userId)->count();
        $pesananSelesai = Pesanan::where('id_pelanggan', $userId)->where('status', 'Selesai')->count();

        return view('pelanggan.dashboard', compact('pesananAktif', 'totalPesanan', 'pesananSelesai'));
    }
}