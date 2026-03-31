<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table = 'pengeluaran';

    protected $fillable = [
        'id_admin',
        'keterangan',
        'nominal',
        'tanggal_pengeluaran'
    ];

    // Relasi: Pengeluaran ini dicatat oleh Admin siapa?
    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin');
    }
}
