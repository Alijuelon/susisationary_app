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

    // Fungsi Pembantu: Mengambil detail barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_item');
    }

    // Fungsi Pembantu: Mengambil detail layanan
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_item');
    }
}
