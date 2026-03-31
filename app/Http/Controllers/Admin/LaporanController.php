<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter tanggal dari request, jika tidak ada, default ke bulan ini
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // 1. Ambil Data Pemasukan (Transaksi Berhasil)
        $pemasukan = Transaksi::where('status', 'Berhasil')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'tanggal'    => $item->created_at,
                    'keterangan' => 'Penjualan Kasir (' . $item->id . ')',
                    'jenis'      => 'Pemasukan',
                    'nominal'    => $item->total_harga,
                ];
            });

        // 2. Ambil Data Pengeluaran
        $pengeluaran = Pengeluaran::whereDate('tanggal_pengeluaran', '>=', $startDate)
            ->whereDate('tanggal_pengeluaran', '<=', $endDate)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'tanggal'    => Carbon::parse($item->tanggal_pengeluaran), // Ubah ke objek Carbon agar sama
                    'keterangan' => $item->keterangan,
                    'jenis'      => 'Pengeluaran',
                    'nominal'    => $item->nominal,
                ];
            });

        // 3. Gabungkan dan Urutkan Berdasarkan Tanggal (Terbaru ke Terlama)
        $laporan = $pemasukan->concat($pengeluaran)->sortByDesc('tanggal');

        // 4. Hitung Rekapitulasi
        $totalPemasukan = $pemasukan->sum('nominal');
        $totalPengeluaran = $pengeluaran->sum('nominal');
        $labaBersih = $totalPemasukan - $totalPengeluaran;

        return view('admin.laporan.index', compact(
            'laporan', 'totalPemasukan', 'totalPengeluaran', 'labaBersih', 'startDate', 'endDate'
        ));
    }

    // Fungsi Cetak PDF
    public function cetakPdf(Request $request)
    {
        // Logika query sama persis dengan index()
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $pemasukan = Transaksi::where('status', 'Berhasil')->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->get()->map(fn($i) => (object) ['tanggal' => $i->created_at, 'keterangan' => 'Penjualan Kasir ('.$i->id.')', 'jenis' => 'Pemasukan', 'nominal' => $i->total_harga]);
        $pengeluaran = Pengeluaran::whereDate('tanggal_pengeluaran', '>=', $startDate)->whereDate('tanggal_pengeluaran', '<=', $endDate)->get()->map(fn($i) => (object) ['tanggal' => Carbon::parse($i->tanggal_pengeluaran), 'keterangan' => $i->keterangan, 'jenis' => 'Pengeluaran', 'nominal' => $i->nominal]);
        
        $laporan = $pemasukan->concat($pengeluaran)->sortBy('tanggal'); // Cetak PDF biasanya dari tanggal terlama ke terbaru
        $totalPemasukan = $pemasukan->sum('nominal');
        $totalPengeluaran = $pengeluaran->sum('nominal');
        $labaBersih = $totalPemasukan - $totalPengeluaran;

        // Catatan: Untuk merender PDF, kita akan menggunakan package dompdf nanti.
        // Untuk sekarang, kita return ke view PDF HTML murni.
        return view('admin.laporan.pdf', compact('laporan', 'totalPemasukan', 'totalPengeluaran', 'labaBersih', 'startDate', 'endDate'));
    }
}