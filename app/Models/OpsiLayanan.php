<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpsiLayanan extends Model
{
    use HasFactory;

    protected $table = 'opsi_layanans';

    protected $fillable = [
        'id_layanan',
        'kategori',
        'nama_opsi',
        'harga',
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan');
    }
}
