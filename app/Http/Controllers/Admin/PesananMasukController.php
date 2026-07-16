<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PesananMasukController extends Controller
{
    // 1. Menampilkan daftar pesanan online yang masuk
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filterStatus = $request->input('status');

        // Query dasar dengan relasi ke tabel user (pelanggan) dan detail
        $query = Transaksi::with(['pelanggan', 'detail.layanan'])
                        ->where('tipe_transaksi', 'Online')
                        ->orderByRaw("FIELD(status, 'Menunggu', 'Diproses', 'Siap Diambil', 'Selesai', 'Berhasil', 'Dibatalkan')")
                        ->orderBy('created_at', 'asc');

        // Pencarian berdasarkan Nama Pelanggan atau Kode Transaksi
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('pelanggan', function($q2) use ($search) {
                    $q2->where('nama_lengkap', 'like', "%{$search}%");
                })->orWhere('kode_transaksi', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan Status
        if ($filterStatus && $filterStatus !== 'Semua') {
            $query->where('status', $filterStatus);
        }

        // Pagination 10 data per halaman
        $pesanan = $query->paginate(10)->withQueryString();

        return view('admin.pesanan.index', compact('pesanan', 'search', 'filterStatus'));
    }

    // 2. Memperbarui Status Pesanan
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Diproses,Siap Diambil,Selesai,Berhasil,Dibatalkan',
        ]);

        $transaksi = Transaksi::where('tipe_transaksi', 'Online')->findOrFail($id);
        $transaksi->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.pesanan.masuk')->with('success', 'Status pesanan ' . $transaksi->kode_transaksi . ' berhasil diperbarui menjadi ' . $request->status . '!');
    }

    // 3. Menghapus satu pesanan
    public function destroy($id)
    {
        $transaksi = Transaksi::with('detail')->where('tipe_transaksi', 'Online')->findOrFail($id);
        
        foreach ($transaksi->detail as $dt) {
            if ($dt->file_dokumen && Storage::disk('public')->exists($dt->file_dokumen)) {
                Storage::disk('public')->delete($dt->file_dokumen);
            }
        }
        $transaksi->delete();

        return redirect()->back()->with('success', 'Pesanan online berhasil dihapus.');
    }

    // 4. Menghapus banyak pesanan sekaligus
    public function destroyBulk(Request $request)
    {
        $ids = $request->input('selected_ids');
        
        if (!$ids || count($ids) == 0) {
            return redirect()->back()->with('error', 'Belum ada data pesanan yang dipilih untuk dihapus.');
        }

        $transaksis = Transaksi::with('detail')->where('tipe_transaksi', 'Online')->whereIn('id', $ids)->get();
        foreach ($transaksis as $transaksi) {
            foreach ($transaksi->detail as $dt) {
                if ($dt->file_dokumen && Storage::disk('public')->exists($dt->file_dokumen)) {
                    Storage::disk('public')->delete($dt->file_dokumen);
                }
            }
            $transaksi->delete();
        }

        return redirect()->back()->with('success', count($ids) . ' pesanan online berhasil dihapus sekaligus.');
    }
}
