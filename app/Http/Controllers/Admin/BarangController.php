<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    // 1. Menampilkan daftar barang (Dilengkapi Search & Pagination)
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query dasar
        $query = Barang::orderBy('created_at', 'desc');

        // Jika ada inputan pencarian
        if ($search) {
            $query->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%");
        }

        // Pagination 10 data per halaman dan membawa parameter pencarian di URL
        $barang = $query->paginate(10)->withQueryString();

        return view('admin.barang.index', compact('barang', 'search'));
    }

    // 2. Menyimpan data barang baru (Dari Modal Tambah)
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang'  => 'required|string|max:50|unique:barang,kode_barang',
            'nama_barang'  => 'required|string|max:100',
            'harga_jual'   => 'required|numeric|min:0',
            'stok'         => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:1',
        ]);

        Barang::create($request->all());

        return redirect()->route('admin.barang.index')->with('success', 'Data barang ATK berhasil ditambahkan!');
    }

    // 3. Memperbarui data barang (Dari Modal Edit)
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kode_barang'  => 'required|string|max:50|unique:barang,kode_barang,' . $barang->id,
            'nama_barang'  => 'required|string|max:100',
            'harga_jual'   => 'required|numeric|min:0',
            'stok'         => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:1',
        ]);

        $barang->update($request->all());

        return redirect()->route('admin.barang.index')->with('success', 'Data barang ATK berhasil diperbarui!');
    }

    // 4. Menghapus data barang
    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('admin.barang.index')->with('success', 'Data barang ATK berhasil dihapus!');
    }
}