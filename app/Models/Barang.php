<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    // Beri tahu Laravel nama tabel aslinya, karena jamak bahasa inggris dari barang bukan 'barangs'
    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'harga_jual',
        'stok',
        'stok_minimum'
    ];
}
