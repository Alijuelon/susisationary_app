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
        $layanan = Layanan::with('opsiLayanan')->get();
        return view('pelanggan.pesanan.create', compact('layanan'));
    }

    // Memproses unggahan file dan menyimpan pesanan
    public function store(Request $request)
    {
        $request->validate([
            'id_layanan'       => 'required|exists:layanans,id',
            'file_dokumen'     => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'jumlah_rangkap'   => 'required|integer|min:1',
            'catatan_tambahan' => 'nullable|string|max:500',
            'opsi'             => 'nullable|array',
            'opsi.*'           => 'exists:opsi_layanans,id',
        ], [
            'file_dokumen.mimes' => 'Format file harus PDF, Word, atau Gambar.',
            'file_dokumen.max'   => 'Ukuran file maksimal adalah 5MB.',
        ]);

        $filePath = $request->file('file_dokumen')->store('dokumen_pesanan', 'public');
        
        $layanan = Layanan::findOrFail($request->id_layanan);
        $totalHarga = $layanan->harga_satuan;
        
        $opsiDibeli = [];
        if ($request->opsi) {
            $opsiList = \App\Models\OpsiLayanan::whereIn('id', $request->opsi)->get();
            foreach ($opsiList as $o) {
                $totalHarga += $o->harga;
                $opsiDibeli[] = $o;
            }
        }
        
        $totalHarga = $totalHarga * $request->jumlah_rangkap;

        $pesanan = Pesanan::create([
            'id_pelanggan' => Auth::id(),
            'id_layanan'   => $request->id_layanan,
            'file_dokumen' => $filePath,
            'qty'          => $request->jumlah_rangkap,
            'total_harga'  => $totalHarga,
            'catatan'      => $request->catatan_tambahan,
            'status'       => 'Menunggu',
        ]);
        
        foreach ($opsiDibeli as $o) {
            \App\Models\PesananOpsi::create([
                'id_pesanan' => $pesanan->id,
                'id_opsi_layanan' => $o->id,
                'kategori' => $o->kategori,
                'nama_opsi' => $o->nama_opsi,
                'harga' => $o->harga,
            ]);
        }

        return redirect()->route('pelanggan.riwayat')->with('success', 'Pesanan berhasil dikirim! Total Rp ' . number_format($totalHarga, 0, ',', '.') . '. Silakan pantau statusnya di sini.');
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
        $pesanan = Pesanan::with(['layanan', 'pelanggan', 'opsi'])
            ->where('id_pelanggan', Auth::id()) // Proteksi: Hanya bisa buka struk miliknya sendiri
            ->findOrFail($id);

        return view('pelanggan.struk', compact('pesanan'));
    }
}