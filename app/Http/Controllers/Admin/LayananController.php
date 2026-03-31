<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    // 1. Menampilkan daftar layanan (Dilengkapi Search & Pagination)
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query dasar
        $query = Layanan::orderBy('created_at', 'desc');

        // Jika ada inputan pencarian
        if ($search) {
            $query->where('nama_layanan', 'like', "%{$search}%");
        }

        // Pagination 10 data per halaman
        $layanan = $query->paginate(10)->withQueryString();

        return view('admin.layanan.index', compact('layanan', 'search'));
    }

    // 2. Menyimpan data layanan baru (Dari Modal Tambah)
    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'satuan'       => 'required|string|max:20',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        Layanan::create($request->all());

        return redirect()->route('admin.layanan.index')->with('success', 'Harga layanan / jasa berhasil ditambahkan!');
    }

    // 3. Memperbarui data layanan (Dari Modal Edit)
    public function update(Request $request, Layanan $layanan)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'satuan'       => 'required|string|max:20',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        $layanan->update($request->all());

        return redirect()->route('admin.layanan.index')->with('success', 'Harga layanan / jasa berhasil diperbarui!');
    }

    // 4. Menghapus data layanan
    public function destroy(Layanan $layanan)
    {
        $layanan->delete();
        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil dihapus!');
    }
}