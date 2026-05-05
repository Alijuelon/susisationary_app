<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $tgl_mulai = $request->input('tgl_mulai');
        $tgl_akhir = $request->input('tgl_akhir');

        // Query dasar: Hanya ambil transaksi milik kasir yang sedang login
        $query = Transaksi::where('id_kasir', Auth::id())
                          ->orderBy('created_at', 'desc');

        // Fitur Pencarian ID Transaksi / Nama Pelanggan
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('kode_transaksi', 'like', "%{$search}%")
                  ->orWhere('nama_pelanggan', 'like', "%{$search}%");
            });
        }

        // Fitur Filter Status
        if ($status && $status != 'Semua') {
            $query->where('status', $status);
        }

        // Fitur Filter Rentang Tanggal
        if ($tgl_mulai && $tgl_akhir) {
            $query->whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_akhir . ' 23:59:59']);
        } elseif ($tgl_mulai) {
            $query->where('created_at', '>=', $tgl_mulai . ' 00:00:00');
        } elseif ($tgl_akhir) {
            $query->where('created_at', '<=', $tgl_akhir . ' 23:59:59');
        }

        // Pagination 10 data per halaman
        $riwayat = $query->paginate(10)->withQueryString();

        return view('kasir.riwayat.index', compact('riwayat', 'search', 'status', 'tgl_mulai', 'tgl_akhir'));
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::where('id', $id)->where('id_kasir', Auth::id())->firstOrFail();
        $transaksi->delete();

        return redirect()->back()->with('success', 'Riwayat transaksi berhasil dihapus dari sistem.');
    }

    public function destroyBulk(Request $request)
    {
        $ids = $request->input('selected_ids');
        
        if (!$ids || count($ids) == 0) {
            return redirect()->back()->with('error', 'Belum ada data yang dipilih untuk dihapus.');
        }

        Transaksi::whereIn('id', $ids)->where('id_kasir', Auth::id())->delete();

        return redirect()->back()->with('success', count($ids) . ' transaksi berhasil dihapus sekaligus.');
    }
}