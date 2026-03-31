<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        // Ambil data pengaturan pertama, jika belum ada, buat default-nya
        $pengaturan = Pengaturan::firstOrCreate([], [
            'nama_toko'     => 'SUSI STATIONARY',
            'alamat'        => 'Jl. Pramuka, Bengkalis',
            'no_telp'       => '0812-3456-7890',
            'pesan_penutup' => 'Terima kasih atas kunjungan Anda. Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.'
        ]);

        return view('kasir.pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_toko'     => 'required|string|max:100',
            'alamat'        => 'required|string|max:255',
            'no_telp'       => 'required|string|max:20',
            'pesan_penutup' => 'nullable|string|max:255',
        ]);

        $pengaturan = Pengaturan::first();
        $pengaturan->update($request->all());

        return back()->with('success', 'Format Struk berhasil diperbarui!');
    }
}