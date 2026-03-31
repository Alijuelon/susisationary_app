<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailTransaksi;
use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosController extends Controller
{
    // 1. Menampilkan Halaman POS Kasir
    public function index()
    {
        // Ambil barang yang stoknya masih ada
        $barang = Barang::where('stok', '>', 0)->get();
        $layanan = Layanan::all();

        return view('kasir.pos.index', compact('barang', 'layanan'));
    }

    // 2. Memproses Pembayaran / Checkout
    public function store(Request $request)
    {
        $request->validate([
            'cart_data' => 'required|json',
            'total_bayar' => 'required|numeric|min:0',
            'uang_masuk' => 'required|numeric|min:0',
        ]);

        $cartData = json_decode($request->cart_data, true);

        if (empty($cartData)) {
            return back()->with('error', 'Keranjang belanja masih kosong!');
        }

        if ($request->uang_masuk < $request->total_bayar) {
            return back()->with('error', 'Uang pelanggan tidak cukup!');
        }

        // Gunakan DB Transaction agar jika ada error di tengah jalan, data di-rollback
        DB::beginTransaction();

        try {
            // 1. Buat Kode Transaksi Unik Otomatis
            $kodeUnik = 'TRX-'.date('Ymd').'-'.strtoupper(Str::random(5));

            // 2. Simpan ke tabel transaksi
            $transaksi = Transaksi::create([
                'kode_transaksi' => $kodeUnik,
                'id_kasir' => Auth::id(),
                'total_harga' => $request->total_bayar,
                'uang_bayar' => $request->uang_masuk,
                'kembalian' => $request->uang_masuk - $request->total_bayar,
                'status' => 'Berhasil',
            ]);

            // 🔥 PELINDUNG GANDA: Pastikan kita benar-benar mendapatkan ID-nya
            $id_transaksi_baru = $transaksi->id;
            if (! $id_transaksi_baru) {
                // Jika $transaksi->id kosong, ambil paksa ID dari database berdasarkan kode unik
                $id_transaksi_baru = Transaksi::where('kode_transaksi', $kodeUnik)->value('id');
            }

            // 3. Looping isi keranjang untuk memotong stok dan menyimpan detail
            foreach ($cartData as $item) {
                if ($item['tipe'] === 'barang') {
                    // Potong stok barang
                    $barang = Barang::findOrFail($item['id']);
                    if ($barang->stok < $item['qty']) {
                        throw new \Exception("Stok {$barang->nama_barang} tidak mencukupi!");
                    }
                    $barang->decrement('stok', $item['qty']);
                }

                // Simpan detail item ke database
                DetailTransaksi::create([
                    'id_transaksi' => $id_transaksi_baru, // <-- GUNAKAN VARIABEL PELINDUNG INI
                    'tipe_item' => ucfirst($item['tipe']),
                    'id_item' => $item['id'],
                    'harga_satuan' => $item['harga'],
                    'qty' => $item['qty'],
                    'subtotal' => $item['harga'] * $item['qty'],
                ]);
            }

            DB::commit();

            // Redirect ke halaman riwayat
            return redirect()->route('kasir.riwayat')->with('success', 'Transaksi berhasil disimpan! Kode: '.$kodeUnik);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }
}
