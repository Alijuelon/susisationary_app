<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    // Konfigurasi Primary Key String (TRX-XXXX)
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'id_kasir',
        'id_pelanggan',
        'id_pesanan_online',
        'total_harga',
        'uang_bayar',
        'kembalian',
        'status',
        'kode_transaksi'
    ];

    public function kasir()
    {
        return $this->belongsTo(User::class, 'id_kasir');
    }

    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'id_pelanggan');
    }

    public function pesananOnline()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan_online');
    }

    // Relasi: 1 Transaksi punya banyak detail keranjang
    public function detail()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi');
    }
}
