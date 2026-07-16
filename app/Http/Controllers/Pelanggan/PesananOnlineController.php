<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\DetailTransaksiOpsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
            'items'                     => 'required|array|min:1',
            'items.*.id_layanan'        => 'required|exists:layanans,id',
            'items.*.file_dokumen'      => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'items.*.jumlah_rangkap'    => 'required|integer|min:1',
            'items.*.catatan_tambahan'  => 'nullable|string|max:500',
            'items.*.opsi'              => 'nullable|array',
            'items.*.opsi.*'            => 'exists:opsi_layanans,id',
        ], [
            'items.*.file_dokumen.mimes' => 'Format file harus PDF, Word, atau Gambar.',
            'items.*.file_dokumen.max'   => 'Ukuran file maksimal adalah 5MB.',
            'items.required'             => 'Minimal pilih 1 layanan.',
        ]);

        DB::beginTransaction();
        try {
            $kodeUnik = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            $totalTransaksi = 0;

            $transaksi = Transaksi::create([
                'kode_transaksi'    => $kodeUnik,
                'id_pelanggan'      => Auth::id(),
                'tipe_transaksi'    => 'Online',
                'status'            => 'Menunggu',
                'nama_pelanggan'    => Auth::user()->nama_lengkap ?? Auth::user()->name,
                'total_harga'       => 0, // Akan diupdate nanti
                'uang_bayar'        => 0,
                'kembalian'         => 0,
                'metode_pembayaran' => 'Cash',
            ]);

            foreach ($request->items as $itemData) {
                $layanan = Layanan::findOrFail($itemData['id_layanan']);
                $filePath = $itemData['file_dokumen']->store('dokumen_pesanan', 'public');
                
                $hargaSatuan = $layanan->harga_satuan;
                $opsiDibeli = [];

                if (isset($itemData['opsi'])) {
                    $opsiList = \App\Models\OpsiLayanan::whereIn('id', $itemData['opsi'])->get();
                    foreach ($opsiList as $o) {
                        $hargaSatuan += $o->harga;
                        $opsiDibeli[] = $o;
                    }
                }

                $qty = $itemData['jumlah_rangkap'];
                $subtotal = $hargaSatuan * $qty;
                $totalTransaksi += $subtotal;

                $detail = DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id,
                    'tipe_item'    => 'Layanan',
                    'id_item'      => $layanan->id,
                    'nama_item'    => $layanan->nama_layanan,
                    'harga_satuan' => $hargaSatuan,
                    'qty'          => $qty,
                    'subtotal'     => $subtotal,
                    'file_dokumen' => $filePath,
                    'catatan'      => $itemData['catatan_tambahan'] ?? null,
                ]);

                foreach ($opsiDibeli as $o) {
                    DetailTransaksiOpsi::create([
                        'id_detail_transaksi' => $detail->id,
                        'id_opsi_layanan'     => $o->id,
                        'kategori'            => $o->kategori,
                        'nama_opsi'           => $o->nama_opsi,
                        'harga'               => $o->harga,
                    ]);
                }
            }

            $transaksi->update(['total_harga' => $totalTransaksi]);

            DB::commit();
            return redirect()->route('pelanggan.riwayat')->with('success', 'Pesanan berhasil dikirim! Total Rp ' . number_format($totalTransaksi, 0, ',', '.') . '. Silakan pantau statusnya di sini.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Menampilkan riwayat pesanan
    public function riwayat(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $tglMulai = $request->input('tgl_mulai');
        $tglAkhir = $request->input('tgl_akhir');

        $query = Transaksi::with(['detail.layanan'])
            ->where('id_pelanggan', Auth::id())
            ->where('tipe_transaksi', 'Online')
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->whereHas('detail', function ($q) use ($search) {
                $q->where('nama_item', 'like', "%{$search}%")
                  ->orWhere('catatan', 'like', "%{$search}%");
            })->orWhere('kode_transaksi', 'like', "%{$search}%");
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
        $transaksi = Transaksi::where('id_pelanggan', Auth::id())->where('tipe_transaksi', 'Online')->findOrFail($id);
        
        if ($transaksi->status !== 'Menunggu') {
            return redirect()->route('pelanggan.riwayat')->with('error', 'Hanya pesanan berstatus Menunggu yang dapat dibatalkan.');
        }

        $transaksi->delete();
        return redirect()->route('pelanggan.riwayat')->with('success', 'Pesanan berhasil dibatalkan.');
    }

    // Menghapus massal riwayat pesanan (Hanya jika menunggu)
    public function destroyBulk(Request $request)
    {
        $ids = $request->input('selected_ids');
        
        if (!$ids || count($ids) == 0) {
            return redirect()->route('pelanggan.riwayat')->with('error', 'Belum ada pesanan yang dipilih untuk dibatalkan.');
        }

        $count = Transaksi::where('id_pelanggan', Auth::id())
                        ->where('tipe_transaksi', 'Online')
                        ->where('status', 'Menunggu')
                        ->whereIn('id', $ids)
                        ->delete();

        if ($count > 0) {
            return redirect()->route('pelanggan.riwayat')->with('success', $count . ' pesanan berhasil dibatalkan sekaligus.');
        }

        return redirect()->route('pelanggan.riwayat')->with('error', 'Gagal membatalkan pesanan. Pastikan pesanan yang dipilih masih berstatus Menunggu.');
    }

    // Menampilkan & Mencetak Struk Pesanan Online
    public function downloadStruk($id)
    {
        $transaksi = Transaksi::with(['detail.layanan', 'detail.opsi', 'pelanggan', 'kasir'])
            ->where('id_pelanggan', Auth::id())
            ->findOrFail($id);

        return view('pelanggan.struk', compact('transaksi'));
    }
}