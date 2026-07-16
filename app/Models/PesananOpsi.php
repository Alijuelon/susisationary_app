<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananOpsi extends Model
{
    use HasFactory;

    protected $table = 'pesanan_opsi';

    protected $fillable = [
        'id_pesanan',
        'id_opsi_layanan',
        'kategori',
        'nama_opsi',
        'harga',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan');
    }

    public function opsiLayanan()
    {
        return $this->belongsTo(OpsiLayanan::class, 'id_opsi_layanan');
    }
}
