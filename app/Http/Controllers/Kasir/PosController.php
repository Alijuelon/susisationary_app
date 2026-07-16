<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailTransaksi;
use App\Models\Layanan;
use App\Models\Membership;
use App\Models\Pengaturan;
use App\Models\Pesanan;
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

        // Ambil pesanan online yang berstatus "Siap Diambil"
        $pesananSiap = Pesanan::with(['pelanggan', 'layanan'])
            ->where('status', 'Siap Diambil')
            ->orderBy('created_at', 'asc')
            ->get();

        // Ambil pengaturan membership
        $pengaturan = Pengaturan::first();

        return view('kasir.pos.index', compact('barang', 'layanan', 'pesananSiap', 'pengaturan'));
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

        $totalBayarAwal = (float) $request->total_bayar;
        $idMembership = null;
        $totalAkhir = $totalBayarAwal;

        // Cek membership
        if ($request->filled('id_membership')) {
            $membership = Membership::where('id', $request->id_membership)
                ->where('status', 'aktif')
                ->first();

            if ($membership) {
                $idMembership = $membership->id;
            }
        }

        if ($request->uang_masuk < $totalAkhir) {
            return back()->with('error', 'Uang pelanggan tidak cukup!');
        }

        // Gunakan DB Transaction agar jika ada error di tengah jalan, data di-rollback
        DB::beginTransaction();

        try {
            // 1. Buat Kode Transaksi Unik Otomatis
            $kodeUnik = 'TRX-'.date('Ymd').'-'.strtoupper(Str::random(5));

            $idPelanggan = null;
            if ($request->filled('id_pesanan_online')) {
                $idPelanggan = Pesanan::find($request->id_pesanan_online)?->id_pelanggan;
            } elseif (isset($membership) && $membership) {
                $idPelanggan = $membership->id_pelanggan;
            }

            // 2. Simpan ke tabel transaksi
            $transaksi = Transaksi::create([
                'kode_transaksi' => $kodeUnik,
                'id_kasir' => Auth::id(),
                'id_pelanggan' => $idPelanggan,
                'id_pesanan_online' => $request->id_pesanan_online,
                'id_membership' => $idMembership,
                'diskon_persen' => 0,
                'total_sebelum_diskon' => null,
                'nama_pelanggan' => $request->nama_pelanggan,
                'total_harga' => $totalAkhir,
                'uang_bayar' => $request->uang_masuk,
                'kembalian' => $request->uang_masuk - $totalAkhir,
                'status' => 'Berhasil',
            ]);

            // Pelindung Ganda: Pastikan kita benar-benar mendapatkan ID-nya
            $id_transaksi_baru = $transaksi->id;
            if (! $id_transaksi_baru) {
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
                    'id_transaksi' => $id_transaksi_baru,
                    'tipe_item' => ucfirst($item['tipe']),
                    'id_item' => $item['id'],
                    'harga_satuan' => $item['harga'],
                    'qty' => $item['qty'],
                    'subtotal' => $item['harga'] * $item['qty'],
                ]);
            }

            // 4. Update status pesanan online ke "Selesai" jika dari pesanan online
            if ($request->filled('id_pesanan_online')) {
                $pesanan = Pesanan::find($request->id_pesanan_online);
                if ($pesanan) {
                    $pesanan->update(['status' => 'Selesai']);
                }
            }

            DB::commit();

            // Redirect ke halaman struk
            return redirect()->route('kasir.pos.struk', $id_transaksi_baru)
                ->with('success', 'Transaksi berhasil disimpan! Kode: '.$kodeUnik);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    // 3. Cetak Struk Transaksi
    public function cetakStruk($id)
    {
        $transaksi = Transaksi::with(['detail', 'kasir', 'pelanggan', 'membership.pelanggan', 'pesananOnline.opsi'])
            ->findOrFail($id);

        $toko = Pengaturan::first();

        return view('kasir.pos.struk', compact('transaksi', 'toko'));
    }
}
