<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $table = 'detail_transaksi';

    protected $fillable = [
        'id_transaksi', // <-- INI YANG BIKIN ERROR JIKA TIDAK ADA!
        'tipe_item',
        'id_item',
        'nama_item',    // Tambahkan jika di database Anda ada kolom nama_item
        'harga_satuan',
        'qty',
        'subtotal',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi');
    }

    // Fungsi Pembantu: Mengambil detail barang jika tipe_item = 'Barang'
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_item')->where('tipe_item', 'Barang');
    }

    // Fungsi Pembantu: Mengambil detail layanan jika tipe_item = 'Layanan'
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_item')->where('tipe_item', 'Layanan');
    }
}
