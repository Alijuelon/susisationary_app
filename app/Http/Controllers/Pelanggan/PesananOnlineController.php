<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananOnlineController extends Controller
{
    // Menampilkan form pemesanan
    public function create()
    {
        $layanan = Layanan::all();
        return view('pelanggan.pesanan.create', compact('layanan'));
    }

    // Memproses unggahan file dan menyimpan pesanan
    public function store(Request $request)
    {
        $request->validate([
            'id_layanan'   => 'required|exists:layanans,id',
            'file_dokumen' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // Maks 5MB
            'catatan'      => 'nullable|string|max:500',
        ], [
            'file_dokumen.mimes' => 'Format file harus PDF, Word, atau Gambar.',
            'file_dokumen.max'   => 'Ukuran file maksimal adalah 5MB.',
        ]);

        // Menyimpan file ke dalam folder storage/app/public/dokumen_pesanan
        $filePath = $request->file('file_dokumen')->store('dokumen_pesanan', 'public');

        Pesanan::create([
            'id_pelanggan' => Auth::id(),
            'id_layanan'   => $request->id_layanan,
            'file_dokumen' => $filePath,
            'catatan'      => $request->catatan,
            'status'       => 'Menunggu',
        ]);

        return redirect()->route('pelanggan.riwayat')->with('success', 'Pesanan berhasil dikirim! Silakan pantau statusnya di sini.');
    }

    // Menampilkan riwayat pesanan
    public function riwayat(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $tglMulai = $request->input('tgl_mulai');
        $tglAkhir = $request->input('tgl_akhir');

        $query = Pesanan::with('layanan')
            ->where('id_pelanggan', Auth::id())
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->whereHas('layanan', function ($q) use ($search) {
                $q->where('nama_item', 'like', "%{$search}%");
            })->orWhere('catatan', 'like', "%{$search}%");
        }

        if ($status && $status !== 'semua') {
            $query->where('status', $status);
        }

        if ($tglMulai && $tglAkhir) {
            $query->whereBetween('created_at', [$tglMulai . ' 00:00:00', $tglAkhir . ' 23:59:59']);
        }

        $riwayat = $query->paginate(10)->withQueryString();

        return view('pelanggan.riwayat', compact('riwayat', 'search', 'status', 'tglMulai', 'tglAkhir'));
    }

    // Menghapus riwayat pesanan tunggal (Hanya jika masih menunggu)
    public function destroy($id)
    {
        $pesanan = Pesanan::where('id_pelanggan', Auth::id())->findOrFail($id);
        
        if ($pesanan->status !== 'Menunggu') {
            return redirect()->route('pelanggan.riwayat')->with('error', 'Hanya pesanan berstatus Menunggu yang dapat dibatalkan.');
        }

        $pesanan->delete();
        return redirect()->route('pelanggan.riwayat')->with('success', 'Pesanan berhasil dibatalkan.');
    }

    // Menghapus massal riwayat pesanan (Hanya jika menunggu)
    public function destroyBulk(Request $request)
    {
        $ids = $request->input('selected_ids');
        
        if (!$ids || count($ids) == 0) {
            return redirect()->route('pelanggan.riwayat')->with('error', 'Belum ada pesanan yang dipilih untuk dibatalkan.');
        }

        $count = Pesanan::where('id_pelanggan', Auth::id())
                        ->where('status', 'Menunggu')
                        ->whereIn('id', $ids)
                        ->delete();

        if ($count > 0) {
            return redirect()->route('pelanggan.riwayat')->with('success', $count . ' pesanan berhasil dibatalkan sekaligus.');
        }

        return redirect()->route('pelanggan.riwayat')->with('error', 'Gagal membatalkan pesanan. Pastikan pesanan yang dipilih masih berstatus Menunggu.');
    }

    // Menampilkan & Mencetak Struk Pesanan Online (BARU)
    public function downloadStruk($id)
    {
        $pesanan = Pesanan::with(['layanan', 'pelanggan'])
            ->where('id_pelanggan', Auth::id()) // Proteksi: Hanya bisa buka struk miliknya sendiri
            ->findOrFail($id);

        return view('pelanggan.struk', compact('pesanan'));
    }
}