<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksiOpsi extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi_opsi';

    protected $fillable = [
        'id_detail_transaksi',
        'id_opsi_layanan',
        'kategori',
        'nama_opsi',
        'harga',
    ];

    public function detailTransaksi()
    {
        return $this->belongsTo(DetailTransaksi::class, 'id_detail_transaksi');
    }

    public function opsiLayanan()
    {
        return $this->belongsTo(OpsiLayanan::class, 'id_opsi_layanan');
    }
}
