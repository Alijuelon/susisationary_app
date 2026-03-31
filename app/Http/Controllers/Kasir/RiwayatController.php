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

        // Query dasar: Hanya ambil transaksi milik kasir yang sedang login
        $query = Transaksi::where('id_kasir', Auth::id())
                          ->orderBy('created_at', 'desc');

        // Fitur Pencarian berdasarkan ID Transaksi
        if ($search) {
            $query->where('id', 'like', "%{$search}%");
        }

        // Pagination 10 data per halaman
        $riwayat = $query->paginate(10)->withQueryString();

        return view('kasir.riwayat.index', compact('riwayat', 'search'));
    }
}