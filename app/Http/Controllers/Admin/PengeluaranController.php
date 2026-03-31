<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengeluaranController extends Controller
{
    // 1. Menampilkan daftar pengeluaran (Dilengkapi Search & Pagination)
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query dasar beserta relasi tabel admin
        $query = Pengeluaran::with('admin')
                    ->orderBy('tanggal_pengeluaran', 'desc')
                    ->orderBy('created_at', 'desc');

        // Jika ada inputan pencarian
        if ($search) {
            $query->where('keterangan', 'like', "%{$search}%");
        }

        // Pagination 10 data per halaman
        $pengeluaran = $query->paginate(10)->withQueryString();

        return view('admin.pengeluaran.index', compact('pengeluaran', 'search'));
    }

    // 2. Menyimpan data pengeluaran baru (Dari Modal Tambah)
    public function store(Request $request)
    {
        $request->validate([
            'keterangan'          => 'required|string|max:255',
            'nominal'             => 'required|numeric|min:1',
            'tanggal_pengeluaran' => 'required|date',
        ]);

        Pengeluaran::create([
            'id_admin'            => Auth::id(), // Otomatis mencatat ID Admin yang login
            'keterangan'          => $request->keterangan,
            'nominal'             => $request->nominal,
            'tanggal_pengeluaran' => $request->tanggal_pengeluaran,
        ]);

        return redirect()->route('admin.pengeluaran.index')->with('success', 'Catatan pengeluaran berhasil ditambahkan!');
    }

    // 3. Memperbarui data pengeluaran (Dari Modal Edit)
    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $request->validate([
            'keterangan'          => 'required|string|max:255',
            'nominal'             => 'required|numeric|min:1',
            'tanggal_pengeluaran' => 'required|date',
        ]);

        $pengeluaran->update([
            'keterangan'          => $request->keterangan,
            'nominal'             => $request->nominal,
            'tanggal_pengeluaran' => $request->tanggal_pengeluaran,
            // id_admin tidak diubah agar riwayat pembuat aslinya tetap terjaga
        ]);

        return redirect()->route('admin.pengeluaran.index')->with('success', 'Catatan pengeluaran berhasil diperbarui!');
    }

    // 4. Menghapus data pengeluaran
    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();
        return redirect()->route('admin.pengeluaran.index')->with('success', 'Catatan pengeluaran berhasil dihapus!');
    }
}