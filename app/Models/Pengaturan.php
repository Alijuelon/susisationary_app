<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_toko',
        'alamat',
        'no_telp',
        'pesan_penutup',
        'membership_aktif',
        'diskon_member',
    ];

    protected $casts = [
        'membership_aktif' => 'boolean',
        'diskon_member' => 'decimal:2',
    ];
}