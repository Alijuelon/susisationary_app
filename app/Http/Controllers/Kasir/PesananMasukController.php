<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pesanan; // Asumsi Anda memiliki model Pesanan untuk transaksi online
use Illuminate\Http\Request;

class PesananMasukController extends Controller
{
    // 1. Menampilkan daftar pesanan online yang masuk
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filterStatus = $request->input('status');

        // Query dasar dengan relasi ke tabel user (pelanggan) dan layanan
        $query = Pesanan::with(['pelanggan', 'layanan'])
                        ->orderByRaw("FIELD(status, 'Menunggu', 'Diproses', 'Siap Diambil', 'Selesai', 'Dibatalkan')") // Urutkan berdasarkan prioritas
                        ->orderBy('created_at', 'asc'); // Yang duluan masuk, berada di atas

        // Pencarian berdasarkan Nama Pelanggan atau ID Pesanan
        if ($search) {
            $query->whereHas('pelanggan', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%");
            })->orWhere('id', 'like', "%{$search}%");
        }

        // Filter berdasarkan Status
        if ($filterStatus && $filterStatus !== 'Semua') {
            $query->where('status', $filterStatus);
        }

        // Pagination 10 data per halaman
        $pesanan = $query->paginate(10)->withQueryString();

        return view('kasir.pesanan.index', compact('pesanan', 'search', 'filterStatus'));
    }

    // 2. Memperbarui Status Pesanan
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Diproses,Siap Diambil,Selesai,Dibatalkan',
        ]);

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update([
            'status' => $request->status
        ]);

        return redirect()->route('kasir.pesanan.masuk')->with('success', 'Status pesanan #' . $pesanan->id . ' berhasil diperbarui menjadi ' . $request->status . '!');
    }
}