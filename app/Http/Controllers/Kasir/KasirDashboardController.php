<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class KasirDashboardController extends Controller
{
    public function index()
    {
        $hariIni = Carbon::today();
        $kasirId = Auth::id(); // Mengambil ID Kasir yang sedang aktif

        // 1. Total Pendapatan yang dicetak oleh kasir ini hari ini
        $pendapatanHariIni = Transaksi::where('id_kasir', $kasirId)
            ->whereDate('created_at', $hariIni)
            ->where('status', 'Berhasil')
            ->sum('total_harga');

        // 2. Jumlah struk/transaksi berhasil hari ini
        $totalTransaksi = Transaksi::where('id_kasir', $kasirId)
            ->whereDate('created_at', $hariIni)
            ->where('status', 'Berhasil')
            ->count();

        // 3. Riwayat 5 transaksi terakhir dari kasir ini
        $transaksiTerbaru = Transaksi::where('id_kasir', $kasirId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 4. Daftar Antrian Aktif Hari Ini
        $daftarAntrian = \App\Models\Queue::whereDate('created_at', $hariIni)
            ->whereIn('status', [\App\Enums\QueueStatus::MENUNGGU, \App\Enums\QueueStatus::DIPROSES])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('kasir.dashboard', compact(
            'pendapatanHariIni', 
            'totalTransaksi', 
            'transaksiTerbaru',
            'daftarAntrian'
        ));
    }
}