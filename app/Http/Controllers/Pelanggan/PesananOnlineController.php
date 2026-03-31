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
    public function riwayat()
    {
        $riwayat = Pesanan::with('layanan')
            ->where('id_pelanggan', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pelanggan.riwayat', compact('riwayat'));
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