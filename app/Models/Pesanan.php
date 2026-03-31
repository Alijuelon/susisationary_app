<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit (opsional tapi aman)
    protected $table = 'pesanans';

    protected $fillable = [
        'id_pelanggan',
        'id_layanan',
        'file_dokumen',
        'catatan',
        'status',
    ];

    /**
     * Relasi ke model User (sebagai pelanggan)
     */
    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'id_pelanggan');
    }

    /**
     * Relasi ke model Layanan (jasa yang dipilih)
     */
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan');
    }
}