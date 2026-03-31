<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit
    protected $table = 'layanans';

    protected $fillable = [
        'nama_layanan',
        'satuan',
        'harga_satuan',
    ];
    
    // Relasi balik ke model Pesanan (Opsional)
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_layanan');
    }
}